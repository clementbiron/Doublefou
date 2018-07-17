<?php
	
	namespace Doublefou\Helper;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * YOAST
	 * @author Clément Biron
	 */
	abstract Class Yoast extends Singleton
	{

		/**
		 * Supprimer les commentaires html injecté par Yoast dans le footer
		 * @todo a tester
		 */
		public static function removeFooter()
		{
			add_action('get_header', function (){
				ob_start(function($output){
					if (defined('WPSEO_VERSION')) {
						$output = str_ireplace('<!-- This site is optimized with the Yoast WordPress SEO plugin v' . WPSEO_VERSION . ' - https://yoast.com/wordpress/plugins/seo/ -->', '', $output);
						$output = str_ireplace('<!-- Avis pour l\'administrateur&nbsp;: cette page n\'affiche pas de méta description car elle n\'en a pas. Vous pouvez donc soit l\'ajouter spécifiquement pour cette page soit aller dans vos réglages (SEO -> Titres) pour configurer un modèle. -->', '', $output);
						$output = str_ireplace('<!-- / Yoast WordPress SEO plugin. -->', '', $output);
					}
					return $output;	
				});
			});
			add_action('wp_head', function(){
				ob_end_flush();
			}, 100);
		}

		/**
		 * Permettre à Yoast d'analyser les champs ACF
		 * @todo à compléter, finaliser
		 */
		public static function makeACFFriendly()
		{
			add_filter('wpseo_pre_analysis_post_content',function ( $content ) {
				global $post;
				$pid = $post->ID;
				$custom = get_post_custom($pid);
				unset($custom['_yoast_wpseo_focuskw']); // Don't count the keyword in the Yoast field!
				$custom_content = '';
				foreach( $custom as $key => $value ) {
					if( substr( $key, 0, 1 ) != '_' && substr( $value[0], -1) != '}' && !is_array($value[0]) && !empty($value[0])) {
						$custom_content .= $value[0] . ' ';
					}
				}
				$content = $content . ' ' . $custom_content;
				return $content;
				remove_filter('wpseo_pre_analysis_post_content', 'add_custom_to_yoast'); // don't let WP execute this twice
			});
		}

		/**
		 * Afficher le bloc Yoast en bas
		 */
		public static function goBottom()
		{
			//Afficher le bloc Yoast en bas
			add_filter( 'wpseo_metabox_prio',function () {
				return 'low';
			});
		}
	}
?>