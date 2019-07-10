<?php
	
	namespace Doublefou\Helper;
	use Doublefou\Core\Debug;
	use Doublefou\Core\Singleton;
	use Doublefou\Tools\StringTool as StringTool;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Page
	 * @author Clément Biron
	 */
	Class Page extends Singleton
	{
		/**
		 * Récupérer le lien d'une page à partir de son slug
		 * @param string $pPageSlug Slug
		 * @return string
		 * @see http://codex.wordpress.org/Function_Reference/get_page_by_path Pour les paramètres
		 */
		public static function getPageLinkBySlug($pPageSlug) {
			$page = get_page_by_path($pPageSlug);
	  		if ($page) :
		    	return get_permalink( $page->ID );
		  	else :
		    	return "#";
		  	endif;
		}
		
		/**
		 * Récupérer une page par son slug
		 * @param string $pPageSlug Slug de la page
		 * @return object|false
		 */
		public static function getPageBySlug($pPageSlug){
			$page = get_page_by_path($pPageSlug);
			if ($page) :
		    	return $page;
		  	else :
		  		return false;
		  	endif;
		}
		
		/**
		 * Récupérer la page courante
		 * @return object
		 */
		public static function getCurrentPage(){
			return  get_page(get_query_var('page'));
		}
		
		/**
		 * Récupérer la description de la page courante
		 * @param interger $pNbWords Nombre de mots de la description
		 * @return string|null
		 */
		public static function getCurrentPageDescription($pNbWords){		
			$currentPage = Page::getCurrentPage();
			if($currentPage){
				return StringTool::cutByWords(strip_tags($currentPage->post_content),$pNbWords);
			}
			return null;
		}
		
		/**
		 * Savoir si la page courante est l'enfant d'une autre
		 * @return Boolean
		 */
		public static function isPageChild($parentID = null)
		{
			global $post;      
			$parentPage = get_page($post->post_parent);	
			if(is_page()){
				if($parentID != null){
					if(($parentPage->ID == $post->post_parent) && ($parentPage->ID == $parentID)){
						return true;
					}
				}else if($parentPage->ID == $post->post_paren){
					return true;
				}
			}else{
				return false;
			}
		}
		
		/**
		 * Ajouter la gestion de l'excerpt pour les page
		 */
		public static function addExcerpt()
		{
			add_post_type_support( 'page', 'excerpt' );
		}

		/**
		 * hideInAdminByID
		 * @param  integer $pId ID de la page à masquer
		 * @param  string $pRole Rôle à partir duquel la page apparait
		 * @return object 
		 */
		public static function hideInAdminByID($pId, $pRole = 'activate_plugins')
		{
			//Après la création de la query mais avant son lancement
			add_action('pre_get_posts', function($query) use ($pId,$pRole)
			{
				//Si on est pas en admin -> go out
				if(!is_admin())
					return $query;

				global $pagenow;

				//Si l'utilisateur n'a pas le role
				if(!current_user_can($pRole)){

					//Et que l'on est sur la bonne page de l'admin
					if('edit.php' == $pagenow && ( get_query_var('post_type') && 'page' == get_query_var('post_type') ) ){

						//On modifie la query
						$query->set('post__not_in', array($pId));						
					}
				}

				return $query;
			});
		}

		/**
		 * hideInAdminByPageTemplate
		 * @param  array $pPageTemplate tableau des noms du page template, exemple : page-pattern.php
		 * @param  string $pRole Rôle à partir duquel la page apparait, default "activate_plugins
		 * @return object 
		 */
		public static function hideInAdminByPageTemplate($pPageTemplates, $pRole = 'activate_plugins')
		{
			//Après la création de la query mais avant son lancement
			add_action('pre_get_posts', function($query) use ($pPageTemplates,$pRole)
			{
				//Si on est pas en admin -> go out
				if(!is_admin())
					return $query;

				global $pagenow;

				//Si l'utilisateur n'a pas le role
				if(!current_user_can($pRole)){

					//Et que l'on est sur la bonne page de l'admin
					if('edit.php' == $pagenow && ( get_query_var('post_type') && 'page' == get_query_var('post_type') ) ){
												
						//On modifie la query
						$query->set('meta_query', array(
								'relation' => 'AND',
								array(
									'key'     => '_wp_page_template',
									'value'   => $pPageTemplates,
									'compare' => 'NOT IN'
								)
							)
						);							
					}
				}

				return $query;
			});
		}
	}

?>