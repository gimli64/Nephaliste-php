<?php
class Graphe extends VueCacheable {

	private static $_cache = '<p>Vous trouverez ici de jolis graphes.
	<img src="res/images/graphes/conso1.png" alt="Nombre de consommations" /><br/>
	<img src="res/images/graphes/conso2.png" alt="Consommations en argent" /><br/>
	<img src="res/images/graphes/depots.png" alt="Montant hebdomadaire des dépôts d\'argent" /><br/>
	<img src="res/images/graphes/.png" alt="" /><br/>
	<img src="res/images/graphes/.png" alt="" /><br/>
</p>';

	public function fraiche () {

/*
		require '../jpgraph/src/jpgraph.php';
		$hauteur = 200;
		$largeur = 600;
		
		//Conso : nombre
		$q = new AdvancedQuery('Historique');
		$res = $q->jointure('Recette', 'id', 'consommation')->grouper('WEEK(date)')->addRow('WEEK(date)', false)->addSomme('prix', 'somme')->limiter(0,52)->executer();

		$graph = new Graph($largeur, $hauteur);
		$graph->SetScale('intint');
		$graph->title->Set('Nombre de consommations');
		$graph->xaxis->title->Set('semaine');
		$graph->yaxis->title->Set('nombre');
		$lineplot = new Lineplot($res);
		$graph->Add($lineplot);
		$graph->Stroke();



		print_r($res);die;
return 'Rien encore ici';
*/
	}

}
?>
