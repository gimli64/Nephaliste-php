<?php
/**
 * Change le statut de la Coopé : fermé -> ouvert
 */
class ControlOuverture extends Controleur {

	protected $regenerer = 'Footer';

	protected function action() {

                //On récupère les objets paramètres à modifier (le statut de la coopé et l'heure d'ouverture
                $statut = Parametre::trouver('statut');
                $heure_ouverture = Parametre::trouver('heure_ouverture');

                //On met à jour les valeurs de ces paramètres (fonction maj_param à revoir)  
                $statut->sauver(1);
                $heure_ouverture->sauver(date('Y-m-d H:i:s'));         
 
		$this->addMessage('Coopé ouverte');
                $this->setCible('Mail_Ouverture');    
		return true;
	}
}
