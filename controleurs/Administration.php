<?php
class ControlAdministration extends Controleur {

	protected $regenerer = array('Administration', 'Rechercher');

	protected function validite() {

		$resultat = count($_POST);
		if ($resultat == 0) {
			$this->addMessage('Aucune action demandée !');
			return false;
		}
		return true;
	}

	protected function action() {

		$annee = $this->getParam('annee');
		if (!empty($annee)) {
			$dernierePromo = Parametre::trouver('dernierePromo');
			$dernierePromo->valeur = $annee;
			$dernierePromo->sauver();

			$this->addMessage ( 'Promo courante : ' . $annee);
			return true;
		}


		return false;
	}
}
