<?php

	namespace Doublefou\Helper;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Login
	 * @author Clément Biron
	 */
	Class Login extends Singleton
	{

		/**
		 * Savoir si on est sur la page de login
		 * @return boolean 
		 */
		public static function isLoginPage()
		{
			return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
		}
	}

?>