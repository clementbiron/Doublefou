<?php
	
	namespace Doublefou\Components;
	use Doublefou\Helper\Category;
	use Doublefou\Helper\Taxonomy;
	use Doublefou\Core\Debug;
	use Doublefou\Tools\ArrayTool;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Gestion du fil d'ariane
	 * @author Clément Biron
	 * @todo A FINIR
	 */
	class Breadcrumb
	{
		/**
		 * Array des liens 
		 * @var Array
		 */
		private $_breadCrumbLinks = array();

		/**
		 * Constructeur
		 */
		public function __construct(){

			//Pour la front page
			$frontpage = new BreadcrumbLink(get_bloginfo('url'), 'Accueil', is_front_page());
			$this->addLink($frontpage);

			//Single
			if (is_single() || is_page()) { 

				//Mais pas la front page
				if(!is_front_page()){

					//Récupération des données
					$permalink = get_permalink();
					$title = get_the_title();

					//Si on en dispose, on ajoute le lien au Breadcrumnb
					if(($permalink != '') && ($title != '')){
						$this->addLink(									
							new BreadcrumbLink(
								get_permalink(), 
								get_the_title(), 
								true
							)											 
						);
					}
					
				}
			}

			//Si c'est une catégorie on crée l'objet qui va bien
			//avec les infos de la cat courante
			if(is_category()){
				$currentCat = Category::getCurrentCategory();
				$this->addLink(
					new BreadcrumbLink(
						Category::getCategoryLink($currentCat), 
						$currentCat->name, 
						true
					) 
				);
			}

			//Si c'est une tax
			//On récupère la tax courante pour construire le lien
			if(is_tax() || is_tag()){
				$currentTax = Taxonomy::getCurrentTax();
				$this->addLink(
					new BreadcrumbLink(
						get_term_link($currentTax), 
						$currentTax->name, 
						true
					) 
				);
			}
		}

		/**
		 * Récupérer le fil d'ariane
		 * @return [type] [description]
		 */
		public function getBreadCrumb()
		{
			return $this->_breadCrumbLinks;
		}

		/**
		 * Ajouter un BreadcrumbLink à une position donnée
		 * @param BreadcrumbLink $pBreadcrumbLink Objet BreadcrumbLink
		 * @param integer $pPosition Null par défaut
		 */
		public function addLink($pBreadcrumbLink,$pPosition = null){
			if($pPosition == null){
				array_push(
					$this->_breadCrumbLinks,
					$pBreadcrumbLink
				);
			}else{
				$tempArray = ArrayTool::insertInPos($this->_breadCrumbLinks,$pPosition,$pBreadcrumbLink);
				unset($this->_breadCrumbLinks);
				$this->_breadCrumbLinks = $tempArray;
			}
		}
	}
?>