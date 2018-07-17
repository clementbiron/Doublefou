<?php
	
	namespace Doublefou\Helper;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Category 
	 * @author Clément Biron
	 */
	Class Category extends Singleton
	{
		/**
		 * Get a category link by slug
		 * @param string  $pCategorySlug Slug de la catégorie
		 * @return string Lien de la catégorie
		 */
		public static function getCategoryLinkBySlug($pCategorySlug){
			$cat = get_category_by_slug($pCategorySlug);
			if($cat){
				return get_category_link($cat->term_id);
			}
		}
		
		/**
		 * Get the current category description
		 * @return string|false
		 */
		public static function getCurrentDescription(){
			$currentCategory = Category::getCurrentCategory();
			if($currentCategory !=  false){
				return $currentCategory->category_description;
			}else{
				return false;
			}
		}
		
		/**
		 * Get the current category
		 * @return object|false
		 */
		public static function getCurrentCategory(){
			$queryvar = get_query_var('cat');
			if(!empty($queryvar)){
				return get_category(get_query_var('cat'));
			}else{
				return false;
			}
		}
		
		/**
		 * Get category link by category
		 * @param object Category
		 * @return string
		 */
		public static function getCategoryLink($pCategory){
			return get_category_link($pCategory->term_id);
		}
	}
?>