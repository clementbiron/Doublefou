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
		 * Initialisation de la config
		 */
        public static function init(){
            (WP_DEBUG === true) ? Debug::showErrors() : Debug::hideErrors();
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
	}
?>