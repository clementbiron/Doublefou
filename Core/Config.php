<?php
	
	namespace Doublefou\Core;
	use Doublefou\Core\Singleton;
	use Doublefou\Core\Debug as Debug;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Class de configuration
	 * @author Clément Biron
	 */
	class Config extends Singleton
	{
		/**
		 * Propriétés
		 * @var array
		 */
		private static $_properties = Array();

		/**
		 * Fichiers inclus
		 * @var array
		 */
		private static $_included = Array();
		
		private static $_debug;

		/**
		 * Parametrer le mode debug
		 * @param integer $pDebug
		 */
		public static function setDebug()
		{
			self::$_debug = WP_DEBUG;
			
			if(WP_DEBUG === true){				
				Debug::showErrors();
			}
			else{
				Debug::hideErrors();
			}
		}
		/**
		 * Récupérer le niveau de debug (0|1)
		 * @return integer
		 */
		public static function getDebug()
		{
			return self::$_debug;
		}

		/**
		 * Configurer une propriété
		 * @param string $pPropertie
		 * @param string $pValue
		 */
		public static function set($pPropertie,$pValue)
		{
			self::$_properties[$pPropertie] = $pValue;
		}
		
		/**
		 * Récupérer une propriété
		 * @param string $pPropertie
		 */
		public static function get($pPropertie)
		{
			if(isset(self::$_properties[$pPropertie])){
				return self::$_properties[$pPropertie];
			}else{
				throw new \Exception($pPropertie .' is not defined in '.get_class(self));
			}
		}
		
		/**
		 * Retourne toutes les propriétées
		 * @return array 
		 */
		public static function getAll()
		{
			return self::$_properties;
		}

		/**
		 * Include files from an array and memorized which are included
		 * @param array
		 */
		public static function loadClass($pArrayToInclude)
		{
			if(is_array($pArrayToInclude)){
				foreach($pArrayToInclude as $toInclude){
					if(!in_array($toInclude,self::$_included)){
						if(file_exists($toInclude)){
							require_once($toInclude);
							array_push(self::$_included,$toInclude);
						}else{
							throw new \Exception($toInclude." file not exist !");
						}
					}
				}
			}else{
				throw new \Exception("Param should be an array");
			}
		}

		/**
		 * Get the included files
		 * @return array
		 */
		public static function getIncluded()
		{
			return self::$_included;
		}
		
		/**
		 * Afficher les constantes WordPress disponibles dans la console de debug
		 */
		public static function getWpConstants()
		{
			Debug::add(get_defined_constants());
		}
	}
?>