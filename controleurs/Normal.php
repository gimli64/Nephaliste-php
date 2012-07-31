<?php
/**
 * Active l'interface minimaliste - pour smartphones
 */

class ControlNormal extends Controleur {

	protected function action () {
		setCookie('format',false);
		header('Location: ' . RACINE);
		exit;
	}
}
