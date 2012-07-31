<?php
class Historique_caisse extends Modele {
   public $id;
   public $date;
   public $montant;
   public $raison;

   protected static $_table = 'historique_caisse';
}
?>
