<?php
	
	namespace Doublefou\Helper;

	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit;

	/**
	 * Post
	 * @author Clément Biron
	 */
	Class Post extends Singleton
	{
		
		/**
		 * Récupérer le permalink d'un post avec son slug
		 *
		 * @param string $pSlug Slug
		 *
		 * @return string|boolean
		 */
		public static function getPostLinkBySlug($pSlug)
		{
			$query = self::getPostBySlug($pSlug);
			return get_permalink($query->post->ID);
		}
		
		/**
		 * Récupérer un post par son slug
		 *
		 * @param string $pSlug
		 *
		 * @return object
		 */
		public static function getPostBySlug($pSlug)
		{
			return new WP_Query("name=$pSlug");
		}
		
		/**
		 * Récupérer un post par son titre
		 *
		 * @param string $pTitle
		 *
		 * @return object|null
		 */
		public static function getPostByTitle($pTitle, $output = OBJECT)
		{
			global $wpdb;
			$post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $page_title), $output);

			if ($post)
				return $post;

			return null;
		}
		
		/**
		 * Récupérer le slug d'un post dans la loop
		 * @return string
		 */
		public static function getPostSlug()
		{
			return basename(get_permalink());
		}
		
		/**
		 * Déterminer si il sagit d'un custom post type
		 *
		 * @param string $type
		 *
		 * @return boolean
		 */
		public static function isCustomPostType($type = '')
		{
			global $post;
			$post_type = get_post_type($post);
			$types     = ["post", "page", "revision", "attachment"];

			if ($type == '' && !in_array($type, $types)) {
				return true;
			} else if ($type == $post_type) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Remove <p> tag from images in the_content()
		 */
		public static function filterPTagsOnImages()
		{
			function dfwp_filter_ptags_on_images($content)
			{
				return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
				add_filter('the_content', 'dfwp_filter_ptags_on_images');
			}
		}
	}

	?>