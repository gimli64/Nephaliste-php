<?php

class ControlMailComptesNegatifs extends Controleur {

   protected function validite() {
   
      $n1 = $this->getParam('subject');
      $n2 = $this->getParam('body');
      $n3 = $this->getParam('amount_min');
      $n4 = $this->getParam('amount_max');

      $resultat = !empty($n1) && !empty($n2);
      return $resultat;
   }

   protected function action() {

      $Resultat = null; 
      $sujet = $this->getParam('subject');
      $corps = $this->getParam('body');
      $montant_min = $this->getParam('amount_min');
      $montant_max = $this->getParam('amount_max');

      $comptes = Compte::instances()->filtrer('solde < -' . $montant_min)->filtrer('solde > -' . $montant_max)->filtrer('ouvert = 1')->executer();   
      
      foreach ($comptes as $compte) {
         
         $mail = new PHPmailer();
         $mail->Subject = $sujet;
         $mail->Body = $corps; 
   
         $mail->AddAddress($compte->email,$compte->nom);
     
         if(!$mail->Send()) {
            $Resultat = $mail->ErrorInfo;
            $this->addMessage('Impossible d\'envoyer le mail à l\'adresse'.$compte->email);
         }
         
         $mail->SmtpClose();
         unset($mail); 
      }
     
      if($Resultat == null) {
         $this->addMessage(array('Mails bien envoyés'=>'ok'));
      }

      return true;
   }
}  
