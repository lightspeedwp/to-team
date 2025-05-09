<?php
/**
 * LSX_TO_Team_Frontend
 *
 * @package   LSX_TO_Team_Frontend
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Team_Frontend
 * @author  LightSpeed
 */
class LSX_TO_Team_Frontend {

	/**
	 * Holds the $page_links array while its being built on the single team page.
	 *
	 * @var array
	 */
	public $page_links = false;

	/**
	 * Holds the array of options.
	 *
	 * @var array
	 */
	public $options = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = get_option( '_lsx-to_settings', false );

		if ( ! is_admin() ) {
			add_filter( 'posts_orderby', array( $this, 'enable_role_taxonomy_order' ), 10, 2 );
		}

		add_filter( 'lsx_to_maps_args', array( $this, 'lsx_to_maps_args' ), 10, 2 );
		add_filter( 'lsx_to_has_maps_location', array( $this, 'lsx_to_has_maps_location' ), 50, 2 );
	}

	/**
	 * Enable role taxonomy order.
	 */
	public function enable_role_taxonomy_order( $orderby, $query ) {
		global $wpdb;

		if ( $query->is_main_query() && $query->is_post_type_archive( 'team' ) ) {
			if ( isset( $this->options['team'] ) && isset( $this->options['team']['group_items_by_role'] ) ) {
				$new_orderby = "(
					SELECT GROUP_CONCAT(lsx_to_term_order ORDER BY lsx_to_term_order ASC)
					FROM $wpdb->term_relationships
					INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
					INNER JOIN $wpdb->terms USING (term_id)
					WHERE $wpdb->posts.ID = object_id
					AND taxonomy = 'role'
					GROUP BY object_id
				) ";

				$new_orderby .= ( 'ASC' == strtoupper( $query->get( 'order' ) ) ) ? 'ASC' : 'DESC';
				$orderby = $new_orderby . ', ' . $orderby;
			}
		}

		return $orderby;
	}

	public function lsx_to_maps_args( $args, $post_id ) {
		if ( is_singular( 'team' ) ) {
			$accommodation_connected = get_post_meta( get_the_ID(), 'accommodation_to_team' );
			if ( is_array( $accommodation_connected ) && ! empty( $accommodation_connected ) ) {
				$args = array(
					'lat' => true,
					'long' => true,
					'connections' => $accommodation_connected,
					'content' => 'excerpt',
					'type' => 'cluster',
					'width' => '100%',
					'height' => '500px',
				);
			}
		}
		return $args;
	}

	public function lsx_to_has_maps_location( $location, $id ) {
		if ( is_singular( 'team' ) ) {
			$accommodation_connected = get_post_meta( $id, 'accommodation_to_team' );
			if ( is_array( $accommodation_connected ) && ! empty( $accommodation_connected ) ) {
				$location = array(
					'lat' => true,
					'connections' => $accommodation_connected,
				);
			}
		}
		return $location;
	}
}

global $lsx_to_team_frontend;
$lsx_to_team_frontend = new LSX_TO_Team_Frontend();
