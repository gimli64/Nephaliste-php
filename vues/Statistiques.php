<?php
/*
 * Statistiques de consommation.
 * Problème : ne gère pas spécialement le cas "rien consommé depuis une semaine/un mois" (cf. vacances)
 * Faire une exception NotEnoughResults lancée dans Modele::executer() si la clause limit n'est pas satisfaite
 */
class Statistiques extends VueCacheable {

	public function fraiche () {

		//Personnes les plus dépensières
		$q = new AdvancedQuery('Historique_Conso');
		$res = $q->jointure('Recette', 'id', 'consommation')->jointure('Compte', 'id', 'compte')->addRow('nom', 'Compte')->addSomme('prix', 'somme')->grouper('compte')->trier('-somme')->limiter(0,1);
		$semaine = clone $res;
		$rep = $semaine->filtrer('date > (NOW() - INTERVAL 1 WEEK)')->executer();
		$stats['riche']['semaine'] = $rep[0];
		$mois = clone $res;
		$rep = $mois->filtrer('date > (NOW() - INTERVAL 1 MONTH)')->executer();
		$stats['riche']['mois'] = $rep[0];
		$rep = $res->executer();
		$stats['riche']['toujours'] = $rep[0];


		//Boissons les plus bues
		$q = new AdvancedQuery('Historique_Conso');
		$res = $q->jointure('Recette', 'id', 'consommation')->addRow('nom', 'Recette')->compter('nombre')->grouper('consommation')->trier('-nombre')->limiter(0,1);

		$semaine = clone $res;
		$rep = $semaine->filtrer('date > (NOW() - INTERVAL 1 WEEK)')->executer();
		$stats['recette']['semaine'] = $rep[0];
		$mois = clone $res;
		$rep = $mois->filtrer('date > (NOW() - INTERVAL 1 MONTH)')->executer();
		$stats['recette']['mois'] = $rep[0];
		$rep = $res->executer();
		$stats['recette']['toujours'] = $rep[0];

return '
	<p>Dépensier :</p>
	<ul>
		<li>semaine : ' .  $stats['riche']['semaine']->nom .  ' pour ' .  $stats['riche']['semaine']->somme .  ' € ;</li>
		<li>mois : ' .  $stats['riche']['mois']->nom .  ' pour ' .  $stats['riche']['mois']->somme .  ' € ;</li>
		<li>toujours : ' .  $stats['riche']['toujours']->nom .  ' pour ' .  $stats['riche']['toujours']->somme .  ' €.</li>
	</ul>
	
	<p>Recette la plus demandée :</p>
	<ul>
		<li>semaine : ' .  $stats['recette']['semaine']->nom .  ' : ' .  $stats['recette']['semaine']->nombre .  ' fois ;</li>
		<li>mois : ' .  $stats['recette']['mois']->nom .  ' : ' .  $stats['recette']['mois']->nombre .  ' fois ;</li>
		<li>toujours : ' .  $stats['recette']['toujours']->nom .  ' : ' .  $stats['recette']['toujours']->nombre .  ' fois.</li>
	</ul>
	';
	}
}
?>
