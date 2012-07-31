<?php
class Infos extends VueCentrale {

	protected static $cache = array('
	<div id="tache-1" style="width: 75%; margin-right: 200px;">
		<a href="','"><img src="res/images/fermer.png" alt="fermer" class="fermer" /></a>
	<p id="infos">
	', 

	'
	</p>


	<table id="h_depot" class="style">
		<caption>Derniers dépôts</caption>

		<thead>
			<tr>
				<th>Date</th>
				<th>Montant</th>
				<th>Forme</th>
				<th>Etat du solde</th>
			</tr>
		</thead>

		<tbody>',
		
'</tbody>
	</table>

	<form method="post" action="', '">
	<input type="hidden" name="tache" value="AnnulerOperation" />
	<table id="h_conso" class="style">
		<caption>Dernières consommations</caption>

		<thead>
			<tr>
				<th>Date</th>
				<th>Conso</th>
				<th>Prix</th>
				<th>Etat du solde</th>
				<th>Supprimer</th>
			</tr>
		</thead>

		<tbody>
		',
'</tbody>
	</table>
	</form>
<div id="stats">', '
</div>
	
</div>');


	public function code () {

		$compte = $this->options;

		//Infos
		//Il est bien dommage de passer par des AdvancedQuery, mais c'est ça ou tout faire en PHP...

		//Dépenses

		/* C'est beaucoup de requête pour pas grand chose
		* Personne ne regarde ces infos
		*  on enlève pour l'instant

		$q = new AdvancedQuery('Historique');
		$res = $q->jointure('Recette', 'id', 'consommation')->jointure('Compte', 'id', 'compte')->addSomme('prix', 'somme')->filtrer('compte=' . $compte->id);

		$semaine = clone $res;
		$rep = $semaine->filtrer('date > (NOW() - INTERVAL 1 WEEK)')->executer();
		$stats['depense']['semaine'] = (int) $rep[0]->somme;
		$mois = clone $res;
		$rep = $mois->filtrer('date > (NOW() - INTERVAL 1 MONTH)')->executer();
		$stats['depense']['mois'] = (int) $rep[0]->somme;
		$rep = $res->executer();
		$stats['depense']['toujours'] = (int) $rep[0]->somme;


		//Recettes préférées
		/* Ne sert à rien pour l'instant 
		$q = new AdvancedQuery('Historique');
		$res = $q->jointure('Recette', 'id', 'consommation')->addRow('nom', 'Recette')->compter('nombre')->grouper('consommation')->filtrer('compte=' . $compte->id)->trier('-nombre')->limiter(0,1);

		$semaine = clone $res;
		$rep = $semaine->filtrer('date > (NOW() - INTERVAL 1 WEEK)')->executer();
		$stats['recette']['semaine'] = isset($rep[0]) ? $rep[0]->nom . ' (' . $rep[0]->nombre . ' fois)' : 'rien';
		$mois = clone $res;
		$rep = $mois->filtrer('date > (NOW() - INTERVAL 1 MONTH)')->executer();
		$stats['recette']['mois'] = isset($rep[0]) ? $rep[0]->nom . ' (' . $rep[0]->nombre . ' fois)' : 'rien';
		$rep = $res->executer();
		$stats['recette']['toujours'] = isset($rep[0]) ? $rep[0]->nom . ' (' . $rep[0]->nombre . ' fois)' : 'rien';
		*/


		$buffer = $this->wrapMessage ();
		$buffer .= self::$cache[0] . RACINE . self::$cache[1];
		
		//Infos générales
		require 'modeles/rgb.php';
		$buffer .= 'nom : ' . ($compte->email ? '<a href="mailto:' . $compte->email . '">' . $compte->nom . '</a>' : $compte->nom) . '<br />';
		$buffer .= 'solde : <span style="color: ' . rgb($compte->solde) . ';">' . $compte->solde . '€</span><br />';
		if ($compte->coopeman == 1) {
			$buffer .= 'coopéman !<br />';
		}
			
		$buffer .= 'promo : ' . ($compte->promo == 0 ? 'extérieur (ou Leguil et cie.)' : $compte->promo);
		$buffer .= '<br />caution : ' . ($compte->caution ? $compte->caution : 'non');
			

		//Derniers dépôts
		$buffer .= self::$cache[2];
		$depots = $compte->Depot__tas()->limiter(0,30)->trier('-date')->executer();
		$formes = Array ('billet', 'cheque', 'autre');
			
		foreach ( $depots as $depot ) {
			$buffer .= '
	<tr>
		<td>' . $depot->date . '</td>
		<td>' . $depot->montant . '</td>
		<td><img src="res/images/' . $formes [$depot->forme] . '.png" /></td>
		<td>' . $depot->etat_solde . '</td>
	</tr>';
		}


		//Dernières consos
		$buffer .= self::$cache[3] . RACINE . self::$cache[4];
		$consos = $compte->Historique_conso__tas()->limiter(0,30)->trier('-date')->executer();

		foreach ( $consos as $conso ) {
			$buffer .= '
	<tr>
		<td>' . $conso->date . '</td>
		<td>' . $conso->consommation->nom . '</td>
		<td>' . $conso->consommation->prix . '</td>
		<td>' . $conso->etat_solde . '</td>
		<td><input type="submit" name="' . $conso->id . '" value="boum !" /></td>
	</tr>';
		}
			
		$buffer .= self::$cache[5];


		//Statistiques
		/* On enlève
		$buffer .= '<p><strong>Dépensier : </strong>semaine : ' . $stats['depense']['semaine'] . ' € ; mois : ' . $stats['depense']['mois'] . ' € ; toujours : ' . $stats['depense']['toujours'] . ' €</p>';

		$buffer .= '<p><strong>Recette préférée : </strong>semaine : ' . $stats['recette']['semaine'] . ' ; mois : ' . $stats['recette']['mois'] . ' ; toujours : ' . $stats['recette']['toujours'] . '</p>';
		*/

		$buffer .= self::$cache[6];


		return $buffer;

	}
}
?>
