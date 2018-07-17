<?php
	
	namespace Doublefou\Components;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	* Element d'un objet Summary
	* @author Clément Biron
	*/
	class SummaryElement{

		private $_level;
		private $_title;
		private $_id;

		/**
		 * Constructeur
		 * @param integer $pLevel niveau de profondeur
		 * @param string $pTitle titre de l'élement
		 * @param string $pID identifiant unique
		 */
		public function __construct($pLevel, $pTitle, $pID){
			$this->_level = $pLevel;
			$this->_title = $pTitle;
			$this->_id = $pID;
		}

		/**
		 * Récupérer le niveau de l'élement
		 * @return integer niveau de profondeur
		 */
		public function getLevel(){
			return $this->_level;
		}

		/**
		 * Récupérer le titre de l'élement
		 * @return string titre de l'élement
		 */
		public function getTitle(){
			return $this->_title;
		}

		/**
		 * Récupérer l'identifiant de l'élement
		 * @return string identifiant unique
		 */
		public function getID(){
			return $this->_id;
		}
	}

?>