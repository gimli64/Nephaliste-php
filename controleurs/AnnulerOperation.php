<?php
/**
 * Supprime une opération récente (erreur, plus de produit demandé, etc.). Arrive une champ de nom {id de l'opération à supprimer}... et de valeur "boum !" (si, si)
 */
class ControlAnnulerOperation extends Controleur {
	protected function validite() {
		return count($this->getParams()) >= 1;
	}

	protected function action() {
		$annulation = array_keys($this->getParams());
		$id = $annulation[0];

		//On supprime l'opération
		$operation = Historique_conso::instances()->get('id=' . $id);
		$operation->delete ();

		//Remboursement du compte
		$operation->compte->solde += $operation->consommation->prix;
		$operation->compte->sauver();
		$message = 'Remboursement effecuté pour ' . $operation->consommation->prix . ' €.';

		$this->addMessage($message,'ok');
                $this->setCible(RACINE);
	}
}
