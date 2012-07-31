<?php

/*
 * Quand on exécute, l'objet est parsé dans une stdClass
 * On n'est donc pas attaché à une table
 *
 */
class AdvancedQuery extends Query {

	//Commander une jointure
	private $joint;

	//Colonne qu'on veut
	private $rows;

	/**
	 * Ajoute une jointure à la requête
	 * @param $modele	nom du modele avec lequel on veut joindre le modele actuel
	 * @param $from		attribut du modele $modele
	 * @param $to		attribut du modele $this->modele
	 */

	public function jointure ($modele, $from, $to) {
		$this->joint[] = 'INNER JOIN ' . $modele::getTable () . '
ON ' . $modele::getTable() . '.' . $from . '=' . $this->getTable () . '.' . $to;
		return $this;
	}

	/**
	 * Ajoute une colonne
	 *
	 * @param $formule	formule de la colonne voulu
	 * @param $as		nom qu'on veut donner. S'il n'est pas fourni, ce nom sera $formule
	 * @param $modele	nom du modele qui possède l'attribut. falcultatif. utile en cas de conflit. Rien de précisé ? $this->modele
	 */
	public function addRow ($nom, $modele=null) {
		$this->rows[] = ($modele===false ? '' : (($modele===null ? $this->getTable() :  $modele::getTable()). '.')) . $nom;
		return $this;
	}

	public function addSomme ($attribut, $nom, $modele=null) {
		return $this->addRow('SUM(' . $attribut . ') AS ' . $nom, false);
	}

	public function compter ($nom) {
		return $this->addRow('COUNT(*) as ' . $nom, false);
	}

	public function max ($attribut, $nom) {
		return $this->addRow('MAX(' . $attribut . ') AS ' . $nom, false);
	}

	public function min ($attribut, $nom) {
		return $this->addRow('MIN(' . $attribut . ') AS ' . $nom, false);
	}



	public function executer () {
		if (!$this->rows)
			throw new NoRowException ();

		$requete = 'SELECT ' . implode($this->rows, ', ') . ' FROM ' . $this->getTable () .
($this->joint ? PHP_EOL . implode ($this->joint, PHP_EOL) : '') .
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
			return $reponse->fetchAll (PDO::FETCH_CLASS, 'stdClass');
		}
		
	}


//	public function addModele ($nom) {
//		$this->modeles[] = $nom;

}
