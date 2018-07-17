<?php
	
	namespace Doublefou\Helper;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Ping
	 * @author Clément Biron
	 */
	Class Ping extends Singleton
	{

		/**
		 * Disable self-trackbacking
		 */
		public static function disableSelfTrackbacking()
		{
			add_action('pre_ping','rynonuke_self_pings');
			function rynonuke_self_pings( &$links ) {
			    foreach ( $links as $l => $link )
			        if ( 0 === strpos( $link, get_option( 'home' ) ) )
			            unset($links[$l]);
			}
		}
	}

?>

