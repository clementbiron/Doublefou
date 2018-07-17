<?php
	
	namespace Doublefou\Tools;
	use Doublefou\Core\Singleton;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * SecureTool
	 * @author Clément Biron
	 */
	class SecureTool extends Singleton
	{
		/**
		 * Know if is a secure string for GET param
		 * @param string $strToSecure
		 * @return boolean
		 */
		public static function isSecureForGet($pStrToSecure)
		{
			if(is_string($pStrToSecure)){
				if(preg_match('#[A-Z0-9\-_\+]#i',$pStrToSecure)){
					return true;
				}else{
					return false;
				}
			}
		}
			
		/**
		 * Know if is a valid email string
		 * @param string $pEmail
		 * @return boolean
		 */
		public static function isValidEmail($pEmail)
		{
			if(!filter_var($pEmail, FILTER_VALIDATE_EMAIL)){
				return false;
			}else{
				return true;
			}
		}
		
		/**
		 * Know if is a valid url string
		 * @param string $pUrl
		 * @return boolean
		 */
		public static function isValidUrl($pUrl)
		{
			if(!filter_var($pUrl, FILTER_VALIDATE_URL)){
				return false;
			}else{
				return true;
			}
		}
		
		/**
		 * Know if is a valid ip
		 * @param string
		 * @return boolean
		 */
		public static function isValidIp($pIp)
		{
			if(!filter_var($pIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false){
				return false;
			}else{
				return true;
			}
		}
	}

?>