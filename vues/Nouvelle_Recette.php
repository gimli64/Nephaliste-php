<?php
class Nouvelle_Recette extends VueCacheable {
	protected static $cache = array('<div id="tache-1">
<form method="post" action="', '">

	<input type="hidden" name="tache" value="NouvelleRecette" />

	<p>
		<label for="nom">Nom : </label><input type="text" name="nom" id="nom" />
	</p>

	<p>
		<label for="prix">Prix de vente : </label><input type="text" name="prix" id="prix" />
	</p>
	
	<p class="centre">
		<input type="submit" class="milieu" value="Faire la cuisine !" />
	</p>

</form>
</div>
');

	public function fraiche () {
		return self::$cache[0] . RACINE . self::$cache[1];
	}
}
?>
