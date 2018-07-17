<?php
	
	namespace Doublefou\Helper;

	//Exit si accès direct
	if (!defined('ABSPATH')) exit; 

	/**
	 * Ajouter la gestion d'un shortcode vidéo html5
	 * @author Clément Biron
	 */
	Class VideoShortCode 
	{
		/**
		 * Constructeur
		 * @uses [video src='' poster ='' width='' height='']
		 */
		public function __construct()
		{
			//Extract code function
			function extractCode($params = array())
			{
				//Extract params
				extract(shortcode_atts(array(  
					'src' => '',  
					'poster' => '',  
					'width' => '645',
					'height' => '359'
				), $params)); 
				
				//Retourner toussa
				if(!empty($src)){
					return '<video width="'.$width.'" height="'.$height.'" poster="'.$poster.'"><source src="'.$src.'" /></video>';	
				}else{
					return '';
				}
			}
			
			//Add short code
			add_shortcode('video','extractCode');  			
		}
	}
?>