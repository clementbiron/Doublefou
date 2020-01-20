<?php
	
	namespace Doublefou\Helper;

	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit;

	/**
	 * Theme
	 * @author Clément Biron
	 */
	Class Theme extends Singleton
	{
		/**
		 * Ajouter la gestion des miniatures de posts
		 */
		public static function addPostThumbnails()
		{
			if (function_exists('add_theme_support')) {
				add_theme_support('post-thumbnails');
			}
		}

		/**
		 * Nétoyer le header html
		 */
		public static function cleanHeader()
		{
			remove_action('wp_head', 'rsd_link');
			remove_action('wp_head', 'wlwmanifest_link');
			remove_action('wp_head', 'index_rel_link');
			remove_action('wp_head', 'wp_generator');
			remove_action('wp_head', 'rest_output_link_wp_head');
			remove_action('wp_head', 'wp_oembed_add_discovery_links');
			remove_action('wp_head', 'wp_resource_hints', 2 );
			remove_action('wp_head', 'feed_links_extra', 3);
			remove_action('wp_head', 'feed_links', 2 );	
		}

		/**
		 * Désactiver la barre d'administration sur le front pour tous les users
		 *
		 * @param  string $pNotForRole Rôle d'utilisateur en exception
		 */
		public static function hideAdminBar($pNotForRole = '')
		{
			if (!empty($pNotForRole)) {
				if (!current_user_can($pNotForRole)) {
					add_filter('show_admin_bar', function () {
						return false;
					});
				}
			} else {
				add_filter('show_admin_bar', function () {
					return false;
				});
			}
		}

		/**
		 * Retourne la version du thème extraite depuis l'en-tête de la feuille de style
		 * @todo A vérifier
		 * @return string|null
		 */
		public static function getThemeVersion()
		{
			if (function_exists('wp_get_theme'))
				$current_theme = wp_get_theme();
			elseif (function_exists('get_theme_data'))
				$current_theme = get_theme_data(get_stylesheet_directory_uri() . '/style.css');
			else
				return null;
			
			return ($current_theme && isset($current_theme['Version'])) ? $current_theme['Version'] : null;
		}

		/**
		 * Supprimer le lien vers le flux rss dans le header
		 */
		public static function removeHeaderRssLink()
		{
			remove_action('wp_head', 'feed_links_extra', 3);
		}

		/**
		 * Supprimer le lien court dans le header
		 */
		public static function removeHeaderShortLink()
		{
			remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		}

		/**
		 * Buffer pour la fonction language_attributes()
		 *
		 * @param  string $doctype
		 *
		 * @return string
		 */
		public static function getLanguageAttributes($doctype = 'html')
		{
			ob_start();
			language_attributes($doctype);
			return ob_get_clean();
		}

		/**
		 * Désactiver les EMOJI
		 */
		public static function disableEmoji()
		{
			add_action('init', function () {
				remove_action('admin_print_styles', 'print_emoji_styles');
				remove_action('wp_head', 'print_emoji_detection_script', 7);
				remove_action('admin_print_scripts', 'print_emoji_detection_script');
				remove_action('wp_print_styles', 'print_emoji_styles');
				remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
				remove_filter('the_content_feed', 'wp_staticize_emoji');
				remove_filter('comment_text_rss', 'wp_staticize_emoji');
				add_filter('tiny_mce_plugins', function ($plugins) {
					if (is_array($plugins)) {
						return array_diff($plugins, ['wpemoji']);
					} else {
						return [];
					}
				});
			});
		}
	}

?>