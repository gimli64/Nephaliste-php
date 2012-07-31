<?php
/**
 * Change le statut de la Coopé : ouvert -> fermé
 * Fonctionne de paire avec ControlOuverture
 * Pas un super solution mais c'est pour éviter les rafraichissements de page intempestif au moment de fermer.
 */
class ControlFermeture extends Controleur {

	protected $regenerer = array('Footer','HistoriqueCaisse');

	protected function action() {
                
                //On récupère les objets paramètres à modifier (le statut de la coopé et l'heure d'ouverture
                $statut = Parametre::trouver('statut');
                $heure_fermeture = Parametre::trouver('heure_fermeture');

                //On met à jour les valeurs de ces paramètres (fonction maj_param à revoir)  
                $statut->sauver(0);
                $valeur = date('Y-m-d H:i:s');
                $heure_fermeture->sauver($valeur);      

                //Récupération des profits faits dans la soirée  
                $heure_ouverture = strtotime(Parametre::valeur('heure_ouverture'));  
                $heure_fermeture = strtotime($valeur);
                $historiques = Historique_conso::instances()->filtrer('UNIX_TIMESTAMP(date) >= '.$heure_ouverture.' and UNIX_TIMESTAMP(date) <= '.$heure_fermeture)->executer();
         
                //Calcul des profits 
                $profits = 0;
                foreach($historiques as $historique) {
		   if($historique->consommation == '') {
		       $this->addMessage('Atttention ! une consommation n\'existe plus, elle n\'a pas ete prise en compte');
		   }
		   else {
                       $recette = Recette::instances()->get('nom = \''.$historique->consommation.'\'');
                   }  
                   $profits += $recette->prix;
                }

                //Création dans Historique_caisse
                if($profits > 0) {
                   $historique = new Historique_caisse ();
                   $historique->montant = $profits;
                   $historique->raison = 'soirée';
                   $historique->sauver();
                }
 
		$this->addMessage('Coopé fermée');
                $this->setCible('Fermeture'); //TODO Fermeture doit être de type VueCentrale (pour le message) 
                    
		return true;
	}
}
