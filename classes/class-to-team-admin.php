<?php
/**
 * TO_Team_Admin
 *
 * @package   TO_Team_Admin
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link      
 * @copyright {year} LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package TO_Team_Admin
 * @author  {your-name}
 */

class TO_Team_Admin extends TO_Team{	

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('init',array($this,'init'),20);
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'register_metaboxes') );
	}

	/**
	 * Output the form field for this metadata when adding a new term
	 *
	 * @since 0.1.0
	 */
	public function init() {
		if(function_exists('to_get_taxonomies')){
			$this->taxonomies = array_keys(to_get_taxonomies());
		}
		add_filter('to_taxonomy_widget_taxonomies', array( $this, 'widget_taxonomies' ),10,1 );

		if(false !== $this->taxonomies){
			add_action( 'create_term', array( $this, 'save_meta' ), 10, 2 );
			add_action( 'edit_term',   array( $this, 'save_meta' ), 10, 2 );
			foreach($this->taxonomies as $taxonomy){
				add_action( "{$taxonomy}_edit_form_fields", array( $this, 'add_expert_form_field' ),3,1 );
			}			
		}	
	}	

	/**
	 * Register the landing pages post type.
	 *
	 *
	 * @return    null
	 */
	public function register_post_types() {
	
		$labels = array(
		    'name'               => _x( 'Team', 'to-team' ),
		    'singular_name'      => _x( 'Team Member', 'to-team' ),
		    'add_new'            => _x( 'Add New', 'to-team' ),
		    'add_new_item'       => _x( 'Add New Team Member', 'to-team' ),
		    'edit_item'          => _x( 'Edit', 'to-team' ),
		    'new_item'           => _x( 'New', 'to-team' ),
		    'all_items'          => _x( 'Team', 'to-team' ),
		    'view_item'          => _x( 'View', 'to-team' ),
		    'search_items'       => _x( 'Search the Team', 'to-team' ),
		    'not_found'          => _x( 'No team member found', 'to-team' ),
		    'not_found_in_trash' => _x( 'No team member found in Trash', 'to-team' ),
		    'parent_item_colon'  => '',
		    'menu_name'          => _x( 'Team', 'to-team' ),
			'featured_image'	=> _x( 'Profile Picture', 'to-team' ),
			'set_featured_image'	=> _x( 'Set Profile Picture', 'to-team' ),
			'remove_featured_image'	=> _x( 'Remove profile picture', 'to-team' ),
			'use_featured_image'	=> _x( 'Use as profile picture', 'to-team' ),								
		);

		$args = array(
            'menu_icon'          =>'dashicons-id-alt',
		    'labels'             => $labels,
		    'public'             => true,
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => 'tour-operator',
			'menu_position'      => 40,
		    'query_var'          => true,
		    'rewrite'            => array('slug'=>'team'),
		    'capability_type'    => 'post',
		    'has_archive'        => 'team',
		    'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
		);

		register_post_type( 'team', $args );	
		
	}		

	/**
	 * Registers the Team metaboxes
	 * @author  {your-name}
	 */
	function register_metaboxes( array $meta_boxes ) {
		
		$fields[] = array( 'id' => 'general_title',  'name' => 'General', 'type' => 'title' );
		$fields[] = array( 'id' => 'featured',  'name' => 'Featured', 'type' => 'checkbox' );
		if(!class_exists('TO_Banners')){
			$fields[] = array( 'id' => 'tagline',  'name' => 'Tagline', 'type' => 'text' );
		}
		$fields[] = array( 'id' => 'role', 'name' => 'Role', 'type' => 'text' );
		$fields[] = array( 'id' => 'contact_title',  'name' => 'Contact', 'type' => 'title' );
		$fields[] = array( 'id' => 'contact_email', 'name' => 'Email', 'type' => 'text' );
		$fields[] = array( 'id' => 'contact_number', 'name' => 'Number (international format)', 'type' => 'text' );
		$fields[] = array( 'id' => 'skype', 'name' => 'Skype', 'type' => 'text' );
		$fields[] = array( 'id' => 'social_title',  'name' => 'Social Profiles', 'type' => 'title' );
		$fields[] = array( 'id' => 'facebook', 'name' => 'Facebook', 'type' => 'text' );
		$fields[] = array( 'id' => 'twitter', 'name' => 'Twitter', 'type' => 'text' );
		$fields[] = array( 'id' => 'googleplus', 'name' => 'Google Plus', 'type' => 'text' );
		$fields[] = array( 'id' => 'linkedin', 'name' => 'LinkedIn', 'type' => 'text' );
		$fields[] = array( 'id' => 'pinterest', 'name' => 'Pinterest', 'type' => 'text' );
		$fields[] = array( 'id' => 'gallery_title',  'name' => 'Gallery', 'type' => 'title' );

		if(class_exists('Envira_Gallery')){ 
			$fields[] = array( 'id' => 'envira_to_team', 'name' => 'Gallery from  Envira Gallery plugin', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'envira','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		}else{
			$fields[] = array( 'id' => 'gallery', 'name' => 'Gallery images', 'type' => 'image', 'repeatable' => true, 'show_size' => false );
		}
		
		if(class_exists('TO_Field_Pattern')){ $fields = array_merge($fields,TO_Field_Pattern::videos()); }		
	
		/*$fields[] = array( 'id' => 'accommodation_title',  'name' => 'Accommodation', 'type' => 'title' );
		$fields[] = array( 'id' => 'accommodation_to_team', 'name' => 'Accommodation', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'accommodation','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		$fields[] = array( 'id' => 'activity_title',  'name' => 'Activities', 'type' => 'title' );
		$fields[] = array( 'id' => 'activity_to_team', 'name' => 'Activity', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'activity','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true );
		$fields[] = array( 'id' => 'destinations_title',  'name' => 'Destinations', 'type' => 'title' );
		$fields[] = array( 'id' => 'destination_to_team', 'name' => 'Destinations', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'destination','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		$fields[] = array( 'id' => 'review_title',  'name' => 'Reviews', 'type' => 'title' );
		$fields[] = array( 'id' => 'review_to_team', 'name' => 'Reviews', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'review','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		$fields[] = array( 'id' => 'specials_title',  'name' => 'Specials', 'type' => 'title' );
		$fields[] = array( 'id' => 'special_to_team', 'name' => 'Specials', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'special','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		$fields[] = array( 'id' => 'tours_title',  'name' => 'Tours', 'type' => 'title' );
		$fields[] = array( 'id' => 'tour_to_team', 'name' => 'Tours', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'tour','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );
		$fields[] = array( 'id' => 'vehicle_title',  'name' => 'Vehicles', 'type' => 'title' );
		$fields[] = array( 'id' => 'vehicle_to_team', 'name' => 'Vehicles', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'vehicles','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true );*/
		
		$meta_boxes[] = array(
				'title' => 'LSX Tour Operators',
				'pages' => 'team',
				'fields' => $fields
		);		
		
		return $meta_boxes;
	
	}	

	/**
	 * Output the form field for this metadata when adding a new term
	 *
	 * @since 0.1.0
	 */
	public function add_expert_form_field( $term = false ) {
		if ( is_object( $term ) ) {
			$value = get_term_meta( $term->term_id, 'expert', true );
		} else {
			$value = false;
		}

		$experts = get_posts(
			array(
				'post_type' => 'team',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			)
		);
		?>

		<tr class="form-field form-required term-expert-wrap">
			<th scope="row">
				<label for="expert"><?php _e( 'Expert','to-team' ) ?></label>
			</th>

			<td>
				<select name="expert" id="expert" aria-required="true">
					<option value=""><?php _e( 'None','to-team' ) ?></option>

					<?php
						foreach ( $experts as $expert ) {
							echo '<option value="'. $expert->ID .'"'. selected( $value, $expert->ID, FALSE ) .'>'. $expert->post_title .'</option>';
						}
					?>
				</select>

				<?php wp_nonce_field( 'to_team_save_term_expert', 'to_team_term_expert_nonce' ); ?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Saves the Taxnomy term banner image
	 *
	 * @since 0.1.0
	 *
	 * @param  int     $term_id
	 * @param  string  $taxonomy
	 */
	public function save_meta( $term_id = 0, $taxonomy = '' ) {	
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['expert'] ) ) {
			return;
		}

		if ( check_admin_referer( 'to_team_save_term_expert', 'to_team_term_expert_nonce' ) ) {
			$meta = ! empty( sanitize_text_field(wp_unslash($_POST[ 'expert' ])) ) ? sanitize_text_field(wp_unslash($_POST[ 'expert' ]))	: '';
			if ( empty( $meta ) ) {
				delete_term_meta( $term_id, 'expert' );
			} else {
				update_term_meta( $term_id, 'expert', $meta );
			}
		}
	}			
}
new TO_Team_Admin();