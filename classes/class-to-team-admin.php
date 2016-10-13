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
		add_filter( 'cmb_meta_boxes', array( $this, 'register_metaboxes') );
		add_action( 'to_framework_team_tab_general_settings_bottom', array($this,'general_settings'), 10 , 1 );
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
	 * Adds the team specific options
	 */
	public function general_settings() {
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
			<tr class="form-field">
				<th scope="row" colspan="2"><label><h3>Extra</h3></label></th>
			</tr>
			<tr class="form-field-wrap">
				<th scope="row">
					<label> Select your consultants</label>
				</th>
				<td>
					<?php foreach ( $experts as $expert ) : ?>
						<label for="expert-<?php echo esc_attr( $expert->ID ); ?>"><input type="checkbox" {{#if expert-<?php echo esc_attr( $expert->ID ); ?>}} checked="checked" {{/if}} name="expert-<?php echo esc_attr( $expert->ID ); ?>" id="expert-<?php echo esc_attr( $expert->ID ); ?>" value="<?php echo esc_attr( $expert->ID ); ?>" /> <?php echo esc_html( $expert->post_title ); ?></label><br>
					<?php endforeach ?>
				</td>
			</tr>	
		<?php
	}	
}
new TO_Team_Admin();