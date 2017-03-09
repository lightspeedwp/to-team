<?php
/**
 * LSX_TO_Team_Frontend
 *
 * @package   LSX_TO_Team_Frontend
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link      
 * @copyright {year} LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Team_Frontend
 * @author  {your-name}
 */
class LSX_TO_Team_Frontend extends LSX_TO_Team{

	/**
	 * Holds the $page_links array while its being built on the single team page.
	 *
	 * @var array
	 */
	public $page_links = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class') );

		if(!class_exists('LSX_TO_Template_Redirects')){
			require_once( LSX_TO_TEAM_PATH . 'classes/class-template-redirects.php' );
		}	
		$this->redirects = new LSX_TO_Template_Redirects(LSX_TO_TEAM_PATH,array('team'));
		add_action( 'lsx_to_team_content', array( $this->redirects, 'content_part' ), 10 , 2 );

		add_filter( 'lsx_to_page_navigation', array( $this, 'page_links' ) );

		add_action( 'lsx_entry_bottom',       array( $this, 'archive_entry_bottom' ) );
		add_action( 'lsx_content_bottom',     array( $this, 'single_content_bottom' ) );
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
				$classes[] = 'col-sm-9';
			} else {
				$classes[] = 'col-sm-12';
			}
		}

		return $classes;
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

			$this->get_related_accommodation_link();
			$this->get_related_tours_link();
			$this->get_gallery_link();
			$this->get_videos_link();

			$page_links = $this->page_links;
		}

		return $page_links;
	}

	/**
	 * Tests for the Related Accommodation and returns a link for the section
	 */
	public function get_related_accommodation_link() {
		$connected_accommodation = get_post_meta( get_the_ID(), 'accommodation_to_team', false );

		if ( post_type_exists( 'accommodation' ) && is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
			$this->page_links[ 'accommodation' ] = esc_html__( 'Accommodation', 'to-team' );
		}
	}

	/**
	 * Tests for the Related Tours and returns a link for the section
	 */
	public function get_related_tours_link() {
		$connected_tours = get_post_meta( get_the_ID(), 'tour_to_team', false );
		
		if ( post_type_exists( 'tour' ) && is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
			$this->page_links[ 'tours' ] = esc_html__( 'Tours', 'to-team' );
		}
	}

	/**
	 * Tests for the Gallery and returns a link for the section
	 */
	public function get_gallery_link() {
		if ( function_exists( 'lsx_to_gallery' ) ) {
			$gallery_ids = get_post_meta( get_the_ID(), 'gallery', false );
			$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );

			if ( ( false !== $gallery_ids && '' !== $gallery_ids && is_array( $gallery_ids ) && ! empty( $gallery_ids ) ) || ( false !== $envira_gallery && '' !== $envira_gallery ) ) {
			 	$this->page_links[ 'gallery' ] = esc_html__( 'Gallery', 'to-team' );
			 	return;
			}
		} elseif ( class_exists( 'envira_gallery' ) ) {
			$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );
			
			if ( false !== $envira_gallery && '' !== $envira_gallery && false === lsx_to_enable_envira_banner() ) {
				$this->page_links[ 'gallery' ] = esc_html__( 'Gallery', 'to-team' );
			 	return;
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

		if ( ( false === $videos_id || '' === $videos_id ) && class_exists( 'LSX_TO_Videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'videos', true );
		}

		if ( false !== $videos_id && '' !== $videos_id ) {
			$this->page_links['videos'] = esc_html__( 'Videos', 'to-team' );
		}
	}

	/**
	 * Adds the template tags to the bottom of the archive team
	 */
	public function archive_entry_bottom() {
		global $lsx_to_archive;

		if ( 'team' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) :
			?>		
			</div>
			<div class="col-sm-4">
				<div class="team-details">
					<?php lsx_to_team_role('<div class="meta role">'.__('Role','to-team').': ','</div>'); ?>
					<?php lsx_to_team_contact_number('<div class="meta contact-number"><i class="fa fa-phone orange"></i> ','</div>'); ?>
					<?php lsx_to_team_contact_email('<div class="meta email"><i class="fa fa-envelope orange"></i> ','</div>'); ?>
					<?php lsx_to_team_contact_skype('<div class="meta skype"><i class="fa fa-skype orange"></i> ','</div>'); ?>
					<?php lsx_to_team_social_profiles('<div class="social-links">','</div>'); ?>
				</div>
			</div>
		</div>
		<?php
		endif;
	}

	/**
	 * Adds the template tags to the bottom of the single team
	 */
	public function single_content_bottom() {
		if ( is_singular( 'team' ) ) {
			lsx_to_team_accommodation();

			lsx_to_team_tours();

			if ( function_exists( 'lsx_to_gallery' ) ) {
				lsx_to_gallery( '<section id="gallery"><h2 class="section-title">' . esc_html__( 'Gallery', 'to-team' ) . '</h2>', '</section>' );
			} elseif ( class_exists( 'envira_gallery' ) ) {
				lsx_to_envira_gallery( '<section id="gallery"><h2 class="section-title">' . esc_html__( 'Gallery', 'to-team' ) . '</h2>', '</section>' );
			}

			if ( function_exists( 'lsx_to_videos' ) ) {
				lsx_to_videos( '<div id="videos"><h2 class="section-title">' . esc_html__( 'Videos', 'to-team' ) . '</h2>', '</div>' );
			} elseif ( class_exists( 'Envira_Videos' ) ) {
				lsx_to_envira_videos( '<div id="videos"><h2 class="section-title">' . esc_html__( 'Videos', 'to-team' ) . '</h2>', '</div>' );
			}
		}
	}

}
new LSX_TO_Team_Frontend();