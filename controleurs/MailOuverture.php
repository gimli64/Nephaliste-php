<?php

class ControlMailOuverture extends Controleur {

   protected function validite() {
   
      $n1 = $this->getParam('subject');
      $n2 = $this->getParam('body');

      $resultat = !empty($n1) && !empty($n2);
      return $resultat;
   }

   protected function action() {

      $Resultat = null; 
      $sujet = $this->getParam('subject');
      $corps = $this->getParam('body');
         
      $mail = new PHPmailer();
      $mail->Subject = $sujet;
      $mail->Body = $corps; 
   
      $mail->AddAddress('tripromo@rez-gif.supelec.fr');
     
      if(!$mail->Send()) {
            $Resultat = $mail->ErrorInfo;
      }
         
      $mail->SmtpClose();
      unset($mail); 
     
      if($Resultat == null) {
         $this->addMessage('Mail bien envoyés');
      }

      return true;
   }
}  
