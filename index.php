<?php 
//$debut = microtime(true);

/**
 * @version: 1.0B
 * @author Bruno Cauet <bruno.cauet@supelec.fr>
 * @see README
 */


//Initialisation des classes et fonctions
require 'controleurs/launcher.php';
require 'modeles/launcher.php';
require 'vues/launcher.php';

ob_start ();
$message = null;
$options = null;
//Une vue est-elle demandée ? Sinon, on renvoie la racine
$cible = (isset ( $_GET ['cible'] ) && ! empty ( $_GET ['cible'] )) ? str_replace ( '-', '_', $_GET ['cible'] ) : RACINE;

if (false !== strpos($cible, 'Control_')) {	//On demande "Control_..." : on force le passage par un controleur, malgré le fait qu'on fasse du get
	$_POST['tache'] = substr($cible, 8);
	$cible = RACINE;
}

//Le controleur agit
if (isset ( $_POST ['tache'] ) && ! empty ( $_POST ['tache'] )) {

	require 'controleurs/' . $_POST ['tache'] . '.php';
	$nom = 'Control' . $_POST ['tache'];

	unset ( $_POST ['tache'] );


	//On appelle le controleur avec les options du formulaire
	$controleur = new $nom ( $_POST );
	$message = $controleur->getMessage ();

	//Le controleur veut-il rediriger l'action ?
	$cibleControleur = $controleur->getCible ();
	if ($cibleControleur !== false) {
		$cible = $cibleControleur;
		$options = $controleur->getOptions ();
	}
}


//C'est au tour de la vue

//On affiche le haut
$foo = new Header();
$foo->display();

//Puis le corps
if (!file_exists ( 'vues/' . $cible . '.php' )) {  
	$cible = RACINE;
	$message = array('La page demandée n\'existe pas' => null);
}
require_safe($cible);
$foo = new $cible ( $message, $options );
$foo->display ();

//Et le bas sauf si on ferme la coopé (impossibilité de revenir en arrière)
if ($cible != 'Fermeture'&& $cible != 'CoopeFermee')
{
    $foo = new Footer();
    $foo->display();
}
ob_end_flush ();

//Pour logger les temps d'exécution
//$fin = microtime(true);
//error_log(round(1000*($fin-$debut),0) . ' ms - ' . (isset($nom) ? 'controleur ' . $nom .', ' : '') . 'vue ' . $cible);
?>
