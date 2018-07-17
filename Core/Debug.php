<?php
	
	namespace Doublefou\Core;
	use Doublefou\Core\Singleton;
	use Doublefou\Core\Config;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	* Outil de debug
	* @author Clément Biron
	* @todo finir l'affichag en console : passage en json
	*/
	class Debug extends Singleton
	{
		/**
		 * Error handler
		 * @var null
		 */
		private static $_errorHandler = null;

		/**
		 * Simple debug
		 */
		public static function add($pToDebug)
		{
			echo '<pre>';
			print_r($pToDebug);
			echo '</pre>';
		}

		/**
		 * Afficher la valeur d'une variable dans la console de debug
		 * @param * $pToDebug
		 */
		public static function addToConsole($pToDebug)
		{
			//On JSON encode ce qu'il faut afficher dans la console
			$toDebugEncode = json_encode($pToDebug, JSON_HEX_APOS | JSON_HEX_QUOT );

			//On prépare le console log que l'on va injecter
			$output = '<script type="text/javascript">console.log(\'DFWP DEBUG\','.$toDebugEncode.');</script>';
			
			//Le nom du hook en fonction si on est en admin ou en front
			$hookName = (is_admin()) ? 'admin_footer' : 'wp_footer';

			//On affiche le bout de script en footer
			add_action($hookName, function() use ($output){
				echo $output;
			});		
		}
		
		/**
		 * Initialiser l'affichage des erreurs php
		 */
		public static function showErrors()
		{
			//On affiche les erreurs
			error_reporting(E_ALL);
			ini_set('display_errors', 1);

			//Pour le front uniquement
			if(!is_admin()){

				//Si on a pas configuré le error handler
				self::initErrorHandler();

				//On active
				self::$_errorHandler->register();
			}
		}

		/**
		 * Activer le gestionnaire d'erreurs
		 */
		private static function initErrorHandler()
		{
			if(self::$_errorHandler == null){
				self::$_errorHandler = new \Whoops\Run;
				self::$_errorHandler->pushHandler(new \Whoops\Handler\PrettyPageHandler);
			}
		}

		/**
		 * Cacher les erreurs php
		 */
		public static function hideErrors()
		{
			//On masque les erreurs
			error_reporting(0);
			ini_set("display_errors",0);

			//Si on a un error handler configuré
			if(self::$_errorHandler !== null){

				//On le turn off
				self::$_errorHandler->unregister();
			}
		}

		/** 
		 * Retrouver le nom d'une variable
		 * @param unknown_type $var
		 */
		public static function varName($var)
		{
			foreach($GLOBALS as $var_name => $value) {
				if ($value === $var) {
					return $var_name;
				}
			}	
			return false;
		}

		/**
		 * Afficher des infos de debug de WordPress dans la console
		 */
		public static function addWpInfoToConsole()
		{	
			Debug::addToConsole(self::getWPInfo());
		}

		/**
		 * Récupérer des infos générales sur WP
		 */
		public static function getWPInfo()
		{
			return array(
				'Permalink structure' => get_option('permalink_structure'),
				'ABSPATH' => ABSPATH,
				'Number of database queries' => get_num_queries(),
				'Memory (mb)' => round( memory_get_peak_usage()/( 1024*1024 ), 3 ),
				'Queries time (seconds)' => timer_stop(0)
			);
		}
	}

?>