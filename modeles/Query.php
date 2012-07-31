<?php
/**
 * Tout le travail des requetes. Retourné par un Modele.
 * Gérer la vérification des données : remplacement par ? dans condition et ajout à prepare
 * Gérer les requetes sur plusieurs tables : jointures, et les relations entre tables (relation ok, jointures : comment faire avec des objets dont les champs sont déjà déterminés ?)
 * Enlever les foreach et mettre l'itérateur dans le Modele, renvoie un array d'attributs avec les valeurs ?
 *
 * Une requête est liée, lors de sa construction, à une table.
 *
 * Quand une méthode n'est rien censée rendre, elle rend l'objet lui-même, pour pouvoir chaîner les appels
 *
 * Etat actuel : aucune jointure. Tout est chargé à la demande. ...
 *
 * @author Bruno Cauet <bruno.cauet@supelec.fr>, Jocelyn Caulet <jocelyn.cauet@supelec.fr>
 * @version 0.1
 */
class Query {

	/*
	 * Objet PDO, commun à toutes les Query
	 */
	protected static $DB = null;

	/*
	 * Clause de filtrage !
	 */
	protected $where;

        /*
        * Sélection du champ
        */
 
        protected $what;

	/*
	 * Clause de tri de la requête actuelle
	 */
	protected $tri;

	/*
	 * Clause LIMIT
	 */
	protected $limit;

	/*
	 * Clause GROUP BY
	 */
	protected $grouper;

	/*
	 * Nom de la classe héritant de Modèle
	 */
	public $modele;

	/*
	 * Indique si une transaction est en cours (true/false)
	 */
	protected static $transaction;

