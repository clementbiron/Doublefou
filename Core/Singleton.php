<?php
	
	namespace Doublefou\Core;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Singleton
	 * @author Clément Biron
	 */
	abstract class Singleton 
	{
		private static $instances = array();
		
		final private function __construct()
		{
			if (isset(self::$instances[get_called_class()])){
				throw new \Exception("An instance of ".get_called_class()." already exists.");
			}
			static::initialize();
		}
		
		protected function initialize(){}
		
		final public static function getInstance()
		{
			$class = get_called_class();
			if (!isset(self::$instances[$class])){
				self::$instances[$class] = new static();
			}
			return self::$instances[$class];
		}
		
		final private function __clone(){}
	}
?>