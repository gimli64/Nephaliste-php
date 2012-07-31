<?php
/**
 *
 * Gère la clôture de comptes, commandée par la vue Cloture.
 * Champ global + API simple
 *
 */
class ControlCloture extends Controleur {

	protected $regenerer = 'Creation';

	protected function validite() {

		$n1 = $this->getParam('annees_ouvertes');
		$n2 = $this->getParam('fermer_negatif');
		$n3 = $this->getParam('regles');

		$resultat = (!empty($n1) && ($n2 != null)) || !empty($n3);

		return $resultat;
	}

	protected function action() {

		//Commande de base
		$anneesOuvertes = (int) $this->getParam('annees_ouvertes');
		$fermerNegatif = (bool) $this->getParam('fermer_negatif');
		if (!empty ($anneesOuvertes) && ($fermerNegatif != null)) {

			$anneesOuvertes = Parametre::trouver('dernierePromo');
			$anneesOuvertes->valeur = $anneesOuvertes;
			$anneesOuvertes->sauver();

			$limite = $derniereAnnee - $anneesOuvertes + 1;
			$comptes = Comptes::instances()->get('promo=>' . $limite);
			foreach ($comptes as $compte) {
				//Tellement moche ! Il faudrait un itérable de Modeles
				$compte->ouvert = 1;
				$compte->sauver();
			}

			$comptes = Comptes::instances()->filtrer('promo!=0')->filtrer('promo<' . $limite);
			if (!$fermerNegatif)
				$comptes->filtrer('solde>0');
			$comptes->executer();

			foreach($comptes as $compte) {
				$compte->ouvert = 0;
				$compte->sauver();
			}

			$reponse = clotureSimple($anneesOuvertes, $fermerNegatif);
			$this->addMessage ($anneesOuvertes . ' années ouvertes. Les autres sont fermées, comptes en négatif' . ($fermerNegatif ? 'compris' : 'exclus');


			//On exécute finalement les commandes entrées manuellement
			$regles = $this->getParam('regles');
			if (!empty($regles)) {
			$commandes = explode(PHP_EOL, $regles);

			$comptes = Comptes::instances();
			$requete[0]['nom']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=0 WHERE nom=?');
			$requete[0]['annee']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=0 WHERE annee=?');

			$requete[1]['nom']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=1 WHERE nom=?');
			$requete[1]['annee']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=1 WHERE annee=?');

			$requete[1]['nom']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=1 WHERE nom=? and solde < 0');
			$requete[1]['annee']['tous'] = $DB->prepare ('UPDATE comptes SET ouvert=1 WHERE annee=? and solde < 0');


			foreach ($commandes as $commande) {
				//Chaque ligne
				$limite = strpos($commande, ' ');
				$type = substr($commande, 0, $limite);
				if ($type !== 'ouvrir' && $type !== 'fermer') {
					$resultat[] = 'Syntaxe incorrecte : "' . $commande . '" : "' . $type . '" n\'est pas une opération reconnue.';
					break;
				}

				$actions = explode (',', substr($commande,$limite+1));

				$ouvert = $type == 'ouvrir' ? 1 : 0;
				foreach ($actions as $action) {
					//Chaque symbole séparé par une virgule
					//On détermine si nom ou année
					$cible = 'tous';

					if ($action[0] == '[' && $action[strlen($action)-1] == ']' ) {
						//C'est un nom
						$categorie = 'nom';
						$action = substr($action,1,-1);

						$resultat[] = 'Compte de ' . $action . ' ' . ($ouvert ? 'ouvert' : 'fermé');

					} else {
						$categorie = 'annee';

						if($action[strlen($action)-1] == '*') {
							//On ostracise les négatifs
							$cible = 'pos';
							$action = substr($action, 0, -1);
						}

						if ($action[0] == '-') {
							//Année relative
							inclure('modeles/launcherDonnees.php');
							$action = getDonnees('dernierePromo') + intval($action);
						}

						$resultat[] = 'Année ' . $action . ' ' . ($ouvert ? 'ouverte' : 'fermée');
					}


					//On a déterminé les trois paramètres, on exécute
					$requete[$ouvert][$categorie][$cible]->execute(array($action));

				}

			}

		$this->addMessage(array($reponse=>'ok'));
		}

		return true;
	}
}
