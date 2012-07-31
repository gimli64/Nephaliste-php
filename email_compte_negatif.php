<?php
   require_once('modeles/launcher.php');
   $mail = new PHPMailer();
   $mail->AddAddress('lucas.deschamps@supelec.fr','lucas deschamps');
   $mail->Subject = 'test';
   $mail->Body = 'tu es vraiment genial lucas';
   if($mail->Send())
      echo 'mail bien envoye';
    
   $mail->SmtpClose();
   unset($mail);
?>
