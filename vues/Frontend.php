<?php
class Frontend extends VueCacheable {

	protected static $cache = array('
<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> 
		         
<head> 
	<title>Le Néphaliste</title>
	<link rel="stylesheet" type="text/css" href="style.css" /> 
</head>
<body>
	<div id="menu">
		<h1>Aujourd\'hui, la Coopé vous propose :</h1>
		<ul>
','
		</ul>
	</div>
	<div id="stats">
		<h1>Statistiques</h1>
','
	</div>
	<div id="solde">
	<form action="solde.php" method="post">
		<p>Vous pouvez demander ici votre solde, qui vous sera envoyé par email :<br/>
		<label>Adresse email : <input type="text" name="email" /></label> <input type="submit" name="Recevoir" />
		</p>
	</form>
	</div>
</body>
</html>');

	protected function fraiche () {
		$cache = self::$cache[0];
		
		$recettes = Recette::instances()->filtrer('disponible=1')->executer();
		
		foreach ($recettes as $recette) {
			$cache .= '
			<li>' . $recette->nom . ' à ' . $recette->prix . ' €</li>';	
		}

		$cache .= self::$cache[1];

		//On rajoute les statistiques
		require_safe('Statistiques');
		$stat = new Statistiques();
		$cache .= $stat->code();

		$cache .= self::$cache[2];
		
		return $cache;
	}

	public function regenerer () {
		$cache = self::fraiche();

                //A remettre en place !!! 
		//file_put_contents('ftp://coope:coopeman@www.rez-gif.supelec.fr/~coope',$cache,0,stream_context_create(array('ftp' => array ('overwrite' => true))));
	}
}
?>
