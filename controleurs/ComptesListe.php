<?php
class ControlComptesListe extends Controleur {
	public function  getCible() {
		return 'Comptes_Liste';
	}

	public function getOptions() {
		$comptes = Compte::instances()->trier('solde');
		$options = $this->getParams();
	/*
	 annees : rien, ou valeur, ou array de valeurs
	 ouverts
	 fermes
	 negatifs
	 positifs
	 coopeman
	 noncoopeman
	 caution
	 noncaution
	 */

		if ($options) {

			if (isset ($options['annees'])) {
				if (is_int($options['annees'])) {
					//Voir quand ce cas arrive : il semblerait que même si une seule année est sélectionnée, on reçoive sa valeur dans un array
					$comptes->filtrer('promo=' . $options['annees']);
				} elseif (is_array($options['annees'])) {
					$comptes->filtrer('promo IN (' . implode(',', $options['annees']) . ')');
				}
			}

			if (!isset($options['ouverts']) || !$options['ouverts']==1) {
				$comptes->filtrer('ouvert=0');
			}

			if (!isset($options['fermes']) || !$options['fermes']==1) {
				$comptes->filtrer('ouvert=1');
			}

			if (!isset($options['negatifs']) || !$options['negatifs']==1) {
				$comptes->filtrer('solde>0');
			}

			if (!isset($options['positifs']) || !$options['positifs']==1) {
				$comptes->filtrer('solde<0');
			}

			if (!isset($options['coopeman']) || !$options['coopeman']==1) {
				$comptes->filtrer('coopeman=0');
			}

			if (!isset($options['noncoopeman']) || !$options['noncoopeman']==1) {
				$comptes->filtrer('coopeman=1');
			}

			if (!isset($options['caution']) || !$options['caution']==1) {
				$comptes->filtrer('caution is null');
			}

			if (!isset($options['noncaution']) || !$options['noncaution']==1) {
				$comptes->filtrer('caution is not null');
			}

		} else {
			$comptes->filtrer('ouvert=1');
		}

		return $comptes;
	}
}
?>
