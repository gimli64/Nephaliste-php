<?php

class Compte extends Modele {
	public $id;
	public $nom;
	public $email;
	public $solde = 0;
	public $coopeman = 0;
	public $promo;
	public $caution = null;
	public $ouvert = 1;

	protected static $_table = 'comptes';

	public function __toString() {
		return $this->nom;
	}

	public function ouvrir () {
		$this->ouvert = 1;
	}

	public function fermer () {
		$this->ouvert = 0;
	}	

	/**
	 * Rend un compte en fonction de son nom.
	 * Si ne trouve pas de réponse exacte, alors essaye de trouver un nom ressemblant
	 * @param $nom	nom fourni
	 * @return objet Compte correspondant
	 */
	public static function trouver ($nom) {
		$nom = accents($nom);
		try {
			$compte = Compte::instances()->get('nom=\'' . $nom . '\'');
		} catch (AucunResultatException $e) {
			$compte = Compte::instances()->get('nom LIKE \'%' . $nom . '%\'');
			//S'il jette encore une erreur, ce sera à l'endroit d'où on vient de la gérer
		}
		return $compte;
	}

	
	/**
	 * Crédite un compte : change le solde, crée un objet dépôt et le lie au compte
	 * @param $montant	le montant (peut être négatif !)
	 * @param $forme	0,1 ou 2
	 * @return	objet compte pour chaîner
	 */
	public function crediter ($montant, $forme) {

		$operation = new Depot ();
		$operation->montant = $montant;
		$operation->forme = $forme;
		$operation->compte = $this;
		$operation->etat_solde = ($this->solde + $montant); //On enregistre l'état du solde après crédit

		$this->lier ($operation);

		$this->solde += $montant;
		return $this;
	}

	public function debiter (Recette &$consommation) {
		//On vérifie qu'on a le droit de débiter la personne : solde suffisant
		if ($this->solde >= $consommation->prix || $this->coopeman || ($this->solde+25 > $consommation->prix && $this->caution)) {

			$operation = new Historique_conso ();
			$operation->consommation = $consommation;
			$operation->compte = $this;  
			$operation->etat_solde = ($this->solde - $consommation->prix); //On enregistre l'état du solde après débit                  
			$this->lier ($operation);

			$this->solde -= $consommation->prix;
		} else {
			throw new SoldeInsuffisantException ($this->nom . ' n\'a plus assez d\'argent pour acheter ' . $consommation->nom);
		}

		//return $this;
		return $consommation->nom;
	}

	public function Historique_conso__tas () {
		return $this->__tas ('Historique_conso', 'compte');
	}

	public function Depot__tas () {
		return $this->__tas('Depot', 'compte');
	}
}
?>
