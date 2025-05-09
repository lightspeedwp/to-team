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
