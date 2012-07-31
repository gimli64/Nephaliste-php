<?php

class Historique_conso extends Modele {
	public $id;
	public $date;
	public $etat_solde;

	//ForeignKey et table de la foreign key
	protected $consommation;
	protected static $_classe_consommation = 'Recette';
	protected $compte;
	protected static $_classe_compte = 'Compte';

	
	protected static $_table = 'historique_conso';
   
        public function getCompte() {
           return $this->compte;
        } 
}
?>
