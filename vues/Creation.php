<?php
class Creation extends VueCacheable {

	protected static $cache = array('
				
				<input type="hidden" name="tache" value="NouveauCompte" />
				<p>
					<label for="rc_prenom">Prénom : </label><input type="text" name="prenom" id="rc_prenom" />
				</p>
				<p>
					<label for="rc_nom">Nom : </label><input type="text" name="nom" id="rc_nom" />
				</p>
				<p>
					<label for="rc_surnom">Surnom : </label><input type="text" name="surnom" id="rc_surnom" />
				</p>
				<p>
					<label for="rc_email">Email : </label><input type="text" name="email" id="rc_email" />
				</p>
				<p>Promo :
','
<input type="radio" name="promo" value="0" id="rc_p0" /> <label for="rc_p0">Extérieur</label>
				</p>
				<p>
					<label for="rc_depot">Dépôt initial : </label><input type="text" name="depot" id="rc_depot" />
				</p>
				<p>
					<input type="radio" value="0" id="rc_forme_0" name="forme" checked="checked" /><label for="rc_forme_0">Liquide</label>
					<input type="radio" value="1" id="rc_forme_1" name="forme" /><label for="rc_forme_1">Chèque</label>
				</p>
				<p>
					<label for="rc_caution_0">Chèque de caution :</label>
					<input type="radio" value="1" id="rc_caution_1" name="caution" /><label for="rc_caution_1">Oui (25 €)</label>
					<input type="radio" value="0" id="rc_caution_0" name="caution" checked="checked" /><label for="rc_caution_0">Non</label>
				</p>
				<input type="submit" value="Créer" />
				
				');
			
	public function fraiche () {

		$promo = Parametre::valeur('dernierePromo');
		$nombre = Parametre::valeur('anneesOuvertes');
			
		$nouveau_cache = self::$cache[0];
		for ($i = $promo; $i >= $promo-$nombre+1; $i--) {
			$nouveau_cache .= '
<input type="radio" name="promo" value="' . $i . '" id="rc_p' . $i . '" /> <label for="rc_p' . $i . '">' . $i . '</label>';
		}
		$nouveau_cache .= self::$cache[1];
		
		return $nouveau_cache;
	}
}
