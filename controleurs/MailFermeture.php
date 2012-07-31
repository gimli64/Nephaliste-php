<?php

class ControlMailFermeture extends Controleur {

         protected function action() {      
     
                //Attribut de succes de l'envoie des mails
                $Resultat = null;

                //Recuperation des id des comptes qui ont consommé ce soir là sans doublons a revoir peut-etre, on fait beaucoup de requete sql!!!
                $heure_ouverture = strtotime(Parametre::valeur('heure_ouverture'));
                $heure_fermeture = strtotime(Parametre::valeur('heure_fermeture'));
                $requete = Historique_conso::instances()->select('DISTINCT compte')->filtrer('UNIX_TIMESTAMP(date) >= '.$heure_ouverture.' and UNIX_TIMESTAMP(date) <= '.$heure_fermeture.'')->executer();

               
                //Envoie du mail pour chaque personne ayant consommee
                foreach($requete as $idCompte) {
                 
                   //Preparation du mail
                   $mail = new PHPMailer();
                   $mail->Subject = '=?UTF-8?B?Q29vcMOp?= : Consommations de la soiree';

                   $compte = Compte::instances()->get('id = '.$idCompte->getCompte()); 
                   $mail->AddAddress($compte->email,$compte->nom);
                   $mail->Body = 'Bonjour '.$compte->nom.', voici le recapitulatif de vos consommations hier soir en coope :

';
                  
                   //recuperation des consos de la soiree    
                   $consos = $compte->Historique_conso__tas()->trier('-date')->filtrer('UNIX_TIMESTAMP(date) >= '.$heure_ouverture.' and UNIX_TIMESTAMP(date) <= '.$heure_fermeture.'')->executer(); 

                   foreach($consos as $conso) {
                      $mail->Body .= $conso->date.'   '.$conso->consommation.' ('.$conso->consommation->prix.' euros)' . PHP_EOL;
                   }
                   
                   $mail->Body .= '
Votre solde est de '.$compte->solde.'.

La Coope, sans frenesie, mais avec classe.';
 
                   //Envoie du mail
                   if(!$mail->Send()) {
                      $Resultat = $mail->ErrorInfo;
                      $this->addMessage('Impossible d\'envoyer le mail à l\'adresse '.$compte->email);
                   }  

                   $mail->SmtpClose();
                   unset($mail);
                   
                }                           
                
                if($Resultat == null)
                   $this->addMessage('Mails bien envoyés');
 
                $this->setCible('CoopeFermee'); 
		return true;
         }
}
?>
