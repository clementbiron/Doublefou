<?php
	
	namespace Doublefou\Components;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Gestion d'un lien de fil d'ariane
	 * @author Clément Biron
	 */
	Class BreadcrumbLink
	{
		/**
		 * Le lien
		 * @var string
		 */
		private $link = "";

		/**
		 * Le titre
		 * @var string
		 */
		private $title = "";

		/**
		 * Si c'est l'item courant
		 * @var boolean
		 */
		private $current = false;
		
		/**
		 * Constructeur
		 * @param string $pLink  Lien
		 * @param string $ptitle Titre
		 */
		public function __construct($pLink, $ptitle, $pCurrent = false){
			$this->link = $pLink;
			$this->title = $ptitle;
			$this->current = $pCurrent;
		}
		
		/**
		 * Get
		 */
		public function __get($pPropertie){
			if(property_exists($this, $pPropertie)){
				return $this->$pPropertie;
			}
			else return;
		}
	}
?>