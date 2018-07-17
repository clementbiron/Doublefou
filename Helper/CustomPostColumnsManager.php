<?php

	namespace Doublefou\Helper;
	use Doublefou\Core\Debug;
	use \Ds\Map;
	use \Exception;

	//Exit si accès direct
    if (!defined('ABSPATH')) exit; 
	
	/**
	 * CustomPostColumnsManager : gestion des colonnes pour les CPT
	 */
    class CustomPostColumnsManager
	{
		private $posttype;

		//Liste des noms de colonnes réservés par WP
		private $predefinedColumnsNames = Array(
			'cb',
			'title',
			'author',
			'categories',
			'tags',
			'comments',
			'date'
		);

		private $filterNames;

		/**
		 * constructor
		 *
		 * @param string $posttype
		 */
		public function __construct(string $posttype)
		{
			//On stocke le type de CPT
			$this->posttype = $posttype;

			//On stocke les noms des hooks pour ce CPT
			$this->filterNames = new Map();
			$this->filterNames->put('manage_{$post_type}_posts_columns', 'manage_'.$this->posttype.'_posts_columns');
			$this->filterNames->put('manage_{$post_type}_posts_custom_column', 'manage_'.$this->posttype.'_posts_custom_column');
			$this->filterNames->put('manage_edit-{$post_type}_sortable_columns', 'manage_edit-'.$this->posttype.'_sortable_columns');
			$this->filterNames->put('manage_edit-{$post_type}_columns', 'manage_edit-'.$this->posttype.'_columns');
		}
		
		/**
		 * addACFColumn : ajouter une colonne ACF
		 *
		 * @param string $name
		 * @param string $type
		 * @param string $acffield
		 * @param bool $filterables
		 * @param string $colwidth
		 * @return void
		 * @todo : implémenter la tri par nom de taxonomy, pour le moment cela tri par l'ID du champ
		 */
		public function addACFColumn(string $name, string $type = 'default', string $acffield, bool $filterable = false ,string $colwidth = '')
		{
			//Uniq sanitize name for GET
			$safename = sanitize_title($name).'-'.uniqid();

			//On ne peut pas ajouter une colonne avec un nom réservé
			if(in_array($safename,$this->predefinedColumnsNames)){
				throw new Exception("Le nom de la colonne $safename est réservé");	
			}

			//On ajoute la colonne
			add_filter($this->filterNames->get('manage_{$post_type}_posts_columns'), function($columns) use ($safename, $name)
			{
				$columns[$safename] = $name;
				return $columns;
			});

			//On ajoute le contenu à la colonne
			add_action($this->filterNames->get('manage_{$post_type}_posts_custom_column'), function($column) use ($safename, $name, $type, $acffield)
			{
				global $post;
				if($column == $safename) 
				{
					switch ($type) {
						case 'taxonomy':
							$taxs = get_field($acffield, $post->ID);							
							if(is_object($taxs)){
								echo($taxs->name);								
							}else if(is_array($taxs)){
								$i = 0;
								$l = count($taxs);
								foreach($taxs as $tax){
									echo($tax->name);
									echo (($i != ($l - 1)) ? ', ' : '');
									$i++;
								}
							}
							break;
						case 'image':
							$img = get_field($acffield, $post->ID);							
							if($img){								
								if(is_array($img)){									
									$src = $img['url'];
								}else{								
									$imgAttachement = wp_get_attachment_image_src($img,'full');
									if($imgAttachement != false){
										$src = $imgAttachement[0];
									}
								}
								if(isset($src)){
									echo '<img src="'.$src.'" style="max-width:100%;" />';
								}
							}
                            break;
                        case 'date':
							$date = get_field($acffield, $post->ID, false, false);							
							if($date){								
                                $date = strtotime($date);
                                echo date_i18n('d M Y',$date);
							}
							break;
						case 'select':
						case 'default':
						default:
							$field = get_field($acffield, $post->ID);
							if($field){
								echo $field;
							}
							break;
					}
				}
			});

			//Si c'est sortable
			if($filterable === true)
			{
				//On configure la colonne pour qu'elle soit sortable
				add_filter($this->filterNames->get('manage_edit-{$post_type}_sortable_columns'), function ( $columns )  use ($safename, $name)
				{
					$columns[$safename] = $safename;
					return $columns;
				});

				//On va faire gérer la requête pour filtrer 
				add_action( 'pre_get_posts', function($query) use($safename, $acffield)
				{
					//Si on est pas sur l'admin et la main query on return
					if (!is_admin() || !$query->is_main_query()) { return; }

					//Si on bien demandé un order by
					$orderby = $query->get( 'orderby' );						
					if(isset( $orderby ) )
					{							
						if($orderby == $safename) 
						{
							$query->set('orderby', 'meta_value');
							$query->set('meta_query',  array(
								'relation' => 'OR',
								array( 
									'key'=> $acffield,
									'compare' => 'EXISTS'           
								),

								//On récupère aussi les pots dans la key n'existe pas !
								array( 
									'key'=> $acffield,
									'compare' => 'NOT EXISTS'           
								)
							));
						}
					}

					return $query;
				});
			}

			//Si on doit paramétrer la largeur de la colonne
			if($colwidth != '')
			{
				//On ajoute le style qui va bien
				add_action('admin_head', function () use ($safename, $colwidth)
				{
					echo '<style>
						th#'.$safename.'{
							width: '.$colwidth.';
						}
					</style>';
				});
			}
		}

		/**
		 * removeColumn : supprimer une colonne
		 *
		 * @param string $name
		 * @return void
		 */
		public function removeColumn(string $name)
		{
			//Sanitize name
			$name = sanitize_title($name);

			//On supprime une colonne
			add_filter($this->filterNames->get('manage_edit-{$post_type}_columns'), function($columns) use ($name)
			{
				unset($columns[$name]);
				return $columns;
			});
		}
	}

?>