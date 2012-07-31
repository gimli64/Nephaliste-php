<?php
//Appelle les vues de chaque module
class Tasks extends VueCentrale {

	private static $taches = array(
	Array ('Debiter', '<em>Débiter</em> un compte' ),
	Array ('Crediter', '<em>Créditer</em> un compte' ),
	Array ('Historique', '<em>Aperçu</em> des historiques (compte, stocks)' ),
	Array ('Creation', '<em>Créer</em> un nouveau compte'),
	Array ('Gestion', 'Gérer les <em>bières</em> (et autres trucs à ingurgiter)' ),
	Array ('Statistiques', 'Consulter les <em>statistiques</em>' ),
	);
	private static $nombre = 5;


	private static function champ() {
           $buffer =  '
<form name="form" action="' . RACINE . '" method="post" id="champ">
	<p>
	<label for="main_nom">Nom : </label> <input type="text" id="main_nom" name="nom" cols="50" rows="10"/><strong></strong><img id="photo" src="" alt="" style="" />
	</p>
        <input type="hidden" name="tache" value="Debiter" />'; 
        
           	$recettes = Recette::instances()->filtrer('bouton=1')->executer();
		$bouton_soiree = Recette::instances()->filtrer('soiree=1')->executer();
           	$nb_recettes = sizeof($recettes);
		$nb_bouton_soiree = sizeof($bouton_soiree);
           	$max_recettes = min(11,$nb_recettes);
		$max_bouton_soiree = min(10,$nb_bouton_soiree);

	   /*
	   * Pour éviter que "entrée" débite un demi de kro, 
	   * dégueulasse pour l'instant mais j'ai pas trouvé d'autre solution
	   */
           $buffer = $buffer.'<input style="width: 0; height: 0; opacity: 0;" type="submit" name="brochaux" value=""/>';	   

	   //  Boutons de droite 	   	
	 	$i = 0;
		while($i < 7) {
              	$recette = $recettes[$i];
              	$decalage_haut = ($i+1)  * 10;
              	$buffer = $buffer.'<input class="bouton_brochaux" type="submit" style="top:'.$decalage_haut.'%;" name="brochaux" value="'.$recette->nom.'" />'; 
              	$i++;
           	} 

	   /* Le menu est rajouté dans cette div via du javascript */	
	$buffer = $buffer.' 
	<div id="menu">
	</div>';

	  
	  //  Bouton en dessous du menu pour les soirees
	$j = 0;
	if ($nb_bouton_soiree != 0) { 
           	$buffer .= '<span id="bouffe">'; 
          	while($j < min(5,$nb_bouton_soiree)) {
        	$b_s = $bouton_soiree[$j];
              	$decalage_haut = ($j+1)  * 10;
              	$buffer = $buffer.'<input type="submit"  name="brochaux" value="'.$b_s->nom.'" />'; 
              	$j++;
           }             
       $buffer .= '</span>';
	if ($nb_bouton_soiree > 5) { 
       $buffer .= '<span id="cocktails">'; 
           while($j < min(10,$nb_bouton_soiree)) {
              $b_s = $bouton_soiree[$j];
              $decalage_haut = ($j+1)  * 10;
              $buffer = $buffer.'<input type="submit"  name="brochaux" value="'.$b_s->nom.'" />'; 
              $j++;
           }  
	$buffer .= '</span>';
	}
  	}

           $buffer .= '</form>';
           return $buffer;
	
	if (condition) {
		$buffer .= '<script language="Javascript" type="text/Javascript">
				alert(\'Ce compte est en negatif, et le Trez a dit non !!\');
				</script>';
	}
}
	private static function menu() {
		$buffer = '<ul>';

		//Pour la vue minimaliste
		$cibles = isset($_COOKIE['format']) ? array(0,2) : array(0,1,2,3,4,5);

		foreach ($cibles as $i) {
			$buffer .= '
		<li id="tache' . $i . '">
		<a href="#form">' . self::$taches [$i] [0] . '</a>
		<div>
		';
			class_exists(self::$taches [$i] [0]) || require 'vues/' . self::$taches [$i] [0] . '.php';
			$maVue = self::$taches [$i] [0];
			$foo = new $maVue ();
			$buffer .= $foo->code ();
			$buffer .= '
		</div>
		</li>';

		}

		$buffer .= '</ul>';

		return $buffer;
	}


	public function code () {

		$buffer = $this->wrapMessage ($this->menu ());
		//ajout de la popu-up en fonction de l'option soldeInsuffisant

	if ($this->options == 'pop-up') {
		$buffer .= '<script language="Javascript" type="text/Javascript">
					alert(\'Solde insuffisant pour debiter ce compte !\');
				</script>';
		}

		$buffer .= $this->champ();

		return $buffer;
	}
}
?>
