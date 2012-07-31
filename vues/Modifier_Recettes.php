<?php
class Modifier_Recettes extends VueCacheable {
	protected static $cache = array('<div id="tache-1">
<form method="post" action="', '">

	<input type="hidden" name="tache" value="ModifierRecettes" />

	<table class="style">


		<tr>
			<th>Nom</th>
			<th>Prix</th>
			<th colspan="2">Disponible</th>
                        <th> Stock </th>
			<th>Supprimer</th>
                        <th colspan="2">Bouton</th>
			<th colspan="2">Soiree</th>   
		</tr>

		<tr>
			<th></th>
			<th></th>
			<th>Oui</th>
			<th>Non</th>
                        <th></th>  
                        <th></th>  
                        <th>Oui</th>  
                        <th>Non</th>
			<th>Oui</th>
			<th>Non</th>  
		</tr>
		','
	</table>

	<p class="centre">
		<input type="submit" class="milieu" value="Faire la cuisine !" />
	</p>

</form>
</div>
');

	//A régénérer lors de la modif d'une recette
	public function fraiche () {

		$nouveauCache = self::$cache[0] . RACINE . self::$cache[1];

		$recettes = Recette::instances()->executer();
		foreach ($recettes as $recette) {

			$nouveauCache .= '
		<tr>
			<td><input type="text" name="' . $recette->id . '_nom" value="' . $recette->nom . '"/></td>
			<td><input style="width: 60px;" type="text" name="' . $recette->id . '_prix" value="' . $recette->prix . '"/></td>
			<td><input type="radio" name="' . $recette->id . '_disponible" value="1" id="d_' . $recette->id . '_1" ' . ($recette->disponible ? 'checked="checked" ' : '') . '/></td>
			<td><input type="radio" name="' . $recette->id . '_disponible" value="0" id="d_' . $recette->id . '_0" ' . (!$recette->disponible ? 'checked="checked" ' : '') . '/></td>
                        <td><input style="width: 60px;" type="text" name="' . $recette->id . '_stock" value="' . $recette->stock . '"/></td>
			<td><input type="checkbox" name="' . $recette->id . '_suppr" value="1" /></td>
			<td><input type="radio" name="' . $recette->id . '_bouton" value="1" id="b_' . $recette->id . '_1" ' . ($recette->bouton ? 'checked="checked" ' : '') . '/></td>
			<td><input type="radio" name="' . $recette->id . '_bouton" value="0" id="b_' . $recette->id . '_0" ' . (!$recette->bouton ? 'checked="checked" ' : '') . '/></td>
			<td><input type="radio" name="' . $recette->id . '_soiree" value="1" id="s_' . $recette->id . '_1" ' . ($recette->soiree ? 'checked="checked" ' : '') . '/></td>	
			<td><input type="radio" name="' . $recette->id . '_soiree" value="0" id="s_' . $recette->id . '_0" ' . (!$recette->soiree ? 'checked="checked" ' : '') . '/></td>

		</tr>';

		}

		$nouveauCache .= self::$cache[2];

		return $nouveauCache;
	}
}
?>
