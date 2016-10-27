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
		 * The post types the plugin registers
		 */
		public $post_types = false;	

		/**
		 * The singular post types the plugin registers
		 */
		public $post_types_singular = false;	

		/**
		 * An array of the post types slugs plugin registers
		 */
		public $post_type_slugs = false;		

		/**
		 * Constructor
		 */
		public function __construct() {
			//Set the variables
			$this->set_vars();
			add_action('init',array($this,'load_plugin_textdomain'));

			if(false !== $this->post_types){
				add_filter( 'to_framework_post_types', array( $this, 'post_types_filter') );
				add_filter( 'to_post_types', array( $this, 'post_types_filter') );
				add_filter( 'to_post_types_singular', array( $this, 'post_types_singular_filter') );
				add_filter('to_settings_path',array( $this, 'plugin_path'),10,2);
			}

			require_once(TO_TEAM_PATH . '/classes/class-to-team-admin.php');
			require_once(TO_TEAM_PATH . '/classes/class-to-team-frontend.php');
			require_once(TO_TEAM_PATH . '/includes/template-tags.php');
		}
	
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'to-team', FALSE, basename( TO_TEAM_PATH ) . '/languages');
		}

		/**
		 * Sets the plugins variables
		 */
		public function set_vars() {
			$this->post_types = array(
				'team' => __( 'Team','to-team' )
			);
			$this->post_types_singular = array(
				'team' => __( 'Team Member','to-team' )
			);
			$this->post_type_slugs = array_keys($this->post_types);			
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
		 * Adds our post types to an array via a filter
		 */
		public function post_types_slugs_filter($post_types){
			if(is_array($post_types)){
				$post_types = array_merge($post_types,$this->post_type_slugs);
			}else{
				$post_types = $this->post_type_slugs;
			}
			return $post_types;
		}

		/**
		 * Adds our post types to an array via a filter
		 */
		public function post_types_filter($post_types){
			if(is_array($post_types) && is_array($this->post_types)){
				$post_types = array_merge($post_types,$this->post_types);
			}elseif(is_array($this->post_types)){
				$post_types = $this->post_types;
			}
			return $post_types;
		}	

		/**
		 * Adds our post types to an array via a filter
		 */
		public function post_types_singular_filter($post_types_singular){
			if(is_array($post_types_singular) && is_array($this->post_types_singular)){
				$post_types_singular = array_merge($post_types_singular,$this->post_types_singular);
			}elseif(is_array($this->post_types_singular)){
				$post_types_singular = $this->post_types_singular;
			}
			return $post_types_singular;
		}

	}
	new TO_Team();
}