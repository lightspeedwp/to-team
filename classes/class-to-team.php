<?php
/**
 * TO_Team
 *
 * @package   TO_Team
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link      
 * @copyright {year} LightSpeedDevelopment
 */
if (!class_exists( 'TO_Team' ) ) {

	/**
	 * Main plugin class.
	 *
	 * @package TO_Team
	 * @author  {your-name}
	 */	
	class TO_Team {
		
		/** @var string */
		public $plugin_slug = 'to-team';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->options = get_option('_lsx_lsx-settings',false);
			if(false !== $this->options && isset($this->options[$this->plugin_slug]) && !empty($this->options[$this->plugin_slug])){
				$this->options = $this->options[$this->plugin_slug];
			}
			else{
				$this->options = false;
			}			
			require_once(TO_TEAM_PATH . '/classes/class-to-team-admin.php');
			require_once(TO_TEAM_PATH . '/classes/class-to-team-frontend.php');
			require_once(TO_TEAM_PATH . '/includes/template-tags.php');

			add_action( 'init', array( $this, 'register_post_types' ) );
		}

		/**
		 * Adds our post types to an array via a filter
		 */
		public function plugin_path($path,$post_type){
			if(false !== $this->post_types && array_key_exists($post_type,$this->post_types)){
				$path = TO_TEAM_PATH;
			}
			return $path;
		}					

		/**
		 * Register the landing pages post type.
		 *
		 *
		 * @return    null
		 */
		public function register_post_types() {
		
			$labels = array(
			    'name'               => _x( 'Team', 'tour-operator' ),
			    'singular_name'      => _x( 'Team Member', 'tour-operator' ),
			    'add_new'            => _x( 'Add New', 'tour-operator' ),
			    'add_new_item'       => _x( 'Add New Team Member', 'tour-operator' ),
			    'edit_item'          => _x( 'Edit', 'tour-operator' ),
			    'new_item'           => _x( 'New', 'tour-operator' ),
			    'all_items'          => _x( 'Team Members', 'tour-operator' ),
			    'view_item'          => _x( 'View', 'tour-operator' ),
			    'search_items'       => _x( 'Search the Team', 'tour-operator' ),
			    'not_found'          => _x( 'No team members found', 'tour-operator' ),
			    'not_found_in_trash' => _x( 'No team members found in Trash', 'tour-operator' ),
			    'parent_item_colon'  => '',
			    'menu_name'          => _x( 'Team', 'tour-operator' ),
				'featured_image'	=> _x( 'Profile Picture', 'tour-operator' ),
				'set_featured_image'	=> _x( 'Set Profile Picture', 'tour-operator' ),
				'remove_featured_image'	=> _x( 'Remove profile picture', 'tour-operator' ),
				'use_featured_image'	=> _x( 'Use as profile picture', 'tour-operator' ),								
			);

			$args = array(
	            'menu_icon'          =>'dashicons-id-alt',
			    'labels'             => $labels,
			    'public'             => true,
			    'publicly_queryable' => true,
			    'show_ui'            => true,
			    'show_in_menu'       => true,
			    'query_var'          => true,
			    'rewrite'            => array('slug'=>'team'),
			    'capability_type'    => 'page',
			    'has_archive'        => 'team',
			    'hierarchical'       => false,
	            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' )
			);

			register_post_type( 'team', $args );	
			
		}		
	}
	new TO_Team();
}