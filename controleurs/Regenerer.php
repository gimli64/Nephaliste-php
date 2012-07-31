<?php
/**
 * Vide le cache
 * Parcourt le dossier vues/, et pour chaque fichier (sauf le launcher), appelle la méthode regenerer()
 *
 * @see vues/Vue.php
 *
 */
class ControlRegenerer extends Controleur {
	protected function action() {
		$repertoire = 'vues/cache/';
		$handle = opendir($repertoire);
		$i = 0;
		
		while ($fichier = readdir($handle)) {
			if (is_file ($repertoire . $fichier)) {
				unlink($repertoire . $fichier);
				$i++;
			}
		}
		closedir($handle);

		$this->addMessage(array($i . ' fichiers de cache purgé(s).' => 'ok'));
		
		$repertoire = 'vues/';
		$handle = opendir($repertoire);
		$i = 0;

		while ($fichier = readdir($handle)) {
			if ($fichier !== 'launcher.php' && strrchr($fichier, '.') == '.php' && strpos($fichier, 'Vue') === false) {
				require_safe(strstr($fichier, '.php', true));
				$classe = substr($fichier, 0, -4);
				//On regarde si la classe est cacheable
				if (get_parent_class($classe) == 'VueCacheable') {
					$objet = new $classe ();
					$objet->regenerer();
					$i++;
				}
			}
		}

		closedir($handle);

		$this->addMessage(array($i . ' fichiers de cache recréé(s).'=>'ok'));
                $this->setCible(RACINE);
	}
}
