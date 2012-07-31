<?php
/**
 * Controleur des vues
 * @version 2a
 */
abstract class Vue {

	protected $options;

	/*
	 * Constructeur : un paramètre optionnel : un array d'options, spécifique à chaque vue
	 */
	public function __construct () {
		$this->options = func_num_args() == 1 ? func_get_arg(0) : null;
	}

	/**
	 * Rend le code source de cette vue
	 * @return code html
	 */
	abstract public function code ();

	/**
	 * Affiche directement le code source
	 */
	public final function display () {
		echo $this->code();
	}

}
