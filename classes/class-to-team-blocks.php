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
