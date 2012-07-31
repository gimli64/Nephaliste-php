<?php
//Utilisé pour le frontend, pour envoyer un mail avec les infos de la personne
if (isset ($_GET['email']) && preg_match('#^[a-z0-9._ +-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $_GET['email'])) {
	require_once('modeles/launcher.php');
        $mail = new PHPMailer();
	try {
		$compte = Compte::instances()->get('email=\'' . $_GET['email'] . '\'');
                $mail->AddAddress($compte->email,$compte->nom); 
		$consos = $compte->Historique_conso__tas()->limiter(0,15)->trier('-date')->executer();

		//On compose le mail
		
	        $mail->Subject = 'Compte =?UTF-8?B?Q29vcMOp?=';
	        $mail->Body = 'Bonjour ' . $compte->nom . ',
Conformément à votre demande, voici l\'état de votre compte : votre solde est de ' . $compte->solde . ' euros.

Voici, par ailleurs, vos dernières consommations :
';
		foreach ($consos as $conso) {
			$mail->Body .= '– le ' . $conso->date . ' : ' . $conso->consommation . ' (' . $conso->consommation->prix . ' €)' . PHP_EOL;
		}

                $mail->Body .= '
La Coopé, sans frénésie, mais avec classe.';


		//Et on l'envoie
                if(!$mail->Send())
                    $body =  $mail->ErrorInfo;
                else 
                    $body =  'Mail envoyé avec succès';
                $mail->SmtpClose();
                unset($mail); 

	} catch (AucunResultatException $e) {
		$body = '<p>Cette adresse email est absente de notre base de données.</p>';
	} catch (TropDeResultatsException $e) {
		$body = '<p>Il semblerait que plusieurs comptes utilisent cette adresse email... Ce qui est plutôt étrange. Merci de contacter un Coopéman.';
	}


} else {
	$body = '<p>Email absent ou incorrect.</p>';
}


$top = '<!DOCTYPE html> 
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> 

	<head> 
	<title>Le Néphaliste</title> 
	<link rel="stylesheet" type="text/css" href="/res/style.css" /> 
	</head> 
	<body id="mail">
	<div id="resultat">
	';

$bottom = '
	<a href="index.html">Retour</a></p>
	</div>
	</body>
	</html>';

echo $top, $body, $bottom;
?>
