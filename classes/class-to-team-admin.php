<?php
/**
 * LSX_TO_Team_Admin
 *
 * @package   LSX_TO_Team_Admin
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link      
 * @copyright {year} LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Team_Admin
 * @author  {your-name}
 */

class LSX_TO_Team_Admin extends LSX_TO_Team{	

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();
		add_action('init',array($this,'init'),20);
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'register_metaboxes') );

		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields') );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields') );
		add_filter( 'lsx_to_special_custom_fields', array( $this, 'custom_fields') );
		add_filter( 'lsx_to_review_custom_fields', array( $this, 'custom_fields') );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields') );
	}

	/**
	 * Output the form field for this metadata when adding a new term
	 *
	 * @since 0.1.0
	 */
	public function init() {
		if(function_exists('lsx_to_get_taxonomies')){
			$this->taxonomies = array_keys(lsx_to_get_taxonomies());
		}
		add_filter('lsx_to_taxonomy_widget_taxonomies', array( $this, 'widget_taxonomies' ),10,1 );

		if(false !== $this->taxonomies){
			add_action( 'create_term', array( $this, 'save_meta' ), 10, 2 );
			add_action( 'edit_term',   array( $this, 'save_meta' ), 10, 2 );
			foreach($this->taxonomies as $taxonomy){
				add_action( "{$taxonomy}_edit_form_fields", array( $this, 'add_expert_form_field' ),3,1 );
			}			
		}

		add_action( 'lsx_to_framework_team_tab_content', array($this,'general_settings'), 10 , 2 );
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
		if(!class_exists('LSX_Banners')){
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

		//Galleries Block
		$fields[] = array( 'id' => 'gallery_title',  'name' => esc_html__('Gallery','tour-operator'), 'type' => 'title' );
		$fields[] = array( 'id' => 'gallery', 'name' => esc_html__('Gallery','tour-operator'), 'type' => 'image', 'repeatable' => true, 'show_size' => false );
		if(class_exists('Envira_Gallery')){
			$fields[] = array( 'id' => 'envira_title',  'name' => esc_html__('Envira Gallery','tour-operator'), 'type' => 'title' );
			$fields[] = array( 'id' => 'envira_gallery', 'name' => esc_html__('Envira Gallery','to-galleries'), 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'envira','nopagin' => true,'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC' ) , 'allow_none' => true );
			if(class_exists('Envira_Videos')){
				$fields[] = array( 'id' => 'envira_video', 'name' => esc_html__('Envira Video Gallery','to-galleries'), 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'envira','nopagin' => true,'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC' ) , 'allow_none' => true );
			}
		}		
		
		if(class_exists('LSX_TO_Field_Pattern')){ $fields = array_merge($fields,LSX_TO_Field_Pattern::videos()); }

		$fields[] = array( 'id' => 'accommodation_title',  'name' => 'Accommodation', 'type' => 'title' );
		$fields[] = array( 'id' => 'accommodation_to_team', 'name' => 'Accommodations related with this team', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'accommodation','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true, 'cols' => 12 );
		$fields[] = array( 'id' => 'destinations_title',  'name' => 'Destinations', 'type' => 'title' );
		$fields[] = array( 'id' => 'destination_to_team', 'name' => 'Destinations related with this team', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'destination','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true, 'cols' => 12 );
		$fields[] = array( 'id' => 'tours_title',  'name' => 'Tours', 'type' => 'title' );
		$fields[] = array( 'id' => 'tour_to_team', 'name' => 'Tours related with this team', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'tour','nopagin' => true,'posts_per_page' => 1000, 'orderby' => 'title', 'order' => 'ASC' ), 'repeatable' => true, 'sortable' => true, 'allow_none'=>true, 'cols' => 12 );

		//Allow the addons to add additional fields.
		$fields = apply_filters('lsx_to_team_custom_fields',$fields);

		$meta_boxes[] = array(
				'title' => 'Tour Operator Plugin',
				'pages' => 'team',
				'fields' => $fields
		);		
		
		return $meta_boxes;
	
	}	

	/**
	 * Adds in the fields to the Tour Operators Post Types.
	 */
	public function custom_fields($fields) {
		global $post, $typenow, $current_screen;

		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && $current_screen->post_type ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( isset( $_REQUEST['post'] ) ) {
			$post_type = get_post_type( sanitize_key( $_REQUEST['post'] ) );
		}
		
		$fields[] = array('id' => 'team_title', 'name' => 'Teams', 'type' => 'title', 'cols' => 12);
		$fields[] = array('id' => 'team_to_' . $post_type, 'name' => 'Specials related with this '.$post_type, 'type' => 'post_select', 'use_ajax' => false, 'query' => array('post_type' => 'team', 'nopagin' => true, 'posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'ASC'), 'repeatable' => true, 'allow_none' => true, 'cols' => 12);
		return $fields;
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

				<?php wp_nonce_field( 'lsx_to_team_save_term_expert', 'lsx_to_team_term_expert_nonce' ); ?>
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

		if ( check_admin_referer( 'lsx_to_team_save_term_expert', 'lsx_to_team_term_expert_nonce' ) ) {
			$meta = ! empty( sanitize_text_field(wp_unslash($_POST[ 'expert' ])) ) ? sanitize_text_field(wp_unslash($_POST[ 'expert' ]))	: '';
			if ( empty( $meta ) ) {
				delete_term_meta( $term_id, 'expert' );
			} else {
				update_term_meta( $term_id, 'expert', $meta );
			}
		}
	}


	/**
	 * Adds the team specific options
	 */
	public function general_settings($post_type=false,$tab=false) {
		if('general' !== $tab){ return false; }
		?>
		<?php
		$experts = get_posts(
			array(
				'post_type' => 'team',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			)
		);
		?>
		<tr class="form-field-wrap">
			<th scope="row">
				<label> Select your consultants</label>
			</th>
			<td>
				<?php foreach ( $experts as $expert ) : ?>
					<label for="expert-<?php echo $expert->ID ?>"><input type="checkbox" {{#if expert-<?php echo $expert->ID ?>}} checked="checked" {{/if}} name="expert-<?php echo $expert->ID ?>" id="expert-<?php echo $expert->ID ?>" value="<?php echo $expert->ID ?>" /> <?php echo $expert->post_title ?></label><br>
				<?php endforeach ?>
			</td>
		</tr>
		<?php
	}
}
new LSX_TO_Team_Admin();