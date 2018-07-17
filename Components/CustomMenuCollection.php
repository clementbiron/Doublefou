<?php
	
	namespace Doublefou\Components;
	use Doublefou\Components\CustomMenuItem;
	use Doublefou\Core\Debug;
	use Exception;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Custom menu collection
	 * @author Clément Biron
	 */
	Class CustomMenuCollection
	{
		/**
		 * Les élements du menu
		 * @var array
		 */
		private $items = array();

		/**
		 * Constructeur
		 * @param string $pMenuSlug le slug du menu
		 */
		public function __construct($pMenuSlug = false){

			//Si on construit l'objet à partir du slug du menu
			if($pMenuSlug != false){
				
				//On recupère les menu locations
				$locations = get_nav_menu_locations();

				//Si le menu existe bien 
				if (!empty($locations[$pMenuSlug])){

					//Alors on récupère l'objet wp
					$menuObject =  wp_get_nav_menu_object($locations[$pMenuSlug]);

					//Et les élements
					$menuItems = wp_get_nav_menu_items($menuObject->term_id);

					//On construit les élements
					$this->buildItems($menuItems);

				}else{
					throw new Exception("Le menu ".$pMenuSlug." n'existe pas.");
				}
			}
		}

		/**
		 * Construire les élements du menus à partir du tableau
		 * généré par la fonction wp_get_nav_menu_items
		 * @param array $pMenuItems 
		 */
		private function buildItems($pMenuItems){

			//Parcourire les items
			foreach((array) $pMenuItems as $key => $menuItem){

				//Data
				$id = (int) $menuItem->ID;
				$parentId = (int) $menuItem->menu_item_parent;
				$title = $menuItem->title;
			    $permalink = ($menuItem->url == 'http://#') ? false : $menuItem->url;

			    //Nouvel item à la collection
			    $collectionItem = new CustomMenuItem(
			    	$id,
			    	$parentId,
			    	$title,
			    	$permalink
			    );

			    //Si il n'y a pas d'élement parent
			    if($parentId == 0){
			    	
			    	//On met dans la collection courante
			    	array_push($this->items, $collectionItem);
			    }else{

			    	//Sinon on ajoute dans la collection parente
			    	$this->addToParentCollection($parentId,$collectionItem);
			    }			   
			}
		}

		/**
		 * Ajouter nun enfant dans la collection
		 * @param CustomMenuItem $pChildren 
		 */
		public function addChildren(CustomMenuItem $pChildren){
			array_push($this->items, $pChildren);
		}

		/**
		 * Ajouter dans la collection du parent
		 * @param integer $pParentId 
		 * @param CustomMenuItem $pItem 
		 */
		public function addToParentCollection($pParentId,$pItem){

			//On parcoure la collection courante
			foreach ($this->items as $item) {

				//On récupere la collection du parent
				$parentCollection = $item->getCustomMenuCollection();

				//Si l'item id == au parent id, on ajoute l'enfant 
				//dans cette collection
				if($item->getID() == $pParentId){
			    	$parentCollection->addChildren($pItem);
				}

				//Sinon on parcoure la collection parente
				else{
					$parentCollection->addToParentCollection($pParentId,$pItem);
				}
			}
		}

		/**
		 * Récupérer tous les éléments du menu
		 * @return array 
		 */
		public function getItems(){
			return $this->items;
		}
	}

?>