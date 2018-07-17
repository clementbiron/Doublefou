<?php

	namespace Doublefou\Components;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	* Composant de sommaire automatique
	* @author Clément Biron
	* @example $summary = new Summary('2-4');
	*/
	class Summary{

		private $_pattern = "/<h([2-4])(.*?)>(.*?)<\/h([2-4])>/i";
		private $_summary = array();
		private $_content;
		private $_guid = '';

		/**
		 * Constructeur
		 * @param string $pHLevel niveaux des titres à cibler, chaine de type from-to, exemple : '2-4'
		 */
		public function __construct($pHLevel = null, $pContent = null){

			//Global
			global $post;

			//Setup guid
			if(isset($post->ID) && !empty($post->ID)){
				$this->_guid = $post->ID;				
			}

			//Si on beson de redéfinir un ensemble de niveau à cibler
			if ($pHLevel != null){
				$this->_pattern = "/<h([".$pHLevel."])(.*?)>(.*?)<\/h([".$pHLevel."])>/i";
			}

			//Si on cherche dans un contenu particulier (type acf field)
			//On redéfinit le contenu sur lequel on bosse
			if ($pContent != null){
				$this->_content = $pContent;
			}

			//Sinon on prend le contenu du post en cours
			else{
				$this->_content = $post->post_content;
			}

			//On cherche dans le contenu avec la regex 
			$this->_content = preg_replace_callback($this->_pattern, array($this, 'replace'), $this->_content);

			//On ajoute un filtre sur l'affichage du contenu
			//Pour retourner le contenu modifié
			add_filter('the_content', array($this,'getContent'),0);
	
		}
		
		/**
		 * Replace
		 */
		private function replace($pMatches){
			
			//On créer l'id avec le titre et un nombre aléatoire
			$id = sanitize_title($pMatches[3]).'-'.$this->_guid;

			//Pour chaque résultat, on créer un objet SummaryElement et on le stocke
			array_push($this->_summary,new SummaryElement($pMatches[1],$pMatches[3], $id));

			//Et on retourne la chaine modifiée avec l'ancre pour le contenu
			return '<h'.$pMatches[1].$pMatches[2].' id="'.$id.'">'.$pMatches[3].'</h'.$pMatches[4].'>';
		}

		/**
		 * Retourne le sommaire
		 * @return array
		 */
		public function getSummary(){
			return $this->_summary;
		}

		public function getContent(){
			return $this->_content;
		}
	}


?>