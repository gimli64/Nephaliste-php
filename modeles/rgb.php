<?php
/**
 * A chaque solde fait correspondre une couleur
 * @return String "rgb($r,$v,$b)" Codage RGB correspondant au solde
 * @param int $solde
 * @version 2
 */
function rgb($solde) {
	
	//Pour ne les calculer qu'une seule fois, dans le cas d'une page qui utilise plusieurs fois cette fonction
	static $extremes = false;

	if (!$extremes) {
		$q = new AdvancedQuery('Compte');
		$resultat = $q->max('solde', 'max')->min('solde', 'min')->executer();
		$extremes = $resultat[0];
	}

	$t = ($solde-$extremes->min)/($extremes->max-$extremes->min);	//Compris entre 0 et 1, place sur l'échelle de riche-pauvre.
	
	if($t < 1/2) {
		$r = 255;
		$v = round(255*2*$t);
	} else {
		$r = round(255*2*(1-$t));
		$v = 255;
	}

	return 'rgb(' . $r . ',0,' . $v . ')';
}
?>

