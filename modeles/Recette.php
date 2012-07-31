<?php

class Recette extends Modele {
	public $id;
	public $prix;
	public $nom;
	public $disponible = 1;
        public $stock;
        public $bouton;
	public $soiree;

	protected static $_table = 'recettes';

	public function __toString() {
		return $this->nom;
	}
}
?>
