<?php
class Crediter extends VueCacheable {

	protected static $cache = '
				<input type="hidden" name="tache" value="Crediter" />
				<p>
					<label for="c_montant">Montant : </label><input type="text" name="montant" id="c_montant" />
				</p>
				<p>
					<input type="radio" value="0" id="c_forme_0" name="forme" checked="checked" /><label for="c_forme_0">Liquide</label>
					<input type="radio" value="1" id="c_forme_1" name="forme" /><label for="c_forme_1">Chèque</label>
				</p>
				<input type="submit" value="Créditer" />';

	public function fraiche () {
		return self::$cache;
	}
}

?>
