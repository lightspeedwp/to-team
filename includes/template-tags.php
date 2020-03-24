<?php
/**
 * Template Tags
 *
 * @package   to-team
 * @author    LightSpeed
 * @license   {license}
 * @link
 * @copyright 2016 LightSpeedDevelopment
 */

/**
 * Find the content part in the plugin
 *
 * @package 	to-team
 * @subpackage	template-tag
 * @category 	content
 */
function lsx_to_team_content( $slug, $name = null ) {
	do_action( 'lsx_to_team_content',$slug, $name );
}

/**
 * Outputs the current team members role, must be used in a loop.
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_role( $before = '', $after = '', $echo = true ) {
	lsx_to_custom_field_query( 'role',$before,$after,$echo );
}

/**
 * Outputs the current team members role, must be used in a loop.
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_contact_number( $before = '', $after = '', $echo = true ) {
	$contact_number = get_post_meta( get_the_ID(), 'contact_number', true );

	if ( false !== $contact_number && '' !== $contact_number ) {
		$contact_html = $before . '<a href="tel:+' . $contact_number . '">' . $contact_number . '</a>' . $after;

		if ( $echo ) {
			echo wp_kses_post( $contact_html );
		} else {
			return $contact_html;
		}
	}
}

/**
 * Outputs the current team members role, must be used in a loop.
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_contact_email( $before = '', $after = '', $echo = true ) {
	$contact_email = get_post_meta( get_the_ID(), 'contact_email', true );

	if ( false !== $contact_email && '' !== $contact_email ) {
		$contact_html = $before . '<a href="mailto:' . $contact_email . '">' . $contact_email . '</a>' . $after;

		if ( $echo ) {
			echo wp_kses_post( $contact_html );
		} else {
			return $contact_html;
		}
	}
}

/**
 * Outputs the current team members Skype, must be used in a loop.
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_contact_skype( $before = '', $after = '', $echo = true ) {
	$contact_skype = get_post_meta( get_the_ID(), 'skype', true );

	if ( false !== $contact_skype && '' !== $contact_skype ) {
		$contact_html = $before . '<span>' . $contact_skype . '</span>' . $after;

		if ( $echo ) {
			echo wp_kses_post( $contact_html );
		} else {
			return $contact_html;
		}
	}
}

/**
 * Outputs the current team members social profiles, must be used in a loop.
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_social_profiles( $before = '', $after = '', $echo = true ) {
	$social_profiles = array( 'facebook', 'twitter', 'googleplus', 'linkedin', 'pinterest' );
	$social_profile_html = false;

	foreach ( $social_profiles as $meta_key ) {
		$meta_value = get_post_meta( get_the_ID(),$meta_key,true );

		if ( false !== $meta_value && '' !== $meta_value ) {
			$icon_class = '';

			switch ( $meta_key ) {
				case 'facebook':
					$icon_class = 'facebook';
				break;

				case 'twitter':
					$icon_class = 'twitter';
				break;

				case 'googleplus':
					$icon_class = 'google-plus';
				break;

				case 'linkedin':
					$icon_class = 'linkedin';
				break;

				case 'pinterest':
					$icon_class = 'pinterest-p';

				default:
				break;
			}

			$social_profile_html .= '<a target="_blank" rel="noopener noreferrer" href="' . $meta_value . '"><i class="fa fa-' . $icon_class . '" aria-hidden="true"></i></a>';
		}
	}

	if ( false !== $social_profile_html && '' !== $social_profile_html ) {
		$social_profile_html = $before . $social_profile_html . $after;

		if ( $echo ) {
			echo wp_kses_post( $social_profile_html );
		} else {
			return $social_profile_html;
		}
	}
}

/**
 * Gets the current teams tagline
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-team
 * @subpackage	template-tags
 */
function lsx_to_team_tagline( $before = '', $after = '', $echo = true ) {
	lsx_to_tagline( $before,$after,$echo );
}

