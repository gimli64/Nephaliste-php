<?php
class ControlNouvelleRecette extends Controleur {


	protected $regenerer = array ('Debiter', 'Modifier_Recettes', 'Frontend', 'Etat_Stock');

	protected function validite() {

		$param1 = $this->getParam('nom');
		$param2 = $this->getParam('prix');
		$ok = !empty ($param1) && is_numeric($param2);

		return $ok;
	}

	protected function action() {

		$recette = new Recette();
		$recette->nom = $this->getParam('nom');
		$recette->prix = $this->getParam('prix');
                $recette->stock = 10;
                $recette->bouton = 0;
		$recette->sauver();

		$this->addMessage(array('Création d\'une nouvelle recette'=>'ok'));
                $this->setCible(RACINE);

		return true;
	}
}
?>
