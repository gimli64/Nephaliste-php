<?php
/**
 * Petit ORM
 * On l'utilise en en héritant
 * Avantage d'en hériter : on peut définir d'autres méthodes, surcharger celles fournies
 *
 * classe = table
 * objet = enregistrement
 *
 * @author Bruno Cauet <bruno.cauet@supelec.fr>, Jocelyn Caulet <jocelyn.cauet@supelec.fr>
 * @version 0.1
 */

abstract class Modele {

	/**
	 * Nom de la table dont le modèle s'occupe
	 */
	protected static $_table;


	/**
	 * Colonnes obligatoires de la table
	 */
	public $id;


	/**
	 * Objets héritant de Modele déjà chargés (cache)
	 */
	protected static $instances;

	/**
	 * Éléments à sauvegarder quand l'objet actuel sera sauvegardé
	 */
	private $objetsLies = array();


	//Frontend

	/**
	 * Constructeur : enregistre l'élément créé dans le tableau s'il existe déjà, et sinon ne fait rien
	 */
	public function __construct () {
		if (isset ($this->id)) {
			//L'objet a été créé par PDO (dans Query) en le récupérant dans la base. Le constructeur est appelé après le peuplement des champs, donc c'est bon : on peut enregistrer l'objet
			$this->enregistrer();
		}
		//Sinon, on l'enregistrera au sauver ()
	}

	
	/**
	 * Renvoie le nom de la table
	 * @return nom de la table associée à la classe
	 */
	public static function getTable () {
		return static::$_table;
	}
	
	/**
	 * Renvoie un objet de requetes
	 * @return objet Query lié à cette classe
	 */
	public static function instances () {
		return new Query (get_called_class ());
	}

	/**
	 * Lie un objet à $this, pour qu'il soit sauvegardé quand $this le sera (exemple : crédit d'un compte, on n'enregistre l'opération qu'à la sauvegarde de $this)
	 */
	protected function lier (Modele &$objet) {
		$this->objetsLies[] = $objet;
	}

	/**
	 * Rend un tableau nom => valeur pour chaque élément de l'objet. Utilisé par Query pour sauvegarder l'objet
	 * @return table nom => valeur réelle pour chaque attribut
	 */
	private function vraiesValeurs() {
		$valeurs = array();
		foreach ($this as $attribut => $valeur) {
			//On ne s'occupe pas du tableau des objets liés ni des attributs de gestion pure (classe pour les foreign key, table du modele)
			if ($attribut !== 'objetsLies' && $attribut[0] !== '_')
				$valeurs[$attribut] = $valeur;
		}
		return $valeurs;
	}
	
	/**
	 * Sauvegarde un objet, ou, s'il existe déjà (ligne dans la DB, son id existe), le met à jour
	 * Sauvegarde également les objets qui y sont liés
	 * Query::sauver() a besoin des vraies valeurs de $this (donc de la foreign key et pas de l'objet pointé. Il ne peut pas les récupérer, il faut donc les lui fournir. Pour cela, au lieu de lui passer $this, on lui passe $this->vraiesValeurs();
	 * @return l'objet (chaînage des requetes)
	 */
	public function sauver () {
		$requete = new Query (get_called_class ());
		if (isset ($this->id)) {
			//On met à jour
			$requete->maj ($this->vraiesValeurs());
		} else {
			//On crée
			$id = $requete->sauver ($this->vraiesValeurs());
			$this->id = $id;
			//Et on l'enregistre !
			$this->enregistrer ();
		}

		//Enregistrement des objets liés. On le fait après avoir sauvé $this : il y a un risque si $this n'a pas encore été sauvé et que l'objet lié a besoin de son id
		foreach ($this->objetsLies as $clef => $objet) {
			$objet->sauver ();
			unset ($this->objetsLies[$clef]);
		}

		return $this;
	}

	
	/**
	 * Supprime un objet (de la DB, des instances enregistrées)
	 * Et s'il est lié quelque part ? - c'est ok : il y est par référence. Ca fait juste un indice qui disparaît (euh, c'est sûr ?)
	 */
	public function delete () {
		if ($this->id) {		//L'objet avait bien été enregistré
			$requete = new Query (get_called_class ());
			$requete->delete ($this);
			unset (self::$instances[get_called_class()][$this->id]);	//On l'enlève de la liste des objets enregistrés
			unset ($this->id);			//On supprime son id

			//Un unset($this) n'est pas possible
		}

		return null;
	}

	//Backend

	/**
	 * A utiliser dans les classes client sous la forme
	 * public function Classe__tas () {
	 * //blahblah
	 * $this->__tas (Classe, attributPointant)
	 * }
	 * Pour remonter une foreignKey : dans classe, attribut est une foreignKey vers la classe courante (celle de $this)
	 *
	 * Par exemple :
	 * $compte de type compte
	 * $compte->Historique_conso__tas()
	 *
	 *
	 * Problème : exécuter immédiat ! Pas glop ! -> on l'enlève
	 *
	 *
	 * @param $classe	Nom de la classe d'où part la foreign key
	 * @param $attribut	La foreign key
	 *
	 * //@return array d'instances de $classe avec leur foreign key $attribut pointant sur $this.
	 * @return Query pointant sur un array d'instances de $classe avec leur foreign key $attribut pointant sur $this.
	 */

	protected final function __tas ($classe, $attribut) {
		if ($classe::${'_classe_' . $attribut} === get_called_class()) {
			//On a fait gaffe à ce que n'importe quoi n'ait pas été demandé, c'est bon !
			return $classe::instances()->filtrer($attribut . '=' . $this->id);	//->executer();
		} else {
			throw new Exception ('Pas de ForeignKey correspondant');
		}
	}


	/**
	 * Appelé par __get pour récupérer un objet à partir de son id, sans le reprendre dans la DB si possible
	 * Euh bin en fait si besoin ça le télécharge. Utile pour __set aussi.
	 *
	 * @param $id	l'id de l'instance qu'on veut
	 * @return cette instance
	 */
	public static function get ($id) {
		return isset (self::$instances[get_called_class()][$id]) ? self::$instances[get_called_class()][$id] : static::instances()->get('id=' . $id);
	}

	/**
	 * Appelé lors d'une tentative d'accès à un attribut inacessible depuis l'extérieur
	 * Permet de gérer les Foreign keys : rend une instance de la classe en question
	 * Vérifie que l'attribut peut être demandé
	 *
	 * @param attribut non-visible de l'extérieur
	 * @return l'objet sur lequel la foreign key pointe
	 */
	public function __get ($cible) {
		if (isset ($this->{$cible}) && $cible[0] !== '_') {
			$classe = static::${'_classe_' . $cible};

			try  {
				return $classe::get ($this->{$cible});
			} catch (AucunResultatException $e) {
				//L'élément demandé n'existe pas
				return null;
			}
		}
		return null;
	}

	
	/**
	 * Tout comme get
	 *
	 * @param $cible	nom de la variable contenant l'objet lié qu'on veut changer
	 * @param $valeur	la valeur qu'on veut lui affecter
	 *
	 * @return l'objet (chaînage)
	 */
	public function __set ($cible,Modele $valeur) {
		if ($cible[0] !== '_') {
			$this->{$cible} =& $valeur->id;
		}
		return $this;
	}

	/**
	 * Ajoute l'objet actuel à self::$instances
	 */
	private function enregistrer () {
		$temp = self::$instances;
		$temp[get_called_class()][$this->id] = $this;
		self::$instances = $temp;

		return $this;
	}
}
