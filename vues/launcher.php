<?php
function require_safe($cible) {
	class_exists($cible) || include 'vues/' . $cible . '.php'; 
}



require_safe('Vue');
require_safe('VueCacheable');
require_safe('VueCentrale');
require_safe('Header');
require_safe('Footer');
