<?php
class Mail_ComptesNegatifs extends VueCacheable {

   protected static $cache = array('
   <div id="tache-1">
      <form action="', '" method="post">
         <input type="hidden" name="tache" value="MailComptesNegatifs" />

         <div id="sujet">  
            <label for="subject"> Sujet :</label><input type="text" name="subject" />
         </div>
         <div id="corps">  
            <label for="body"> Corps du mail :</label>
               <textarea name="body"></textarea>
         </div>
	<span id="montant">
		<label for="montant">Negatif entre :</label><input type="text" name="amount_min" value="0" />
	       	et  <input type="text" name="amount_max" />
        </span>
	<br/>
	<em>Indiquer "0" dans la premiere et "100" dans la seconde envoit le mail aux gens dont le negatifs est entre 0 et 100...</em>
	<br/>
	<span id="promo">
		<label for="promo">Promo :</label>
				<input type="checkbox" name="2010" value="2010" />2010
				<input type="checkbox" name="2011" value="2011" />2011
				<input type="checkbox" name="2012" value="2012" />2012
				<input type="checkbox" name="2013" value="2013" />2013
				<input type="checkbox" name="2014" value="2014" />2014
	</span>
	<br/>
	<br/>
	<input type="submit" value="Envoyer le mail" />
      </form>
   </div>');

   public function fraiche() {

      $nouveau_cache = self::$cache[0].RACINE.self::$cache[1];
      return $nouveau_cache;
   }
}
