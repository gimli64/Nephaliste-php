<?php
class Comptes_Liste extends VueCentrale {

	protected static $cache = array('
<div id="tache-1">
   <a href="', '"><img src="res/images/fermer.png" alt="fermer" class="fermer" /></a>
   <form method="post" action="Infos">
      <input type="hidden" name="tache" value="ModifierCompte" />
      <input type="hidden" name="id" value="" />
      <input type="hidden" name="ouvert" value="1" />

      <table class="modifiable style" id="compte">
         <thead>
            <tr>
	       <th>Modifier</th>
	       <th>Nom</th>
	       <th class="{sorter: \'digit\'}">Solde</th>
	       <th>Caution</th>
	       <th>Promo</th>
	       <th>Coopeman</th>
	    </tr>
         </thead>

         <tbody>',
'        </tbody>
      </table>', '
   </form>

   <form method="post" action="Comptes-Liste">
      <input type="hidden" name="tache" value="ComptesListe" />
      <h2>Options de filtrage :<br/></h2>
         <p>
	    <label for="annees">Années affichées : </label>
	    <select name="annees[]" id="annees" multiple="multiple">', '
	    <option value="0">exté</option>
	    </select>
	</p>
		
	<p>
	   <input type="checkbox" name="ouverts" value="1" id="ouverts" checked="checked" /> <label for="ouverts">Afficher les comptes ouverts</label><br/>
	   <input type="checkbox" name="fermes" value="1" id="fermes" /> <label for="fermes">Afficher les comptes fermés</label>
	</p>
		
	<p>
	   <input type="checkbox" name="positifs" value="1" id="positifs" checked="checked" /> <label for="positifs">Afficher les comptes en positif</label><br/>
	   <input type="checkbox" name="negatifs" value="1" id="negatifs" checked="checked"/> <label for="negatifs">Afficher les comptes en négatif</label>
	</p>
		
	<p>
	   <input type="checkbox" name="noncoopeman" value="1" id="coopeman" checked="checked" /> <label for="coopeman">Afficher les non-Coopémen</label><br/>
	   <input type="checkbox" name="coopeman" value="1" id="noncoopeman" checked="checked" /> <label for="noncoopeman">Afficher les Coopémen</label>
	</p>
		
	<p>
	   <input type="checkbox" name="caution" value="1" id="caution" checked="checked" /> <label for="caution">Afficher les comptes avec caution</label><br/>
	   <input type="checkbox" name="noncaution" value="1" id="noncaution" checked="checked" /> <label for="noncaution">Afficher les comptes sans caution</label>
	</p>
		
	<input type="submit" value="Obtenir la liste" />
   </form>
</div>');


	public function code () {

		$buffer = $this->wrapMessage ();

		$buffer .= self::$cache[0]. RACINE. self::$cache[1];

		$nombre = 0;
		$solde = 0;

		$comptes = $this->options->executer();

		require 'modeles/rgb.php';
		foreach ( $comptes as $compte ) {
			$nombre++;
			$solde += $compte->solde;
			$buffer .= '
		<tr title="' . $compte->id . '">
			<td></td>
			<td>' . (empty ( $compte->email ) ? $compte->nom : ('<a href="mailto:' . $compte->email . '">' . $compte->nom . '</a>')) . '</td>
			<td style="background-color: white; color: ' . rgb($compte->solde) . ';">' . $compte->solde . '</td>
			<td>' . ($compte->caution == null ? 'Non' : 'Oui') . '</td>
			<td>' . ($compte->promo == 0 ? '<i>exté</i>' : $compte->promo) . '</td>
			<td class="' . ($compte->coopeman ? 'coopeman">1' : 'noncoopeman">') . '</td>
		</tr>';
		}

		$buffer .= self::$cache[2];
		
		$buffer .= '<p>' . $nombre . ' compte(s) avec un solde cumulé de ' . $solde . ' €.</p>';
		
		$buffer .= self::$cache[3];

		//Afficher les années
		$debut = Parametre::valeur('premierePromo');
		$fin = Parametre::valeur('dernierePromo');
		for ($i = $debut; $i <= $fin; $i++) {
			$buffer .= '
				<option value="' . $i . '">' . $i . '</option>';
		}

		$buffer .= self::$cache[4];

		return $buffer;
	}
}
?>
