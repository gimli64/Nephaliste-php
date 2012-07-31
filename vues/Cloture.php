<?php
class Cloture extends VueCacheable {

	protected static $cache = array('
<div id="tache-1">
	<form method="post" action="', '">
		<input type="hidden" name="tache" value="Cloture" />
                <strong> Pour fermer UN compte, allez plutôt dans "Modification de compte"</strong>
		<p>
			Vous pouvez ici choisir d\'ouvrir ou de fermer des comptes, par année ou par personne, avec un filtre sur le solde (positif/négatif) en plus.<br/>
			<label for="annees_ouvertes">Nombre d\'années à laisser ouvertes : </label><input type="text" name="annees_ouvertes" id="annees_ouvertes" value="', '" />
		</p>
		<p>
			<input type="radio" id="fermer_negatif_1" name="fermer_negatif" value="1" /> <label for="fermer_negatif_1">Fermer également les comptes en négatif</label><br/>
			<input type="radio" id="fermer_negatif_0" name="fermer_negatif" value="0" /> <label for="fermer_negatif_0">Laisser les comptes en négatif ouverts</label><br/>
			
		</p>
			
		<p>
			Pour spécifier des règles plus fines (pour telle année, laisser les comptes négatifs ouverts, pour telle autre, les fermer, fermer ce compte, ouvrir celui-ci, etc.), utilisez le champ ci-dessous.<br/>
			La syntaxe est simple. Deux lignes suffisent à tout faire, mais en utiliser plus peut être une bonne idée pour ne pas se planter.<br/>
			On commence par dire si on veut <cite>ouvrir</cite> ou <cite>fermer</cite>. Après un espace, on met la liste des éléments à qui on veut appliquer la commande.<br/>
			Chaque élément est séparé des autres par une virgule.<br/>
			Pour spécifier une promo en absolu, on met l\'année (exemple : <cite>2012</cite>).<br/>
			Pour spécifier une promo en relatif, on met la différence d\'années avec la promo actuelle (exemple : <cite>-2</cite>).<br/>
			Pour spécifier une personne, on met son titre (Nom "surnom" Prénom) entre crochets (exemple : <cite>[François "fesse" Savary]</cite>).<br/>
			Après chaque année (en relatif ou absolu), on peut mettre un signe * pour spécifier qu\'il ne faut fermer que ceux dont le compte est en positif <em>pour un clôture</em> et qu\'il ne faut ouvrir que ceux dont le compte est en négatif <em>pour une rouverture</em>.<br/>
			<br/>
			Voici des exemples :
			
			<dl id="script">
				<dt>Fermeture intégrale des deux dernières promotions (i.e. si la promo en cours est 2015, fermer 2014 et 2013) :</dt>
				<dd>fermer -1,-2</dd>
				
				<dt>Fermeture des promotions 2012 (ceux qui ont un compte positif) et 2011 (tous)</dt>
				<dd>fermer 2011,2012*</dd>
				
				<dt>Ouverture intégrale de ces promotions</dt>
				<dd>ouvrir 2011,2012</dd>
				
				<dt>Fermer le compte de Bruno Cauet</dt>
				<dd>fermer [Bruno Cauet]</dd>
				
				<dt>Rouvrir les comptes de Bruno Cauet, Marc Beauchet et de ceux qui ont un compte en négatif parmi la promo 2011</dt>
				<dd>ouvrir [Bruno Cauet],[Jocelyn Caulet],2011*</dd>
				
				<dt>Commande qui ne fait à peu près rien quand la promo actuelle est 2013 (en fait ça va supprimer toutes les règles spéciales concernant la promo 2012)</dt>
				<dd>fermer 2012<br/>ouvrir -1</dd>
			
			</dl>
			<textarea name="regles"></textarea>
		</p>

		<input type="submit" value="Clôturer/rouvrir" />
	</form>

</div>');

	public function fraiche () {
		return self::$cache[0] . RACINE . self::$cache[1] . Parametre::valeur('anneesOuvertes') . self::$cache[2];
	}
}
?>
