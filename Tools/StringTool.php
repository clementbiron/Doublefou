<?php
	
	namespace Doublefou\Tools;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * StringTool
	 * @author Clément Biron
	 */
	class StringTool extends Singleton
	{
		/**
		 * Cut a string with max words allowed
		 * @param string $pStr
		 * @param number $pMaxWords
		 * @param string $pEnd
		 */
		public static function cutByWords($pStr,$pMaxWords,$pEnd = '')
		{
			$strReturn = '';
			$words = self::stringToArray($pStr);
			$nbWords = count($words);
			if($nbWords > $pMaxWords){
				for($i=0;$i<$pMaxWords;$i++){
					$strReturn .= $words[$i]." ";
				}
				$strReturn .= $pEnd;
				return $strReturn;
			}else{
				return $pStr;
			}
		}
		
		/*
		 * Couper une chaîne à un nombre de caractère déterminé
		 * @param string $pStr
		 * @param number $pMaxLength
		 * @param string $pEnd
		 * @return string 
		 */
		public static function cutByLength($pStr,$pMaxLength,$pEnd = '')
		{	
			return (strlen($pStr) > $pMaxLength) ? rtrim(substr($pStr,0,$pMaxLength)).$pEnd : $pStr;
		}
		
		/**
		 * Get the word number from a string
		 * @param string
		 * @return number
		 */
		public static function numberOfWords($pStr)
		{
			return count(self::stringToArray($pStr));
		}
		
		/**
		 * Put each word in table cell
		 * @param string $pStr
		 * @return array
		 */
		public static function stringToArray($pStr)
		{
			return explode(" ",$pStr);
        }
        
        /**
         * PHP strip_tags doesn’t remove the content inside the removed tag. Here is how to remove the content too
         * @param string $text
         * @param string $tags
         * @param boolean $invert
		 * @return string
         */
        public static function stripTagsContent($text, $tags = '', $invert = FALSE)
        {
            preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
            $tags = array_unique($tags[1]);
            if(is_array($tags) AND count($tags) > 0){
                if($invert == FALSE){
                    return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
                }
                else{
                    return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
                }
            }elseif($invert == FALSE){
                return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
            }
            return $text;
        }
	}

?>