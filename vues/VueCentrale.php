<?php
/**
 * VueCentrale : gère les messages
 */
abstract class VueCentrale extends Vue {

	protected $message = false;
	protected $toutVabien = true;  //Le fameux booléen qui indique si tout va bien ou pas, la couleur de fond change en fonction

	/*
	 * Constructeur : 2 paramètres optionnels : message et options
	 */
	public function __construct () {
		switch (func_num_args()) {
		case 2:
			parent::__construct(func_get_arg(1));
		case 1:
			$this->message = $this->formatMessage (func_get_arg(0));
		default:
			break;
		}
	}

	/*
	 * Rend un message formaté, en <li>
	 * Appelé par le constructeur, surtout
	 * @param $message	Message qu'on veut formater
	 * @return Le message formaté
	 * Les messages sont liés à une option, si une des options est à false
	 * quelque chose s'est mal passé, on change le fond en rouge, sinon il est vert
	 */
	protected function formatMessage ($message) {
	   if (! empty ( $message )) {
	      $buffer = '<ul>';	

              foreach($message as $msg => $option) {

		 /* 
		 * Si tout va bien pour l'instant on vérifie sur l'option de chaque message
		 * Sinon c'est qu'il y a un message d'erreur 
 		 * On laisse toutVabien à false et le fond des messages sera rouge
		 */ 
		 if($this->toutVabien)
                 	$this->toutVabien  = ($option == 'ok') ? true : false;
                 $buffer .= '<li>'.$msg.'</li>'; 
        	     	
	}                
	      
              $buffer .= '</ul>';
              return $buffer; 
           } else {
              return false;
	   }
	}  

	/**
	 * Permet d'afficher facilement le message dans la page
	 */
	protected final function wrapMessage ($menu=false) {
		if ($this->message !== false || $menu) {
			//Il y a quelque chose à afficher

			$buffer = '<div id="sidebar">';

			//On n'affiche la boîte à messages que s'il y en a !
			//toutVabien permet de régler la couleur de fond
			if ($this->message !== false) {
				$couleur_fond = ($this->toutVabien)? 'green' : 'red';
				$buffer .= '
<div style="border: 2px dotted blue; background-color: '.$couleur_fond.';">' . $this->message . '</div>
';
			}

			$buffer .= $menu . '</div>';

			return $buffer;
		} else {
			return '';
		}
	}
}
