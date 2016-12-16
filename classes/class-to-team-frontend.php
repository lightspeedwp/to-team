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
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class') );

		if(!class_exists('LSX_Template_Redirects')){
			require_once( LSX_TO_TEAM_PATH . 'classes/class-template-redirects.php' );
		}	
		$this->redirects = new LSX_Template_Redirects(LSX_TO_TEAM_PATH,array('team'));
		add_action( 'lsx_to_team_content', array( $this->redirects, 'content_part' ), 10 , 2 );				
	}	

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function entry_class( $classes ) {
		global $lsx_to_archive;
		if(1 !== $lsx_to_archive){$lsx_to_archive = false;}

		if(is_main_query() && is_singular('team') && false === $lsx_to_archive){
			$classes[] = 'col-sm-9';	
		}
		return $classes;
	}	
}
new LSX_TO_Team_Frontend();