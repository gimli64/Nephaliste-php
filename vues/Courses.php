<?php
class Courses extends VueCacheable {

   protected static $cache = array('
<div id="tache-1">
   <form method="post" action="', '">
      <input type ="hidden" name="tache" value="FaireLesCourses" />

      <table class="style">
         <tr>  
            <th> Nom </th>
            <th> Stock </th>           
         </tr>      
         ','
      </table> 
      <p>
         <label for="montant" style="width: 200px;"> Montant des courses : </label><input type="text" name="montant" id="montant" />
      </p>

      <p class="centre">
         <input type="submit" class="milieu" value="Modifier la caisse" />
      </p>

   </form>
</div>');

   public function fraiche() {
      $nouveauCache =  self::$cache[0] . RACINE . self::$cache[1];
      $recettes = Recette::instances()->executer();
      
      foreach($recettes as $recette) {
         $nouveauCache .= '
            <tr>
               <td>'.$recette->nom.'</td>
               <td><input type="text" name="'.$recette->id.'" value="'.$recette->stock.'" /></td>
            </tr>'; 
      }
      
      $nouveauCache .= self::$cache[2];
      return $nouveauCache;
   }
}