/**
 * Gets the current connected team member panel
 * @return		string
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_has_team_member() {
	$tour_operator = tour_operator();
	$tab = 'team';
	$start_with = 'expert-';
	$has_team = false;

	// Check if the team member panel has been disabled.
	if ( ! is_singular( 'team' ) && ! is_singular( 'review' ) ) {
		if ( is_object( $tour_operator ) && isset( $tour_operator->options[ $tab ] ) && is_array( $tour_operator->options[ $tab ] ) && isset( $tour_operator->options[ $tab ]['disable_team_panel'] ) ) {
			return false;
		}
	}

	if ( is_singular( 'team' ) || is_singular( 'review' ) ) {
		$has_team = has_post_thumbnail();
	} elseif ( is_tax() ) {
		$has_team = lsx_to_has_custom_field_query( 'expert', get_queried_object()->term_id, true );
	} else {
		$has_team = lsx_to_has_custom_field_query( 'team_to_' . get_post_type(), get_the_ID() );
	}

	if ( false === $has_team ) {
		if ( is_object( $tour_operator ) && isset( $tour_operator->options[ $tab ] ) && is_array( $tour_operator->options[ $tab ] ) ) {
			foreach ( $tour_operator->options[ $tab ] as $key => $value ) {
				if ( substr( $key, 0, strlen( $start_with ) ) === $start_with ) {
					$has_team = true;
					break;
				}
			}
		}
	}
	return $has_team;
}

/**
 * Gets the current connected team member panel
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_member_panel( $before = '', $after = '' ) {
	$team_id = false;

	if ( is_tax() ) {
		$meta_key = 'expert';
		$team_id = get_transient( get_queried_object()->term_id . '_' . $meta_key );
	} else {
		$meta_key = 'team_to_' . get_post_type();
		$team_id = get_transient( get_the_ID() . '_' . $meta_key );
	}

	if ( false === $team_id ) {
		$tour_operator = tour_operator();
		$tab = 'team';
		$start_with = 'expert-';
		$team_ids = array();

		if ( is_object( $tour_operator ) && isset( $tour_operator->options[ $tab ] ) && is_array( $tour_operator->options[ $tab ] ) ) {
			foreach ( $tour_operator->options[ $tab ] as $key => $value ) {
				if ( substr( $key, 0, strlen( $start_with ) ) === $start_with ) {
					$team_ids[] = $value;
				}
			}
		}

		if ( count( $team_ids ) > 0 ) {
			$team_id = $team_ids[ array_rand( $team_ids ) ];
		}
	}

	if ( false !== $team_id ) {
		$team_args = array(
			'post_type'	=> 'team',
			'post_status' => 'publish',
			'p' => $team_id,
		);

		$team = new WP_Query( $team_args );

		if ( $team->have_posts() ) :
			echo wp_kses_post( $before );

			while ( $team->have_posts() ) :
				global $post;

				$team->the_post();

				$has_single = ! lsx_to_is_single_disabled();
				$permalink = '';

				if ( $has_single ) {
					$permalink = get_the_permalink();
				} elseif ( ! is_post_type_archive( 'team' ) ) {
					$has_single = true;
					$permalink = get_post_type_archive_link( 'team' ) . '#team-' . $post->post_name;
				}
				?>
				<article <?php post_class(); ?>>
					<div class="lsx-to-contact-thumb">
						<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
							<?php lsx_thumbnail( 'lsx-thumbnail-square' ); ?>
						<?php if ( $has_single ) { ?></a><?php } ?>
					</div>

					<div class="lsx-to-contact-info">
						<small class="lsx-to-contact-prefix text-center">Your travel expert:</small>

						<h4 class="lsx-to-contact-name text-center">
							<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
								<?php the_title(); ?>
							<?php if ( $has_single ) { ?></a><?php } ?>
						</h4>
					</div>

					<div class="lsx-to-contact-meta-data text-center hidden">
						<?php
							lsx_to_team_contact_number( '<span class="lsx-to-meta-data contact-number"><i class="fa fa-phone orange"></i> ', '</span>' );
							lsx_to_team_contact_email( '<span class="lsx-to-meta-data email"><i class="fa fa-envelope orange"></i> ', '</span>' );
							lsx_to_team_contact_skype( '<span class="lsx-to-meta-data skype"><i class="fa fa-skype orange"></i> ', '</span>' );
							lsx_to_team_social_profiles( '<div class="lsx-to-meta-data lsx-to-contact-socials">', '</div>' );
						?>
					</div>
				</article>
				<?php
			endwhile;

			echo wp_kses_post( $after );

			wp_reset_postdata();
		endif;
	}
}

/**
 * Outputs the connected accommodation for a team member
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_accommodation() {
	global $lsx_to_archive;

	if ( post_type_exists( 'accommodation' ) && is_singular( 'team' ) ) {
		$args = array(
			'from'		=> 'accommodation',
			'to'		=> 'team',
			'column'	=> '3',
			// @codingStandardsIgnoreLine
			'before'	=> '<section id="accommodation" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-accommodation">' . __( lsx_to_get_post_type_section_title( 'accommodation', '', 'Featured Accommodations' ), 'to-team' ) . '</h2><div id="collapse-accommodation" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected destination for a team member
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_destination() {
	global $lsx_to_archive;

	if ( post_type_exists( 'destination' ) && is_singular( 'team' ) ) {
		$args = array(
			'from'   => 'destination',
			'to'     => 'team',
			'column' => '3',
			// @codingStandardsIgnoreLine
			'before' => '<section id="destination" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-destination">' . __( lsx_to_get_post_type_section_title( 'destination', '', 'Featured Destinations' ), 'to-team' ) . '</h2><div id="collapse-destination" class="collapse in"><div class="collapse-inner">',
			'after'  => '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected tour for a team member
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_tours() {
	global $lsx_to_archive;

	if ( post_type_exists( 'tour' ) && is_singular( 'team' ) ) {
		$args = array(
			'from'		=> 'tour',
			'to'		=> 'team',
			'column'	=> '3',
			// @codingStandardsIgnoreLine
			'before'	=> '<section id="tours" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-tours">' . __( lsx_to_get_post_type_section_title( 'tour', '', 'Featured Tours' ), 'to-team' ) . '</h2><div id="collapse-tours" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected accommodation for a team member
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_reviews() {
	global $lsx_to_archive;

	if ( post_type_exists( 'review' ) && is_singular( 'team' ) ) {
		$args = array(
			'from'		=> 'review',
			'to'		=> 'team',
			'column'	=> '2',
			// @codingStandardsIgnoreLine
			'before'	=> '<section id="reviews" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-reviews">' . __( lsx_to_get_post_type_section_title( 'review', '', 'Featured Reviews' ), 'to-team' ) . '</h2><div id="collapse-reviews" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Gets the current specials connected team member
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	connections
 */
function lsx_to_connected_team( $before = '', $after = '', $echo = true ) {
	lsx_to_connected_items_query( 'team', get_post_type(), $before, $after, $echo );
}

/**
 * Outputs the connected posts
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function lsx_to_team_posts() {
	//$site_user = get_post_meta( get_the_ID(), 'site_user', true );

	if ( post_type_exists( 'post' ) && is_singular( 'team' ) ) {
		$args = array(
			'from'		=> 'post',
			'to'		=> 'team',
			'column'	=> '3',
			// @codingStandardsIgnoreLine
			'before'	=> '<section id="posts" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-posts">' . __( lsx_to_get_post_type_section_title( 'post', '', 'Featured Posts' ), 'to-team' ) . '</h2><div id="collapse-posts" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}
