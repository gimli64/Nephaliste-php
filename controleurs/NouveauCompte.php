<?php
/**
 * Création de compte 
 *
 */
class ControlNouveauCompte extends Controleur {
	protected function validite() {
		
		$n1 = $this->getParam ( 'prenom' );
		$n2 = $this->getParam ( 'nom' );
		$n3 = $this->getParam ( 'promo' );
		$n4 = $this->getParam ( 'forme' );
		$n5 = $this->getParam ( 'caution' );
		$n6 = $this->getParam ( 'email' );
		
		$resultat = ! empty ( $n1 ) && ! empty ( $n2 ) && is_numeric ( $n3 ) && ($n4 == 1 || $n4 == 0) && ($n5 == 1 || $n5 == 0) && ($n6 == 1 || $n6 == 0);
		
		if (! $resultat) {
			$this->addMessage ( 'Tous les paramètres ne sont pas renseignés' );
		}
		return $resultat;
	}
	
	protected function action() {
		
		$surnom = $this->getParam ( 'surnom' );
		$titre = $this->getParam ( 'prenom' ) . (! empty ( $surnom ) ? ' "' . $surnom . '" ' : ' ') . $this->getParam ( 'nom' );
		
		$compte = new Compte();
		$compte->nom = accents($titre);
		$compte->email = $this->getParam('email');
		$compte->promo = $this->getParam('promo');
		$compte->solde = 0;
		$compte->caution = ($this->getParam('caution') == 1 ? 'NOW()' : null);

		$compte->crediter ($this->getParam('depot'), $this->getParam('forme'));

		$compte->sauver();

		$this->addMessage (array('Compte bien créé'=>'ok'));
                $this->setCible(RACINE);

		return true;
	}
}
?>
