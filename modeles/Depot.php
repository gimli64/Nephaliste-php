<?php

class Depot extends Modele {
	public $id;
    //Date par défaut
	public $date = 'NOW()';
	public $montant;
	public $forme;
	public $etat_solde;

	protected $compte;
	protected static $_classe_compte = 'Compte';

	protected static $_table = 'depots';

	//Constantes utilisées pour la forme du dépôt
	//Comment le faire dans le sens inverse ?
	const Cheque = 0;
	const Liquide = 1;
	const Autre = 2;

}
?>
