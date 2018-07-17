<?php
	
	namespace Doublefou\Components;
	use Doublefou\Core\Debug;
	use Doublefou\Components\CustomMenuCollection;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Custom menu item
	 * @author Clément Biron
	 */
	Class CustomMenuItem
	{
		private $id;
		private $parentId;
		private $title;
		private $permalink;
		private $childrenCollection;

		/**
		 * [__construct description]
		 * @param integer $pId        [description]
		 * @param integer $pParentId  [description]
		 * @param string $pTitle     [description]
		 * @param string $pPermalink [description]
		 */
		public function __construct($pId,$pParentId,$pTitle,$pPermalink){
			$this->id = (int) $pId;
			$this->parentId = (int) $pParentId;
			$this->title = $pTitle;
			$this->permalink = $pPermalink;
			$this->childrenCollection = new CustomMenuCollection();
		}

		/**
		 * Récupérer l'identifiant
		 * @return integer
		 */
		public function getID(){
			return $this->id;
		}

		/**
		 * Récupérer l'identifiant du parent
		 * @return integer
		 */
		public function getParentID(){
			return $this->parentId;
		}

		/**
		 * Récupérer le titre
		 * @return string
		 */
		public function getTitle(){
			return $this->title;
		}

		/**
		 * Récupérer le permalien
		 * @return string
		 */
		public function getPermalink(){
			return $this->permalink;
		}

		/**
		 * Ajouter un enfant dans la collection
		 * @param CustomMenuItem $pChildren 
		 */
		public function addChildren(CustomMenuItem $pChildren){
			$this->childrenCollection->addChildren($pChildren);
		}

		/**
		 * Récupérer la collection enfant de cet item
		 * @return CustomMenuCollection 
		 */
		public function getCustomMenuCollection(){
			return $this->childrenCollection;
		}

		public function hasChildren()
		{
			if(count($this->childrenCollection->getItems()) > 0) return true;
			else return false;
		}
	}

?>