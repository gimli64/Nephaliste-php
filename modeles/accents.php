<?php
function accents($str, $charset='utf-8')
{
	//Fonction idéale, mais au comportement un peu aléatoire
	//return iconv($charset, 'ASCII//TRANSLIT//IGNORE', $str);


	$str = htmlentities($str, ENT_NOQUOTES, $charset);
			
	$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

	return $str;
}
?>
