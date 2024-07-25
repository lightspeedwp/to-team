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
class LSX_TO_Team_Frontend extends LSX_TO_Team {

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

		add_action( 'wp_head', array( $this, 'change_single_team_layout' ), 20, 1 );

		if ( ! is_admin() ) {
			add_filter( 'posts_orderby', array( $this, 'enable_role_taxonomy_order' ), 10, 2 );
		}

		add_filter( 'lsx_to_archive_class', array( $this, 'archive_class' ), 10, 3 );
		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class' ) );
		add_action( 'lsx_to_settings_current_tab', array( $this, 'set_settings_current_tab' ) );

		/*if ( ! class_exists( 'LSX_TO_Template_Redirects' ) ) {
			require_once( LSX_TO_TEAM_PATH . 'classes/class-lsx-to-template-redirects.php' );
		}*/

		//$this->redirects = new LSX_TO_Template_Redirects( LSX_TO_TEAM_PATH, array( 'team' ), array( 'role' ) );

		add_action( 'lsx_to_team_content', array( $this->redirects, 'content_part' ), 10, 2 );

		add_filter( 'lsx_to_page_navigation', array( $this, 'page_links' ) );

		add_action( 'lsx_entry_top', array( $this, 'archive_entry_top' ), 15 );
		add_action( 'lsx_entry_bottom', array( $this, 'archive_entry_bottom' ) );
		add_action( 'lsx_content_bottom', array( $this, 'single_content_bottom' ) );

