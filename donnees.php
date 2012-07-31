<?php
//$_GET['c'] contient l'id du compte, et demande les infos correspondant
//Utilisé pour charger un compte pour le modifier
if (! isset ( $_GET ['c'] ) || empty ( $_GET ['c'] )) {
	die;
}

require_once('modeles/launcher.php');

header('Content-type: application/json');
echo json_encode(Compte::instances()->get('nom LIKE \'%' . $_GET['c'] . '%\''));
?>
