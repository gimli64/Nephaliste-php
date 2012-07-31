<?php
class Mail_Ouverture extends VueCacheable {

   protected static $cache = array('
   <div id="tache-1">
      <form action="', '" method="post">
         <input type="hidden" name="tache" value="MailOuverture" />

         <h3> Envoi du tripromo </h3> 
         <div id="sujet">  
            <label for="subject"> Sujet :</label><input type="text" name="subject" />
         </div>
         <div id="corps">  
            <label for="body"> Corps du mail :</label>
               <textarea name="body"></textarea>
         </div>
         <input type="submit" value="Envoyer le mail" />
      </form>
   </div>');

   public function fraiche() {

      $nouveau_cache = self::$cache[0].RACINE.self::$cache[1];
      return $nouveau_cache;
   }
}
