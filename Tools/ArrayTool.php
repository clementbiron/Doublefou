<?php
	
	namespace Doublefou\Tools;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * ArrayTool
	 * @author Clément Biron
	 */
	class ArrayTool extends Singleton
	{
		/**
		 * Insérer un élement dans un tableau une position donnée
		 * @param  array $pArray Le tableau source
		 * @param  integer $pPos   La position dans le tableau (démarre à 0)
		 * @param  [type] $pValue L'élement à ajouter
		 * @return array Le tableau final
		 */
		public static function insertInPos($pArray, $pPos, $pValue)
		{
			return array_merge(array_slice($pArray, 0 , $pPos), array($pValue), array_slice($pArray,  $pPos));
		}
	}

?>