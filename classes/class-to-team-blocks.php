<?php
/**
 * LSX_TO_Team_Blocks
 *
 * @package   LSX_TO_Team
 * @author    LightSpeed
 * @license   GPL-3.0+
 */
class LSX_TO_Team_Blocks {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block_json_files' ) );
		add_filter( 'lsx_to_multi_field_wrappers', array( $this, 'register_multi_field_wrappers' ) );
	}

	/**
	 * Register team block wrappers that group multiple meta fields.
	 * The social-links wrapper should be hidden only when every social field is empty.
	 *
	 * @param array $wrappers
	 * @return array
	 */
	public function register_multi_field_wrappers( $wrappers ) {
		$wrappers['social-links'] = array(
			'facebook',
			'twitter',
			'linkedin',
			'pinterest',
			'skype',
		);
		return $wrappers;
	}

	/**
	 * Register the block JSON files from build/blocks/.
	 *
	 * @return void
	 */
	public function register_block_json_files() {
		$directory = LSX_TO_TEAM_PATH . 'build/blocks/';

		if ( ! is_dir( $directory ) ) {
			return;
		}

		foreach ( glob( $directory . '*', GLOB_ONLYDIR ) as $block_dir ) {
			register_block_type( $block_dir );
		}
	}
}
