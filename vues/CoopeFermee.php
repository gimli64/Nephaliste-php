<?php

class CoopeFermee extends VueCentrale {

         protected static $cache = '
<div id="tache-1">
   
    <p> Bonne nuit, A demain </p>

</div>
</body>
</html>
';

         public function code() {
                 $buffer = $this->wrapMessage();
                 $buffer .= self::$cache;
                 return $buffer;
         }
}