		add_filter( 'lsx_to_maps_args', array( $this, 'lsx_to_maps_args' ), 10, 2 );
		add_filter( 'lsx_to_has_maps_location', array( $this, 'lsx_to_has_maps_location' ), 50, 2 );
	}

	/**
	 * Change single team layout.
	 */
	public function change_single_team_layout() {
		global $lsx_to_archive;

		if ( is_singular( 'team' ) && 1 !== $lsx_to_archive ) {
			remove_action( 'lsx_entry_bottom', 'lsx_to_single_entry_bottom' );
			add_action( 'lsx_entry_top', array( $this, 'lsx_to_single_entry_bottom' ) );
		}
	}

	/**
	 * Change single team layout.
	 */
	public function lsx_to_single_entry_bottom() {
		if ( is_singular( 'team' ) ) { ?>
			<div class="col-xs-12 col-sm-5 col-md-4">
				<figure class="lsx-to-team-thumb">
					<?php lsx_thumbnail( 'lsx-thumbnail-square' ); ?>
				</figure>

				<?php
					lsx_to_team_social_profiles( '<span class="lsx-to-team-socials-header">' . esc_html__( 'Follow', 'to-team' ) . ':</span><div class="lsx-to-team-socials">', '</div>' );
					lsx_to_enquire_modal( esc_html__( 'Get in touch', 'to-team' ) );
				?>
			</div>
		<?php
		}
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

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function archive_class( $new_classes, $classes, $layout ) {
		if ( is_post_type_archive( 'team' ) ) {
			$new_classes = $classes;

			if ( 'grid' === $layout ) {
				$new_classes[] = 'col-xs-12 col-sm-6 col-md-3';
			} else {
				$new_classes[] = 'col-xs-12';
			}
		}

		return $new_classes;
	}

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function entry_class( $classes ) {
		global $lsx_to_archive;

		if ( 1 !== $lsx_to_archive ) {
			$lsx_to_archive = false;
		}

		if ( is_main_query() && is_singular( 'team' ) && false === $lsx_to_archive ) {
			if ( lsx_to_has_enquiry_contact() ) {
				$classes[] = 'col-xs-12 col-sm-7 col-md-8';
			} else {
				$classes[] = 'col-xs-12';
			}
		}

		return $classes;
	}

	/**
	 * Sets the current tab selected.
	 */
	public function set_settings_current_tab( $settings_tab ) {
		if ( is_tax( 'role' ) ) {
			$taxonomy = get_query_var( 'taxonomy' );

			if ( 'role' === $taxonomy ) {
				$settings_tab = 'team';
			}
		}

		return $settings_tab;
	}

	/**
	 * Adds our navigation links to the team single post
	 *
	 * @param $page_links array
	 * @return $page_links array
	 */
	public function page_links( $page_links ) {
		if ( is_singular( 'team' ) ) {
			$this->page_links = $page_links;

			$this->get_map_link();

			$this->get_related_posts_link();
			$this->get_related_accommodation_link();
			$this->get_related_destination_link();
			$this->get_related_tours_link();
			$this->get_related_reviews_link();

			$this->get_gallery_link();
			$this->get_videos_link();

			$page_links = $this->page_links;
		}

		return $page_links;
	}

	/**
	 * Tests for the Related Posts and returns a link for the section
	 */
	public function get_related_posts_link() {
		$site_user = get_post_meta( get_the_ID(), 'site_user', true );

		if ( ! empty( $site_user ) ) {
			if ( is_user_member_of_blog( $site_user ) ) {
				$user_posts = count_user_posts( $site_user, 'post' );

				if ( $user_posts > 0 ) {
					$this->page_links['posts'] = esc_html__( 'Posts', 'to-team' );
				}
			}
		}
	}

	/**
	 * Tests for the Google Map and returns a link for the section
	 */
	public function get_map_link() {
		if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) {
			$this->page_links['team-map'] = esc_html__( 'Map', 'tour-operator' );
		}
	}

	/**
	 * Tests for the Related Accommodation and returns a link for the section
	 */
	public function get_related_accommodation_link() {
		$connected_accommodation = get_post_meta( get_the_ID(), 'accommodation_to_team', false );

		if ( post_type_exists( 'accommodation' ) && is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
			$connected_accommodation = new \WP_Query( array(
				'post_type' => 'accommodation',
				'post__in' => $connected_accommodation,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_accommodation = $connected_accommodation->posts;

			if ( is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
				$this->page_links['accommodation'] = esc_html__( 'Accommodation', 'to-team' );
			}
		}
	}

	/**
	 * Tests for the Related Destinations and returns a link for the section
	 */
	public function get_related_destination_link() {
		$connected_destination = get_post_meta( get_the_ID(), 'destination_to_team', false );

		if ( post_type_exists( 'destination' ) && is_array( $connected_destination ) && ! empty( $connected_destination ) ) {
			$connected_destination = new \WP_Query( array(
				'post_type' => 'destination',
				'post__in' => $connected_destination,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_destination = $connected_destination->posts;

			if ( is_array( $connected_destination ) && ! empty( $connected_destination ) ) {
				$this->page_links['destination'] = esc_html__( 'Destinations', 'to-team' );
			}
		}
	}

	/**
	 * Tests for the Related Tours and returns a link for the section
	 */
	public function get_related_tours_link() {
		$connected_tours = get_post_meta( get_the_ID(), 'tour_to_team', false );

		if ( post_type_exists( 'tour' ) && is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
			$connected_tours = new \WP_Query( array(
				'post_type' => 'tour',
				'post__in' => $connected_tours,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_tours = $connected_tours->posts;

			if ( is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
				$this->page_links['tours'] = esc_html__( 'Tours', 'to-team' );
			}
		}
	}

	/**
	 * Tests for the Related Tours and returns a link for the section
	 */
	public function get_related_reviews_link() {
		$connected_reviews = get_post_meta( get_the_ID(), 'review_to_team', false );

		if ( post_type_exists( 'review' ) && is_array( $connected_reviews ) && ! empty( $connected_reviews ) ) {
			$connected_reviews = new \WP_Query( array(
				'post_type' => 'review',
				'post__in' => $connected_reviews,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_reviews = $connected_reviews->posts;

			if ( is_array( $connected_reviews ) && ! empty( $connected_reviews ) ) {
				$this->page_links['reviews'] = esc_html__( 'Reviews', 'to-team' );
			}
		}
	}

	/**
	 * Tests for the Gallery and returns a link for the section
	 */
	public function get_gallery_link() {
		$gallery_ids = get_post_meta( get_the_ID(), 'gallery', false );
		$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );

		if ( ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) || ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) ) {
			if ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) {
				// Envira Gallery.
				$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-team' );
				return;
			} else {
				if ( function_exists( 'envira_dynamic' ) ) {
					// Envira Gallery - Dynamic.
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-team' );
					return;
				} else {
					// WordPress Gallery.
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-team' );
					return;
				}
			}
		}
	}

	/**
	 * Tests for the Videos and returns a link for the section
	 */
	public function get_videos_link() {
		$videos_id = false;

		if ( class_exists( 'Envira_Videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'envira_video', true );
		}

		if ( empty( $videos_id ) && function_exists( 'lsx_to_videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'videos', true );
		}

		if ( ! empty( $videos_id ) ) {
			$this->page_links['videos'] = esc_html__( 'Videos', 'to-team' );
		}
	}

	/**
	 * Adds the template tags to the top of the archive team
	 */
	public function archive_entry_top() {
		global $lsx_to_archive;

		if ( 'team' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) {
			?>
			<?php if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
				<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-grid-mode">
					<?php
						$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

						lsx_to_team_role( '<span class="' . $meta_class . 'role"><span class="lsx-to-meta-data-key">' . __( 'Role', 'to-team' ) . ':</span> ', '</span>' );
						lsx_to_team_contact_number( '<span class="' . $meta_class . 'phone">', '</span>' );
						lsx_to_team_contact_email( '<span class="' . $meta_class . 'email">', '</span>' );
						lsx_to_team_contact_skype( '<span class="' . $meta_class . 'skype">', '</span>' );
						lsx_to_team_social_profiles( '<div class="' . $meta_class . 'socials">', '</div>' );
					?>
				</div>
			<?php } ?>
		<?php
		}
	}

	/**
	 * Adds the template tags to the bottom of the archive team
	 */
	public function archive_entry_bottom() {
		global $lsx_to_archive;

		if ( 'team' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) {
			?>
				</div>

				<?php if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
					<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-list-mode">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							lsx_to_team_role( '<span class="' . $meta_class . 'role"><span class="lsx-to-meta-data-key">' . __( 'Role', 'to-team' ) . ':</span> ', '</span>' );
							lsx_to_team_contact_number( '<span class="' . $meta_class . 'phone">', '</span>' );
							lsx_to_team_contact_email( '<span class="' . $meta_class . 'email">', '</span>' );
							lsx_to_team_contact_skype( '<span class="' . $meta_class . 'skype">', '</span>' );
							lsx_to_team_social_profiles( '<div class="' . $meta_class . 'socials">', '</div>' );
						?>
					</div>
				<?php } ?>

				<?php
					$member_name = get_the_title();
					$has_single = ! lsx_to_is_single_disabled();
				?>

				<?php if ( $has_single && 'grid' === tour_operator()->archive_layout ) : ?>
					<p class="text-center lsx-to-single-link"><a href="<?php the_permalink(); ?>"><?php echo esc_html__( 'More about', 'to-team' ) . ' ' . esc_html( strtok( $member_name, ' ' ) ); ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a></p>
				<?php endif; ?>
			</div>
		<?php
		}
	}

	/**
	 * Adds the template tags to the bottom of the single team
	 */
	public function single_content_bottom() {
		if ( is_singular( 'team' ) ) {
			if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) :
				global $post;
				$map_title = $post->post_title;
				$map_title = $map_title . __( "'s favourite places", 'to-team' );
			?>
				<section id="team-map" class="lsx-to-section lsx-to-collapse-section">
					<h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-team-map"><?php echo esc_html( $map_title ); ?></h2>

					<div id="collapse-team-map" class="collapse in">
						<div class="collapse-inner">
							<?php lsx_to_map(); ?>
						</div>
					</div>
				</section>
				<?php
			endif;

			lsx_to_team_posts();

			lsx_to_team_accommodation();

			lsx_to_team_destination();

			lsx_to_team_tours();

			lsx_to_team_reviews();

			lsx_to_gallery( '<section id="gallery" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-gallery">' . esc_html__( 'Gallery', 'to-team' ) . '</h2><div id="collapse-gallery" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );

			if ( function_exists( 'lsx_to_videos' ) ) {
				lsx_to_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-team' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			} elseif ( class_exists( 'Envira_Videos' ) ) {
				lsx_to_envira_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-team' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			}
		}
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
