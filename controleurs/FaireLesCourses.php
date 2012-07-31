<?php

class ControlFaireLesCourses extends Controleur {

   protected $regenerer = array('Historique', 'Modifier_Recettes', 'Frontend', 'Etat_Stock', 'Courses', 'HistoriqueCaisse');
 
   protected function validite() {
   
      $n1 = $this->getParam('montant');
      $resultat = ! empty ($n1);
      
      return $resultat;
   }

   protected function action() {
     
      $tentatives = 0; 
      $params = $this->getParams();

      foreach($params as $id => $param) {
 
         if($id != 'montant' && !isset($recettes_passees[$id])) {
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


            if(isset ($params[$id])) {
               $recette->stock = $params[$id];
               $recette->sauver();
               $tentatives++;  
            } else {
               $this->addMessage($recette->nom .' : tous les paramètres ne sont pas fournis');
            }
         }   
      } 

      $montant = $this->getParam('montant');
      $caisse = Parametre::trouver('caisse');
      $valeur = floatval($caisse->valeur);
      $valeur -= $montant;
      $caisse->sauver($valeur);
      
      $historique = new Historique_caisse ();
      $historique->montant = -$montant;
      $historique->raison = 'courses';
      $historique->sauver();
   
      $this->addMessage(array('Modification de '.$tentatives.' recettes ' => 'ok')); 
      $this->addMessage(array('Courses effectuées, nouvelle valeur de la caisse : '.$valeur => 'ok'));
      return true;
   }
}
