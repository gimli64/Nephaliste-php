<?php
abstract class Controleur {

	private $message = Array();
	private $param;

	private $cible = false;
	private $options = Array();

	protected $regenerer = array();

	/**
	 * Constructeur : appelle validite() et action()
	 */
	public function __construct(&$param) {
		$this->param =& $param;

		if ($this->validite ()) {
			$action = $this->action ();

			//On régénère les vues indiquées
			$this->regenerer();

			//On met fin à la transaction
			Query::fin();
		} else {
			$this->addMessage('Les donnnées passées ne sont pas valides ou sont incomplètes.');
		}
	}

	/**
	 * Si le controleur veut forcer une certaine vue à être utilisée
	 */
	protected function setCible ($cible, $options) {
		$this->cible = $cible;
                if($options != null) 
		   $this->options = $options;
	}

	public function getCible () {
		return $this->cible;
	}

	public function getOptions () {
		return $this->options;
	}


	protected function addMessage($message) {
		if (!is_array($message)) {
			$this->message = array_merge($this->message, array($message => null));
		} else {
			$this->message = array_merge($this->message, $message);
		}
	}

	public function getMessage() {
		return $this->message;
	}

	protected function getParams() {
		return $this->param;
	}

	protected function getParam($param) {
		return isset ($this->param[$param]) ? $this->param [$param] : null;
	}

	/**
	 * Teste la validité des arguments
	 * 
	 * @return bool $reussi		Test réussi : $reussi
	 */
	protected function validite() {
		return true;
	}

	/**
	 * Effectue l'action
	 * 
	 * @return bool $reussi	Action réussie : $reussi.
	 */
	protected function action() {
		return true;
	}

	/**
	 * Rafraîchit les vues nécessaires
	 *
	 * @return void
	 */
	protected function regenerer() {
		if (is_array($this->regenerer)) {
			foreach ($this->regenerer as $vue) {
				require_safe($vue);
				$objet = new $vue ();
				$objet->regenerer();
			}
		} elseif (is_string($this->regenerer)) {
			//Il n'y a qu'une seule vue à régénérer
			$classe = $this->regenerer;
			require_safe($classe);
			$objet = new $classe ();
			$objet->regenerer ();
		}
	}
}
?>
