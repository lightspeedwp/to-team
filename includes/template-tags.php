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
function to_team_content($slug, $name = null) {
	do_action('to_team_content',$slug, $name);
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
function to_team_role($before="",$after="",$echo=true){
	to_custom_field_query('role',$before,$after,$echo);
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
function to_team_contact_number($before="",$after="",$echo=true){
	$contact_number = get_post_meta(get_the_ID(),'contact_number',true);
	if(false !== $contact_number && '' !== $contact_number){
		$contact_html = $before.'<a href="tel:+'.$contact_number.'">'.$contact_number.'</a>'.$after;
		if($echo){
			echo wp_kses_post( $contact_html );
		}else{
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
function to_team_contact_email($before="",$after="",$echo=true){
	$contact_email = get_post_meta(get_the_ID(),'contact_email',true);
	if(false !== $contact_email && '' !== $contact_email){
		$contact_html = $before.'<a href="mailto:'.$contact_email.'">'.$contact_email.'</a>'.$after;
		if($echo){
			echo wp_kses_post( $contact_html );
		}else{
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
function to_team_social_profiles($before="",$after="",$echo=true){
	$social_profiles = array('facebook','twitter','googleplus','linkedin','pinterest','skype');
	$social_profile_html = false;
	foreach($social_profiles as $meta_key){
		$meta_value = get_post_meta(get_the_ID(),$meta_key,true);
		if(false !== $meta_value && '' !== $meta_value){
			$icon_class = '';
			switch($meta_key){
				case 'facebook':
					$icon_class = 'facebook';
				break;
				
				case 'twitter':
					$icon_class = 'twitter';
				break;
				
				case 'googleplus':
					$icon_class = 'googleplus';
				break;
				
				case 'linkedin':
					$icon_class = 'linkedin-alt';
				break;
				
				case 'pinterest':
					$icon_class = 'pinterest-alt';
				break;							
				
				default:
				break;
			}
			$social_profile_html .= '<a target="_blank" href="'.$meta_value.'"><span class="genericon genericon-'.$icon_class.'"></span></a>';
		}
	}
	if(false !== $social_profile_html && '' !== $social_profile_html){
		$social_profile_html = $before.$social_profile_html.$after;
		if($echo){
			echo wp_kses_post( $social_profile_html );
		}else{
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
function to_team_tagline($before="",$after="",$echo=true){
	to_tagline($before,$after,$echo);
}

/**
 * Gets the current connected team member panel
 * @return		string
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function to_has_team_member() {
	$has_team = false;

	if ( is_tax() ) {
		$has_team = to_has_custom_field_query( 'expert', get_queried_object()->term_id, true );
	} else {
		$has_team = to_has_custom_field_query( 'team_to_'. get_post_type(), get_the_ID() );
	}

	if ( false === $has_team ) {

		global $tour_operator;
		$tab = 'team';
		$start_with = 'expert-';

		if ( is_object( $tour_operator ) && isset( $tour_operator->options[$tab] ) && is_array( $tour_operator->options[$tab] ) ) {
			foreach ( $tour_operator->options[$tab] as $key => $value ) {
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
function to_team_member_panel($before="",$after=""){
	$team_id = false;

	if ( is_tax() ) {
		$meta_key = 'expert';
		$team_id = get_transient( get_queried_object()->term_id .'_'. $meta_key );
	} else {
		$meta_key = 'team_to_'. get_post_type();
		$team_id = get_transient( get_the_ID() .'_'. $meta_key );
	}

	if ( false === $team_id ) {
		global $to_operators;
		$tab = 'team';
		$start_with = 'expert-';
		$team_ids = array();
		
		if ( is_object( $to_operators ) && isset( $to_operators->options[$tab] ) && is_array( $to_operators->options[$tab] ) ) {
			foreach ( $to_operators->options[$tab] as $key => $value ) {
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
			'post_type'	=>	'team',
			'post_status' => 'publish',
			'p' => $team_id
		);

		$team = new WP_Query($team_args);

		if ( $team->have_posts() ):
			echo wp_kses_post( $before );
			while($team->have_posts()):
				$team->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="thumbnail">
						<?php if(!to_is_single_disabled()){ ?>
							<a href="<?php the_permalink(); ?>">
						<?php } ?>
							<?php lsx_thumbnail( 'lsx-thumbnail-wide' ); ?>
						<?php if(!to_is_single_disabled()){ ?>
							</a>
						<?php } ?>
					</div>

					<h4 class="title">
						<?php if(!to_is_single_disabled()){ ?>
							<a href="<?php the_permalink(); ?>">
						<?php } ?>
							<?php the_title(); ?>
						<?php if(!to_is_single_disabled()){ ?>
							</a>
						<?php } ?>
					</h4>
					<div class="team-details">
						<?php to_team_contact_number('<div class="meta contact-number"><i class="fa fa-phone orange"></i> ','</div>'); ?>
						<?php to_team_contact_email('<div class="meta email"><i class="fa fa-envelope orange"></i> ','</div>'); ?> 
					</div>
				</article>
				<?php			
			endwhile;
			
			echo wp_kses_post( $after );
			
			wp_reset_query();
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
function to_team_accommodation(){
	global $to_archive;
	if(post_type_exists('accommodation') && is_singular('team')) {
		$args = array(
				'from'			=>	'accommodation',
				'to'			=>	'team',
				'column'		=>	'12',
				'before'		=>	'<section id="accommodation"><h2 class="section-title">'.__(to_get_post_type_section_title('accommodation', '', 'Featured Accommodations'),'to-team').'</h2>',
				'after'			=>	'</section>'
		);
		to_connected_panel_query($args);
	}
}

/**
 * Outputs the connected tour for a team member
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	team
 */
function to_team_tours(){
	global $to_archive;
	if(post_type_exists('tour') && is_singular('team')) {
		$args = array(
				'from'			=>	'tour',
				'to'			=>	'team',
				'column'		=>	'12',
				'before'		=>	'<section id="tours"><h2 class="section-title">'.__(to_get_post_type_section_title('tour', '', 'Featured Tours'),'to-team').'</h2>',
				'after'			=>	'</section>'
		);
		to_connected_panel_query($args);
	}
}