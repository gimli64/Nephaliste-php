<?php
//require_once 'controleurs/launcher.php';
require 'vues/launcher.php';
require 'modeles/launcher.php';

require_safe('Statistiques');
Statistiques::regenerer();
?>
