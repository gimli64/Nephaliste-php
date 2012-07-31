<?php
/*
 * Liste d'éléments :
 * - {id}_nom
 * - {id}_prix
 * - {id}_disponible
 */

class ControlModifierRecettes extends Controleur {

	protected $regenerer = array ('Debiter', 'Modifier_Recettes', 'Frontend', 'Etat_Stock', 'Courses');

	protected function action() {

		$recettes_passees = array();
		$suppressions = $tentatives = $echecs = 0;

		$params = $this->getParams();

		foreach ($params as $clef => $param) {
			$id = strstr($clef, '_', true);
			
			//On regarde si on veut la supprimer
			//Défaut : on la télécharge avant de la supprimer !
			if (isset($params[$id . '_suppr']) && $params[$id . '_suppr'] == 1) {
				$recette = Recette::get($id);
				$recette->delete();
				$suppressions++;

			//Sinon normal : on regarde si déjà passé dans les modifs
			} elseif (!isset($recettes_passees[$id])) {

				//On s'en occupe !

				//On le marque comme passé
				$recettes_passees[$id] = true;

				try {
					$recette = Recette::get($id);
				} catch (AucunResultatException $e) {
					$recette = new Recette();
					$recette->nom = 'Inconnu';
					$recette->prix = 0;
					$recette->disponible = 0;
                                        $recette->stock= 10; 
                                        $recette->bouton=0;  
				}

				//On regarde si tous les paramètres sont bien au rendez-vous
				if (isset ($params[$id . '_nom']) && isset ($params[$id . '_prix']) && isset ($params[$id . '_disponible']) && isset($params[$id . '_stock']) && isset($params[$id.'_bouton']) && isset($params[$id . '_soiree'])) {

					$recette->nom = accents($params[$id . '_nom']);
					$recette->prix = $params[$id . '_prix'];
					$recette->disponible = $params[$id . '_disponible'];
                                        $recette->stock = $params[$id . '_stock'];
                                        $recette->bouton = $params[$id . '_bouton'];
					$recette->soiree = $params[$id . '_soiree'];
					$recette->sauver();
					$tentatives++;
				} else {
					$this->addMessage($recette->nom . ' : tous les paramètres ne sont pas fournis');
					$echecs++;
				}
			}
		}
  

		$this->addMessage(array('Suppression de ' . $suppressions . ' recette(s)'=>'ok'));
		$this->addMessage(array('Modification de ' . $tentatives . ' recette(s) (' . $echecs . ' échecs)'=>'ok'));

		return !$echecs;
	}
}
?>
