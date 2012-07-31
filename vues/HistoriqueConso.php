<?php
/*
 * Problème : déjà une classe historique_conso dans modeles/ !
 * => namespaces
 */

//class Historique_conso extends VueCentrale {
class HistoriqueConso extends VueCentrale {

	protected static $cache = array('
<div id="tache-1">
	<form action="', '" method="post">
	<input type="hidden" name="tache" value="AnnulerOperation" />
	<table class="style">
		<caption>Dernières consommations</caption>
		<thead>
			<tr>
				<th>Date</th>
				<th>Recette</th>
				<th>Compte</th>
				<th>Supprimer</th>
			</tr>
		</thead>

		<tbody>
', '
		</tbody>
	</table>
</form>
</div>');

	public function code () {

		$buffer = $this->wrapMessage ();

		$buffer .= self::$cache[0] . RACINE . self::$cache[1];

                $options = $this->options;
                if (sizeof($options) >= 2) {

                   $date = $options[0];
                   $date_previous = $options[1]; 
                   
                   if(sizeof($options) == 3)     
		      $historique = Historique_conso::instances()->filtrer('UNIX_TIMESTAMP(date) >= '.$date_previous.' and UNIX_TIMESTAMP(date) <= '.$date.' and compte = '.$options[2].'')->executer();
                   else
		      $historique = Historique_conso::instances()->filtrer('UNIX_TIMESTAMP(date) >= '.$date_previous.' and UNIX_TIMESTAMP(date) <= '.$date)->executer();
                }
                else 
		   $historique = Historique_conso::instances()->trier('-date')->limiter(0,30)->executer();

		   foreach ($historique as $operation) {
			$buffer .= '
			<tr>
				<td>' . $operation->date . '</td>
				<td>' . $operation->consommation . '</td>
				<td>' . $operation->compte . '</td>
				<td><input type="submit" value="boum !" name="' . $operation->id . '" /></td>
			</tr>';
		   }

		$buffer .= self::$cache[2];

		return $buffer;
	}
}
?>
