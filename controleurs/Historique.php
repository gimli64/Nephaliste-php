<?php
class ControlHistorique extends Controleur {
        protected $is_set_date = false;
        protected $is_set_nom = false;
 
	protected function validite() {
		
		$n1 = $this->getParam ( 'nom' );
                $n2 = $this->getParam ('jour');
                $n3 = $this->getParam ('mois');
                $n4 = $this->getParam ('annee');
		
	        $this->is_set_date = !empty($n2) && !empty($n3) && !empty($n4);
                $this->is_set_nom = !empty($n1);	
		return true;
	}
	
	protected function action() {

        //Si des dates sont indiquées, on renvoit les consommations à cette date, si un nom est précisé on filtre en plus pour ce nom
           if ($this->is_set_date) {

              $date = strtotime($this->getParam('annee').'-'.$this->getParam('mois').'-'.$this->getParam('jour').' '.'23:59:59'); 
              $date_previous = $date - 86399; //écart en seconde avec début du jour   
              
              if ($this->is_set_nom) {
                 $compte = Compte::trouver($this->getParam('nom'));
                 $this->setCible("HistoriqueConso",array($date,$date_previous,$compte->id));
                 return true;      
              }
              
              $this->setCible("HistoriqueConso",array($date,$date_previous));
              return true; 
           } 

         //Si aucune date n'est précisée, on vérifie qu'on veut regarder les informations pour une personne, sinon on renvoit les 30 dernières consommations
           else {
              if ($this->is_set_nom) {
 		 $this->setCible ('Infos', Compte::trouver($this->getParam('nom')));
                 return true;
              }
              else {
                 $this->setCible('HistoriqueConso');   
              } 
           }
           return true;
	}
}
?>