	/**
	 * Constructeur de la classe
	 *
	 * @param $modele	nom de la classe, héritant de Modele, sur laquelle on veut faire une requête
	 */
	public function __construct ($modele) {
		$this->modele = $modele;

		if (self::$DB === null) {
			//Première fois qu'on veut accéder à la DB : on ouvre une connexion
			//On pourrait aussi bien déférer ce traitement aux opérations save/delete/update

			try {
				self::$DB = new PDO ('mysql:host=localhost;dbname=nephaliste', 'coopeman', 'coope', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'') );

				self::$transaction = self::$DB->beginTransaction();
			} catch (PDOException $e) {
				echo '<pre>';
				echo 'Erreur lors de la connexion à la base de données : ' . PHP_EOL;
				echo 'Erreur : ' . $e->getMessage () . '' . PHP_EOL;
				echo 'No. : ' . $e->getCode () . '' . PHP_EOL;
				echo $e->getTraceAsString();
				echo '</pre>';
				exit;
			}
		}
	}

	/**
	 * Valide la transaction. Appelé à la fin du controleur.
	 * Pour la vue, il y aura self::$DB, mais pas de transaction. C'est pas grave, il n'y a que des select !
	 */
	public function fin() {

		if (self::$transaction) {
			try {
				self::$DB->commit();
				self::$transaction = false;
			} catch (PDOException $e) {
				echo '<pre>';
				echo $this->dest;
				echo 'Erreur lors de la validation de la transaction : ' . PHP_EOL;
				echo 'Erreur : ' . $e->getMessage () . '' . PHP_EOL;
				echo 'No. : ' . $e->getCode () . '' . PHP_EOL;
				echo $e->getTraceAsString();
				echo '</pre>';
				exit;
			}
		}

	}

	/**
	 * Renvoie le nom de la table
	 * Utilisé car la syntaxe de php ne permet pas :: et -> ensemble - et pas de passage de Classe, seulement de son nom
	 *
	 * @return le nom de la table MySQL correspondant au modèle
	 */
	protected function getTable () {
		return call_user_func ($this->modele . '::getTable');
	}


        /**
        * Rajoute un champ précis à sélectionner dans la table
        **/
        public function select ($what) {
                $this->what = $what; 
                return $this;   
        }

 
	/**
	 * Rajoute un order by dans la requete.
	 * usage : rien ou + (ASC/DESC) puis un attribut ou un calcul à effectuer par MySQL
	 * @param $ordre	chaine représentant cet ordre de tri
	 */
	public function trier ($ordre) {
		if ($ordre[0] === '-') {
			$this->tri[] = substr($ordre, 1) . ' DESC';
		} else {
			$this->tri[] = $ordre . ' ASC';
		}

		return $this;
	}
		

	/**
	 * Filtre sur un attribut : rajoute une clause where
	 * Pas de vérification de la syntaxe, car toute clause where acceptée par MySQL est utilisable
	 *
	 * @param $condition	condition de filtrage respectant la syntaxt MySQL
	 * @return $this pour chaînage
	 */
	public function filtrer ($condition) {
		$this->where[] = $condition;
		return $this;
	}

	/**
	 * Rajoute une clause GROUP BY
	 * @param $attribut	attribut selon lequel on veut grouper
	 * @return $this
	 */
	public function grouper ($by) {
		$this->grouper = $by;
		return $this;
	}
	
	/**
	 * Renvoie un résultat : ligne de mysql
	 *
	 * @param $condition	condition de filtrage respectant la syntaxt MySQL
	 *
	 * @return un unique résultat, objet de type $this->modele
	 */
	public function get ($condition) {
		$this->filtrer ($condition);
		$resultat = $this->executer ();

		if (count ($resultat) > 1)
			//Trop de résultats => nouvelle exception
			throw new TropDeResultatsException ('classe ' . $this->modele . ' : la requete a renvoyé plus d\'un résultat lors du get');
		if (count ($resultat) == 0)
			throw new AucunResultatException ('classe ' . $this->modele . ' : la requete n\'a renvoyé aucun résultat');
		
		return $resultat[0];
	}

	/**
	 * clause LIMIT de la requête
	 *
	 * @param $a	rang inférieur de la limite
	 * @param $de	rang supérieur de la limite
	 */
	public function limiter ($de, $a) {
		$this->limit = array('de' => $de, 'a' => $a);

		return $this;
	}

	/**
	 * Execute une requete
	 * La table, les join, les where
	 *
	 * @returns tableau des lignes renvoyées, sous forme d'objet $modele
	 */
	public function executer () {
			$requete = 'SELECT '.
($this->what ? PHP_EOL . $this->what : '*'). ' FROM ' . $this->getTable () .
($this->where ? PHP_EOL . 'WHERE ' . implode ($this->where, ' and ') : '') . 
($this->grouper ? PHP_EOL . 'GROUP BY ' . $this->grouper : '') .
($this->tri ? PHP_EOL . 'ORDER BY ' . implode ($this->tri, ', ') : '') .
($this->limit ? PHP_EOL . 'LIMIT ' . $this->limit['de'] . ', ' . $this->limit['a']  : '') .
';';
		$reponse = self::$DB->prepare ($requete);
		$reussite = $reponse->execute (array ());

		if (!$reussite) {
			print_r (self::$DB->errorInfo ());
			return null;
		} else {
			return $reponse->fetchAll (PDO::FETCH_CLASS, $this->modele);
		}
	}

	/**
	 * Nettoie la valeur pour mySQL. Utilisé par sauver et maj
	 * Si c'est NOW() ou un entier, on ne l'échappe pas. Si c'est null, on le transforme en 'null' et on ne l'échappe pas
	 * @param $valeur	variable à nettoyer
	 * @return variable nettoyée
	 */
	private static function nettoyer ($valeur) {
		return $valeur === null ? 'null' : ((is_string($valeur) && $valeur !== 'NOW()') ? ('\'' . preg_replace('/\'/', '/\\\'/', $valeur) . '\'') : $valeur);
	}

	/**
	 * Sauvegarde un nouvel objet
	 *
	 * @param $valeurs	valeurs d'un objet de type $this->modele qu'on veut sauvegarder, sous forme d'un array. Plutôt appelé par le modèle que directement.
	 * @return id de l'objet si réussite, null sinon
	 */
	public function sauver (array &$valeurs) {
		$requete = 'INSERT INTO ' . $this->getTable () . '
(';

		//On récupère les attributs du modèle (= les colonnes de la table) et leur valeur
		foreach ($valeurs as $nom => $valeur) {
			$noms[] = $nom;
			$vals[] = self::nettoyer ($valeur);
		}

		$requete .= implode (', ', $noms) . ')
VALUES(' . implode (', ', $vals) . ')';

		$reussite = self::$DB->exec($requete);

		if (!$reussite) {
			print_r (self::$DB->errorInfo ());
			return null;
		} else {
			return (int) self::$DB->lastInsertId ();
		}
	}


	/**
	 * Met un objet à jour
	 *
	 * @param &$valeurs	valeurs d'un objet de type $this->modele qu'on veut mettre à jour. Plutôt appelé par le modèle que directement.
	 * @return bool réussite de la requête
	 */
	public function maj (array &$valeurs) {
		$requete = 'UPDATE ' . $this->getTable () . '
SET ';

	//On construit la requête
	foreach ($valeurs as $nom => $valeur) {
		if ($nom === 'id')
			continue;
		$req[] = $nom . '=' . self::nettoyer($valeur);
	}

		$requete .= implode (', ', $req) . '
WHERE id=' . $valeurs['id'];

		$reussite = self::$DB->exec($requete);

		return $reussite;
	}

        //fonction pas super propre pour mettre à jour un paramètre, à revoir !!!
        public function maj_param ($nom, $valeur) {
                  $requete = 'UPDATE '.$this->getTable().' SET valeur = \''.$valeur.'\' WHERE nom = \''.$nom.'\'';
                  self::$DB->exec($requete);
        }
                

	/**
	 * Supprime un objet de la DB
	 * @param $objet	objet de type $this->modele qu'on veut supprimer. Plutôt appelé par le modèle que directement.
	 * @return l'objet fourni
	 */
	public function delete (Modele &$objet) {
		$requete = 'DELETE FROM ' . $this->getTable () . '
WHERE id=' . $objet->id;
		
		self::$DB->exec($requete);
		$objet->id = null;

		return $objet;
	}

}
?>
