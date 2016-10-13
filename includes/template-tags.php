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