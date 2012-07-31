<?php

//vue de tout l'historique

class Historique extends VueCacheable {

	protected static $cache = array('
				<input type="hidden" name="tache" value="Historique" />
                                <script type="text/javascript">
                                
                                   function NombreJour() {
                                      var mois = document.getElementById("mois").value;  
                                      var annee = document.getElementById("annee").value; 
                                      return (32 - new Date(annee,mois-1,32).getDate());    
                                   }
        
                                   function MasqueJour() {
                                      var nb_max = NombreJour() + 1;
                                      var nb = 1;
                                      if (document.getElementById("mois").value != "") {
                                         while(nb != nb_max) {
                                            document.getElementById("jour_" + nb).disabled = false; 
                                            nb++;
                                         }
                                      }
                                      while(nb != 32) {
                                         document.getElementById("jour_" + nb).disabled = true; 
                                         nb++;
                                      }
                                   }
                                </script>

				<input type="submit" value="Consulter" />
 
                                <h3> Historique des consommations </h3>
                                   <ul> 
                                      <li> Précisez un nom pour obtenir des informations sur ce compte </li>
                                      <li> Indiquez une date pour obtenir l\'historique des consommations à cette date</li>
                                      <li> Si rien n\'est précisé vous obtiendrez les 30 dernières consommations </li>
                                  </ul>

                                <div id="choixdate">
                                   <select name="annee" id="annee">
                                      <option></option>
                                   ','
                                   </select>
                                   <select name="mois" id="mois" onchange="MasqueJour()">
                                      <option></option>
                                   ','
                                   </select>
                                   <select name="jour" id="jour">
                                      <option></option> 
                                   ','
                                   </select> 
                                </div>
 
				
                                <p><a href="Etat-Stock">Aperçu du stock</a></p>
                                <p><a href="HistoriqueCaisse"> Historique de la caisse </a></p> ');                               
                                 
			
	public function fraiche () {
                $nouveauCache = self::$cache[0]; 
                
                setlocale('LC_ALL', 'fr_FR');
                $annee_max = 2012;
                $i = 0;
                
                while ($i < 4) {
                   $annee = $annee_max - $i;
                   $nouveauCache .= '<option value="'.$annee.'">'.$annee.'</option>';
                   $i++; 
                }
  
                $nouveauCache .= self::$cache[1];

                $i = 1;

                while ($i <= 12) {
                   $mois = date("F",mktime(0,0,0, $i, 1, 0));
                   $nouveauCache .= '<option value="'.$i.'">'.$mois.'</option>';
                   $i++;
                }
    
                $nouveauCache .= self::$cache[2];
                
                $i = 1;
                       
                while ($i <= 31) {
                   $nouveauCache .= '<option disabled="true" value="'.$i.'" id="jour_'.$i.'">'.$i.'</option>';
                   $i++;
                }

		return $nouveauCache.self::$cache[3];
	}
}
?>
