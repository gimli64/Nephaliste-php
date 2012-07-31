<?php
class Modifier_Compte extends VueCacheable {

	protected static $cache = array('
	<div id="tache-1">
	<form action="', '" method="post">

		<p>
			<label for="nom_modification">Compte : </label>
			<input type="text" id="main_nom" class="compte" />
			<input type="button" id="charger_compte" value="Charger le compte" />
		</p>
		
		<input type="hidden" name="tache" value="ModifierCompte" />
		<input type="hidden" name="id" id="rc_id" value="" />
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
			<label for="rc_depot">Solde : </label><input type="text" name="depot" id="rc_depot" />
		</p>
		<p>
			<label style="width: 150px;" for="rc_caution_0">Chèque de caution :</label>
			<input type="radio" value="1" id="rc_caution_1" name="caution" /><label for="rc_caution_1">Oui (25 €)</label>
			<input type="radio" value="0" id="rc_caution_0" name="caution" checked="checked" /><label for="rc_caution_0">Non</label>
		</p>
		<p>
			<label for="rc_coopeman_0" style="height:20px;">Coopéman :</label>
			<input type="radio" value="1" id="rc_coopeman_1" name="coopeman" /><label for="rc_coopeman_1">Oui</label>
			<input type="radio" value="0" id="rc_coopeman_0" name="coopeman" checked="checked" /><label for="rc_coopeman_0">Non</label>
		</p>	
                <p>
                       	<label for="rc_ouvert_0">Ouvert :</label>
                        <input type="radio" value="1" id="rc_ouvert_1" name="ouvert" checked="checked"/><label for="rc_ouvert_1">Oui</label>
                        <input type="radio" value="0" id="rc_ouvert_0" name="ouvert" /><label for="rc_ouvert_0">Non</label>
                </p>
		<input type="submit" value="Modifier" />
	</form>
	</div>');
			
	public function fraiche () {

		$promo = Parametre::valeur('dernierePromo');
		$nombre = Parametre::valeur('anneesOuvertes');
			
		$nouveau_cache = self::$cache[0] . RACINE . self::$cache[1];
		for ($i = $promo; $i >= $promo-$nombre+1; $i--) {
			$nouveau_cache .= '
<input type="radio" name="promo" value="' . $i . '" id="rc_p' . $i . '" /> <label for="rc_p' . $i . '">' . $i . '</label>';
		}
		$nouveau_cache .= self::$cache[2];
		
		return $nouveau_cache;
	}
}
?>
