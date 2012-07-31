<?php
//Recoit une chaine : $_GET['q'] et doit rendre les comptes matchant ce nom
//Utilisé pour l'auto-complétion de nom

if (! isset ( $_GET ['q'] ) || empty ( $_GET ['q'] )) {
	die ();
}

require_once('modeles/launcher.php');

$comptes = Compte::instances()->filtrer('nom LIKE \'%' . $_GET['q'] . '%\'')->executer();

foreach ( $comptes as $compte ) {
//on récupère, le nom, le solde et le prenom.nom (pour la photo)
        $addr = 'http://www.rez-gif.supelec.fr/trombinews/trombicoope.php?mail='.$compte->email;
	echo $compte->nom . ';' . $compte->solde . ',' . $addr  . '*' . PHP_EOL;
}
?>
