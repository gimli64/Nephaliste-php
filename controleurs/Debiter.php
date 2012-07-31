<?php
class ControlDebiter extends Controleur {
        protected $regenerer = array ('Etat_Stock','Modifier_Recettes','Historique');   

	protected function validite() {

		$n1 = $this->getParam ( 'nom' );
		$n2 = $this->getParam ( 'conso' ); //Conso = liste commençant à 0 avec, pour chaque élément, l'id s'il est coché. Et pas ce qui est marqué après. //Conso = liste de 0 ou 1 indexés par le numéro de la recette. $_GET['nb{id}'] contient le nombre de cette recette qu'on débite 
                $n3 = $this->getParam ('brochaux');//Gros bouton de debit rapide

		$resultat = !empty ($n1) && (!empty ($n2) || !empty($n3));
		return $resultat;
	}

	protected function action() {

		$toutVaBien = true;
		$compte = Compte::trouver($this->getParam ('nom'));
                $caisse = Parametre::trouver('caisse');
                $valeur_caisse = floatval($caisse->valeur); 

		//On itère sur chaque recette commandée
             
                if ($compte->ouvert == 0) {
                    $toutVaBien = false;  
                    $this->addMessage ('compte fermé, impossible de débiter');
                } else { 
		    foreach ($this->getParam('conso') as $idConso) {
		    //Et on répète le débit autant de fois qu'on a commandé le même objet
                        $recette = Recette::get($idConso);  

	                for ($i=0; $i < $this->getParam ('nb' . $idConso); $i++) {
				try {
					$nom = $compte->debiter ($recette);
                                        $valeur_caisse += $recette->prix;
                                        $recette->stock -= 1;
                                        $recette->sauver();       
					$this->addMessage (array($nom => 'ok'));
				    } catch (SoldeInsuffisantException $e) {
					    $toutVaBien = false;
					    $option = 'pop-up';
					    $this->setCible('Tasks', $option);
					    $this->addMessage ($e->getMessage ());
					    break 2;
					    //On sort du foreach. On a quand même le début de la commande !
				    }
	                }
                    }
                    
                    $brochaux = $this->getParam('brochaux'); 
                    if (!empty($brochaux)) {  
                        $recette = Recette::instances()->get('nom=\''.$brochaux.'\'');
       
		      try {

		        $nom = $compte->debiter ($recette);
                        $valeur_caisse += $recette->prix;
                        $recette->stock -= 1;
                        $recette->sauver();       
		        $this->addMessage (array($nom => 'ok'));

                       } catch (SoldeInsuffisantException $e) {
		        $toutVaBien = false;
		        $option = 'pop-up';
		        $this->setCible('Tasks', $option);
	                $this->addMessage ($e->getMessage ());
                     }
                   }	        
                }
             
                $caisse->sauver($valeur_caisse);
		$compte->sauver();

		$this->addMessage (array('Solde : ' . $compte->solde . ' €' => 'ok'));

		return $toutVaBien;
	}
}
