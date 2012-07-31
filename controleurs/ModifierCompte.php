<?php
/**
 * Modification de compte 
 *
 */
class ControlModifierCompte extends Controleur {
	private $titre;
	
	protected function validite() {
		
		$n1 = $this->getParam ( 'prenom' );
		$n2 = $this->getParam ( 'nom' );
		$n2bis = $this->getParam ( 'titre' );
		$n3 = $this->getParam ( 'promo' );
		$n4 = $this->getParam ( 'id' );
		$n5 = $this->getParam ( 'caution' );
		$n6 = $this->getParam ( 'coopeman' );
                $n7 = $this->getParam ( 'ouvert' );
		
		$resultat = (! empty ( $n1 ) && ! empty ( $n2 ) || ! empty ( $n2bis )) && is_numeric ( $n3 ) && is_numeric ( $n4 ) && ($n5 == 2 || $n5 == 1 || $n5 == 0) && ($n6 == 0 || $n6 == 1) && ($n7 == 0 || $n7 == 1);
		
		if (! $resultat) {
			$this->addMessage ( 'Tous les paramètres ne sont pas renseignés' );
		} else {
			$surnom = $this->getParam ( 'surnom' );
			$this->nom = empty ( $n2bis ) ? ($n1 . (! empty ( $surnom ) ? ' "' . $surnom . '" ' : ' ') . $n2) : $n2bis;
		}
		return $resultat;
	}
	
	protected function action() {

		$compte = Compte::get($this->getParam('id'));

		$compte->nom = accents($this->nom);
		$compte->email = $this->getParam('email');
		$compte->promo = $this->getParam('promo');
		$compte->coopeman = $this->getParam('coopeman');

		/* 
		* Bug à résoudre, les comptes se fermaient automatiquemetn depuis compteListe.
		* Maintenant par défaut depuis compteListe, le compte passe à ouvert (<input type="hidden"...) 
		*/
                $compte->ouvert = $this->getParam('ouvert');

		switch ($this->getParam('caution')) {
		case 0:
			//Pas de caution
			$compte->caution = null;
			break;
		case 1:
			//S'il n'y en avait pas, on en met une. Sinon, on la laisse.
			if (!$compte->caution)
				$compte->caution = 'NOW()';
		case 2:
			//Il y en avait une et on en met une nouvelle
			$compte->caution = 'NOW()';
		}

	$difference = $this->getParam('depot') - $compte->solde;
	if ($difference)
		$compte->crediter($difference, Depot::Autre);


	$compte->sauver();
		
	$this->addMessage (array('Compte bien modifié'=>'ok'));
		
	//On redirige l'action vers l'affichage des infos du compte modifié
	$this->setCible ( 'Infos', $compte);

	return true;
	}
}
?>
