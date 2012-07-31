<?php
class Administration extends VueCacheable {
	
	protected static $cache = array('
<div id="tache-1">
	<p>Bienvenue sur la page d\'administration. Vous pouvez ici modifier quelques paramètres concernant la gestion de la Coopé.</p>
	<form method="post" action="', '">
		<input type="hidden" name="tache" value="Administration" />
		
		<p>
			<label for="annee">Promotion en cours à Supélec</label>
			<input type="text" id="annee" name="annee" value="', '" />
		</p>
		
		<input type="submit" value="Valider les changements" />
	</form>
	
	<form method="post" action="', '">
		<input type="hidden" name="tache" value="Deposer" />
		
		<p>
			<input type="checkbox" id="cheques" name="cheques" value="1" /> <label for="cheques">Déposer les chèques<br /></label>
			<input type="checkbox" id="liquide" name="liquide" value="1" /> <label for="liquide">Déposer le liquide<br /></label>
		</p>
		
		<input type="submit" value="Mettre les sous en sécurité" />
	</form>

	<p><a href="Control-Regenerer">Régénérer le cache HTML des pages</a> (si certaines modifications dans les propositions de la Coopé n\'ont pas été prises en compte, ou en cas de modification du code source des vues. Ne devrait normalement pas être utilisé, quoi)</p>

</div>
');
	public function fraiche () {
		return self::$cache[0] . RACINE . self::$cache[1] . Parametre::valeur('dernierePromo') . self::$cache[2] . RACINE . self::$cache[3];
	}
}
?>
