<?php
/**
 *
 * Dépose les chèques à la banque
 * Travaille sur les fichiers de données : vide soldeCheque et le met dans soldeBanque
 *
 */
class ControlDeposer extends Controleur {

	protected $regenerer = 'Statistiques';

	protected function action() {

		$banque = Parametre::trouver('soldeBanque');

		if ($this->getParam('cheques') == 1) {
			$cheques = Parametre::trouver('soldeCheque');

			$this->addMessage('Chèques déposés pour un montant de ' . $cheques->valeur . ' €');
			$banque->valeur += $cheques->valeur;
			$cheques->valeur = 0;
			$cheques->sauver();
		}

		if ($this->getParam('liquide') == 1) {
			$liquide = Parametre::trouver('soldeCaisse');

			$this->addMessage('Liquide déposé pour un montant de ' . $liquide->valeur . ' €');
			$banque->valeur += $liquide->valeur;
			$liquide->valeur = 0;
			$liquide->sauver();
		}

		$banque->sauver();

		return true;
	}
}
