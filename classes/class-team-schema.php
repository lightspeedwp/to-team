<?php
/**
 * LSX_TO_Team_Schema
 *
 * @package   LSX_TO_Team_Schema
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Specials_Schema
 * @author  LightSpeed
 */

class LSX_TO_Team_Schema extends LSX_TO_Team {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();
		add_action( 'wp_head', array( $this, 'team_single_schema' ), 1499 );
	}

	/**
	 * Creates the schema for the team post type
	 *
	 * @since 1.0.0
	 * @return    object    A single instance of this class.
	 */
	public function team_single_schema() {
		if ( is_singular( 'team' ) ) {
			$pinterest = get_post_meta( get_the_ID(), 'pinterest', true );
			$googleplus = get_post_meta( get_the_ID(), 'googleplus', true );
			$twitter = get_post_meta( get_the_ID(), 'twitter', true );
			$linkedin = get_post_meta( get_the_ID(), 'linkedin', true );
			$facebook = get_post_meta( get_the_ID(), 'facebook', true );
			$phone_number_team = get_post_meta( get_the_ID(), 'contact_number', true );
			$job_role_team = get_post_meta( get_the_ID(), 'role', true );
			$email_team = get_post_meta( get_the_ID(), 'contact_email', true );
			$name_team = get_the_title();
			$team_description = wp_strip_all_tags( get_the_content() );

		if (! empty($googleplus) || ! empty($pinterest) || ! empty($twitter) || ! empty($linkedin) ||  ! empty($facebook) ) {
			$social_array = array( $googleplus, $pinterest, $twitter, $linkedin, $facebook );
		}

		$meta = array(
		"@context" => "http://schema.org/",
		"@type" => "Person",
		"email" => $email_team,
		"jobTitle" => $job_role_team,
		"telephone" => $phone_number_team,
		"description" => $team_description,
		"name" => $name_team,
		"sameAs" => $social_array,
		);
		$output = wp_json_encode( $meta, JSON_UNESCAPED_SLASHES  );
		?>
		<script type="application/ld+json">
			<?php echo wp_kses_post( $output ); ?>
		</script>
		<?php
		}
	}
}

new LSX_TO_Team_Schema();
