<?php
class HistoriqueCaisse extends VueCacheable {
   protected static $cache = array('
      <div id="tache-1">
         <p> Etat de la caisse : ',' euros</p>
         <table class="style">
            <tr>
               <th>Date</th>
               <th>Montant</th>
               <th>Raison</th>
            </tr>
             
            <tr>
               <th></th>
               <th></th>
            </tr>
            ','
         </table>
      </div>');

   public function fraiche () {

      $nouveauCache = self::$cache[0];

      $caisse = Parametre::valeur('caisse');
      $nouveauCache .= $caisse.self::$cache[1];

      $historiques = Historique_caisse::instances()->executer();
      foreach($historiques as $historique) {
        $nouveauCache .= '
           <tr> 
              <td>'.$historique->date.'</td>
              <td>'.$historique->montant.'</td>
              <td>'.$historique->raison.'</td>
           </tr> '; 
      }

      return $nouveauCache.self::$cache[2];
   }
}         
