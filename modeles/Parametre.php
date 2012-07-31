<?php
class Parametre extends Modele {
	public $nom;
	public $valeur;

	protected static $_table = 'parametres';

	public function __toString() {
		return $this->valeur;
	}

	//les paramètres sont un peu différents des autres modèles... au final, on les repère par le nom et on veut juste la valeur !
	//On crée des des fonctions permettant de réaliser plus simplement ces buts
	//
	//Il y a un problème : on recherche avec le nom, qui n'est pas spécifié comme clé (seul l'id l'est). Les instances sont indexées avec cet id. bouuuuh. pas beau.
	
	/**
	 * Rend un paramètre en fonction de son nom.
	 * @param $nom	nom fourni
	 * @return objet Paramtre correspondant
	 */
	public static function trouver ($nom) {
		return Parametre::instances()->get('nom=\'' . $nom . '\'');
	}

	public static function valeur ($nom) {
		return Parametre::trouver($nom)->valeur;
	}
 
        public function sauver ($valeur) {
                $requete = new Query(get_called_class());
                $requete->maj_param($this->nom,$valeur);
        }
	
}
?>
