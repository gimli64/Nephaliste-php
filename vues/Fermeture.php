<?php

class Fermeture extends VueCacheable {

       protected static $cache = array('
<div id="tache-1">
    <form method="post" action="', '">   
        <input type="hidden" name="tache" value="MailFermeture" />
   
        <p> N\'oubliez pas de nettoyer, de vider les poubelles et de tout éteindre avant de fermer la coopé </p>

        <input type="submit" class="milieu" value="Envoyer le mail de confiance aux clients et fermer la coopé" />
    </form>
</div>
</body>
</html>
');

      public function fraiche () {
              return self::$cache[0]."/". self::$cache[1];
      }  
}
