       <div id="module" style="background-color:black;">
          <?php
             require_once 'modeles/launcher.php';
             $ToutVaBien = true;
             $statut = 'ouverte';
             $opacity = 1;

             $valeur = Parametre::valeur('statut');
             if (!$valeur)
             {
                $statut = 'ferm&eacute;e';
                $opacity = 0.5;
             }
   
	              

             $body = '
	     <img style="opacity:'.$opacity.'; position:relative; width:100%; height:200px;" src="http://coope.rez-gif.supelec.fr/coope2.png" /> 
                      <strong style="font-size:120%;display:block;color:white;text-align:center;">La coope est '.$statut.'</strong>';
     
             //recoit le nom de l'utilisateur qui se logue sur le 3w sous la forme PrenomNOM (a changer cote 3w)
             if (isset($_GET['name_user']))
                $name = $_GET['name_user'];
            
             //met la lettre du prenom en minuscule 
             $name = lcfirst($name);  

             $pattern = '/[A-Z]/';
             $matches = array(); 
             preg_match($pattern,$name,$matches); //on récupère la première lettre du nom;
             /*
             * TODO voir comment faire pour les noms composes
             */
             
             $length = strpos($name,$matches[0]);
             $prenom = substr($name,'0',$length); //on récupère le prénom
             $nom = strtolower(substr($name,$length));  //on récupère le nom
 
             //on recupere le compte correspondant (oblige de passer par l'email a cause du pseudo...
             try 
             {
                $compte = Compte::instances()->get('email=\''.$prenom.'.'.$nom.'@supelec.fr\'');            
             } catch (TropDeResultatsException $e) {
                $ToutVaBien = false;
             } catch (AucunResultatException $e) {
                $ToutVaBien = false;
             }
          
             if($ToutVaBien) 
             {
                $body .= '<strong style="font-size:120%;display:block;color:white;text-align:center;">Bienvenue '.$compte->nom.', votre solde actuellement : '.$compte->solde.' euros</strong>';
             }        

             echo $body;   
          ?>
       </div> 

