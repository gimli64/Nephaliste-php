<?php
/*
 * Le statut correspond à ce qui est marqué sur le site du Rézo.
 * Cependant, cela n'existe plus avec la nouvelle version du www.
 * Il faut voir ave le Rézo ce qu'ils peuvent fournir pour la Coopé comme API.
 */
class Footer extends VueCacheable {

	protected static $cache = array('
		<a id="retour" href="Control-Normal">Écran principal</a>       
		<a id="statut" href="Control-', '">', ' la Coope</a>
		</body>
	</html>');

	public function fraiche  () {

		$valeur = Parametre::valeur('statut');
		$nouveauCache = self::$cache[0] . ($valeur ? 'Fermeture' : 'Ouverture') . self::$cache[1] . ($valeur ? 'fermer' : 'ouvrir') . self::$cache[2];
            		
		return $nouveauCache;
	}
}
?>
