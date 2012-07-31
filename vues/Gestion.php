<?php
class Gestion extends VueCacheable {

	protected static $cache = '
<h4> Gestion des Recettes </h4>
   <ul>
      <li><a href="Nouvelle-Recette">Ajouter une recette</a></li>
      <li><a href="Modifier-Recettes">Modifier les recettes</a></li>
      <li><a href="Courses"> Faire les courses </a></li>
   </ul>

<h4> Gestion des Comptes </h4>
   <ul>
      <li><a href="Control-ComptesListe">Aperçu de tous les comptes</a></li>
      <li><a href="Modifier-Compte">Modification de compte</a></li>
      <li><a href="Mail-ComptesNegatifs"> Envoyer les mails de rappels aux comptes négatifs </a></li>
      <li><a href="Cloture">Clôturer ou rouvrir des comptes</a></li>
   </ul>  

	<p><a href="Control-Minimaliste">Version minimaliste</a></p>

	<p><a href="Mentions">A propos du site : mentions et mode d\'emploi</a></p>
	
	<p><a href="Administration">Administration</a></p>
';

	public function fraiche () {
		return self::$cache;
	}
}
?>
