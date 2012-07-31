<?php
/**
 * Utilisé quand on "crédite" un compte. Ce crédit peut également être négatif !!
 */
class ControlCrediter extends Controleur {
        
	protected function validite() {
		
		$n1 = $this->getParam ( 'nom' );
		$n2 = $this->getParam ( 'montant' );
		$n3 = $this->getParam ( 'forme' );
		
		$resultat = ! empty ( $n1 ) && ! empty ( $n2 ) && is_numeric ( $n2 ) && ($n3 == 0 || $n3 == 1);
		if (!$resultat) {
			$this->addMessage ( 'Tous les paramètres ne sont pas renseignés');
		}
		return $resultat;
	}
	
	protected function action() {

		$compte = Compte::trouver($this->getParam('nom'));
		$compte->crediter($this->getParam('montant'), $this->getParam('forme'));
		$compte->sauver();

		$this->addMessage (array('Solde : ' . $compte->solde . ' €' => 'ok'));
		return true;
	}
}
