<?php
/*
 * En-têtes du site
 */
class Header extends Vue {

	private $title = 'Le Néphaliste';
	protected static $cache = array('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

<head>
	<title>', '</title>
',
'	<link rel="stylesheet" type="text/css" href="res/style.css" />',
'	<link rel="stylesheet" type="text/css" href="res/mini.css" />',
'	<link rel="stylesheet" type="text/css" href="res/jquery.autocomplete.css" />
	<link rel="icon" type="image/png" href="res/images/coopeman.png" />
<!--	<script type="text/javascript" src="res/js/rassembles.js"></script> -->
	<script type="text/javascript" src="res/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="res/js/jquery.autocomplete.pack.js"></script>
	<script type="text/javascript" src="res/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="res/js/metadata.js"></script>
	<script type="text/javascript" src="res/js/script.js"></script> 
	<script type="text/javascript" src="res/js/refuserToucheEntree.js"></script>
<!--[if IE]><script type="text/javascript" src="res/js/jquery.bgiframe.min.js"></script><![endif]-->
</head>

<body>
');

	public function code () {
		header ( 'Content-type: application/xhtml+xml; charset=UTF-8' );
		//header ( 'Content-type: text/html; charset=UTF-8' );

		return self::$cache[0] . (isset($this->options['title']) ? $this->options['title'] : $this->title) . self::$cache[1] . (isset($_COOKIE['format']) ? self::$cache[3] : self::$cache[2]) . self::$cache[4];

	}
}
?>
