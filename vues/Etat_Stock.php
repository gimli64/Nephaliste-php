<?php
class Etat_Stock extends VueCacheable {
	protected static $cache = array('<div id="tache-1">
	<table class="style">
		<tr>
			<th>Nom</th>
                        <th>Stock</th>
		</tr>

		<tr>
			<th></th>
			<th></th>
		</tr>
		','
	</table>
</div>
');

	public function fraiche () {

		$nouveauCache = self::$cache[0];
		$recettes = Recette::instances()->executer();
		foreach ($recettes as $recette) {

			$nouveauCache .= '
		<tr>
			<td>'. $recette->nom . '</td>
                        <td>' . $recette->stock . '</td>
		</tr>';

		}

		$nouveauCache .= self::$cache[1];

		return $nouveauCache;
	}
}
?>
