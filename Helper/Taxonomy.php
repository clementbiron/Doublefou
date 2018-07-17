<?php
	
	namespace Doublefou\Helper;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Taxonomy
	 * @author Clément Biron
	 */
	Class Taxonomy extends Singleton
	{
		/**
		 * Desactivate a taxonomie
		 * @param string $taxonomie
		 */
		public static function desactivateTaxonomy($pTax)
		{
			add_action('init', function() use ($pTax){
				global $wp_taxonomies;
				if ( taxonomy_exists( $pTax))
					unset( $wp_taxonomies[$pTax]);
			});
		}
		
		/**
		 * Récupérer les taxnomy courantes passées en url
		 * @param string $pName
		 * @todo à re-tester
		 */
		public static function getCurrentTaxonomyByName($pName)
		{
			return wp_get_object_terms(get_the_ID(),$pName);
		}

		/**
		 * Récupérer la taxonomie courante
		 * @return object 
		 */
		public static function getCurrentTax()
		{
			return get_queried_object();
		}

		public static function getTaxBySlug($pSlug,$pTaxName)
		{
			return get_term_by('slug',$pSlug,$pTaxName);
		}

		public static function getTaxLinkBySlug($pSlug,$pTaxName)
		{
			$tax = self::getTaxBySlug($pSlug,$pTaxName);
			return get_term_link($tax);
		}
	}
?>