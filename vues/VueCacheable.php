<?php
/**
 * VueCacheable : gère le cache
 * Si des options sont fournies, on ne regarde pas le cache
 * Sinon, on le prend les yeux fermés
 */
abstract class VueCacheable extends Vue {
	
	protected static $cache;
	protected static $chemin;

	public function __construct() {
		$args = func_get_args();
		call_user_func_array(array('parent', '__construct'), $args);

		$this->chemin = 'vues/cache/' . get_called_class(); 
	}

	public final function code () {
		if ($this->options) {
			//On n'utilise pas le cache
			return $this->fraiche();
		} else {
			//On va chercher dans le cache
			$fp = fopen($this->chemin, 'r');
			if ($fp === false) {
				//On n'a pas réussi à ouvrir le cache, on le recrée
				$this->regenerer();
				return $this->code();
			} else {
				$cache = fread($fp, filesize($this->chemin));
				fclose($fp);

				return $cache;
			}
		}
	}

	public function regenerer () {

		$cache = $this->fraiche();

		//On réécrit le fichier
		$fp = fopen($this->chemin, 'w');
		fwrite($fp, $cache);
		fclose($fp);
	}

	//Renvoie enfin la source...
	protected abstract function fraiche ();
}
