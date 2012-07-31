<?php
/**
 * Active l'interface minimaliste - pour smartphones
 */

class ControlMinimaliste extends Controleur {

	protected function action () {
		setCookie('format','mini',time()+60*60*24*30);
		header('Location: ' . RACINE);
		exit;
	}
}
