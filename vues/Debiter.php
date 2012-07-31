<?php
/*
 * Comment trier par popularité ?
 */
class Debiter extends VueCacheable {

	protected static $cache = array('
				<input type="hidden" name="tache" value="Debiter" />
				<table>
					<caption>Consommation :</caption>

					<tbody>

',
					'
					</tbody>
				</table>

				<input type="submit" value="Débiter" />');

	public function fraiche () {

		$nouveauCache = self::$cache[0];

		$boissons = Recette::instances()->trier('nom')->filtrer('disponible=1')->executer();
		//$boissons = get('Recettes', 'prix', false, 'popularite');

		foreach ( $boissons as $boisson ) {
			$nouveauCache .= '
				<tr>
					<td><input type="checkbox" name="conso[]" value="' . $boisson->id . '" id="c' . $boisson->id . '" /></td>
					<td><label for="c' . $boisson->id . '"> ' . $boisson->nom . '</label></td>
					<td><input type="text" class="court" name="nb' . $boisson->id . '" value="1" /></td>
					<td>(' . $boisson->prix . ' €)</td>
				</tr>
';
		}
		$nouveauCache .= self::$cache[1];
			
		return $nouveauCache;
	}
}
?>
