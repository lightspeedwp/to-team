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
		
		// Register our block patterns.
		add_action( 'init', array( $this, 'register_block_patterns' ), 11 );

		// BLock Helpers
		add_filter( 'lsx_to_multi_field_wrappers', array( $this, 'register_multi_field_wrappers' ) );
		add_filter( 'render_block_core/social-links', array( $this, 'render_team_social_links' ), 10, 3 );
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

	/**
	 * Overrides the URLs of the icons inside the "Team - Social Links" block
	 * variation with the team member's matching custom fields, since block
	 * bindings don't support the `url` attribute of `core/social-link`.
	 * Scoped to blocks carrying the `lsx-team-social-links` class so it never
	 * touches unrelated uses of the core Social Icons block. Icons whose
	 * field is empty are removed entirely.
	 *
	 * @param string   $block_content The original block content.
	 * @param array    $parsed_block  Parsed block data, including attrs.
	 * @param WP_Block $block_obj     The block instance.
	 * @return string
	 */
	public function render_team_social_links( $block_content, $parsed_block, $block_obj ) {
		$class_name = $parsed_block['attrs']['className'] ?? '';

		if ( false === strpos( $class_name, 'lsx-team-social-links' ) ) {
			return $block_content;
		}

		if ( 'team' !== get_post_type() ) {
			return $block_content;
		}

		$service_to_field = array(
			'facebook'  => 'facebook',
			'x'         => 'twitter',
			'twitter'   => 'twitter',
			'linkedin'  => 'linkedin',
			'pinterest' => 'pinterest',
			'skype'     => 'skype',
		);

		foreach ( $service_to_field as $service => $field ) {
			$pattern = '/<li\b[^>]*\bwp-social-link-' . preg_quote( $service, '/' ) . '\b[^>]*>.*?<\/li>/is';

			if ( ! preg_match( $pattern, $block_content ) ) {
				continue;
			}

			$url = get_post_meta( get_the_ID(), $field, true );

			if ( empty( $url ) ) {
				$block_content = preg_replace( $pattern, '', $block_content, 1 );
				continue;
			}

			$url = $this->format_social_url( $url );

			$block_content = preg_replace_callback(
				$pattern,
				function ( $matches ) use ( $url ) {
					return preg_replace( '/href="[^"]*"/', 'href="' . esc_url( $url ) . '"', $matches[0], 1 );
				},
				$block_content,
				1
			);
		}

		return $block_content;
	}

	/**
	 * Mirrors the URL normalisation core applies in render_block_core_social_link()
	 * (mailto: for emails, https:// for schemeless URLs).
	 *
	 * @param string $url
	 * @return string
	 */
	private function format_social_url( $url ) {
		if ( is_email( $url ) ) {
			return 'mailto:' . antispambot( $url );
		}

		if ( ! parse_url( $url, PHP_URL_SCHEME ) && ! str_starts_with( $url, '//' ) && ! str_starts_with( $url, '#' ) ) {
			return 'https://' . $url;
		}

		return $url;
	}

	/**
	 * Registers block patterns from the patterns directory.
	 *
	 * Loads patterns from the root /patterns/ directory.
	 *
	 * @since 1.0.0
	 * @since 2.1.0 Updated to load from root /patterns/ directory.
	 *
	 * @return void
	 */
	public function register_block_patterns() {
		$directory = LSX_TO_PATH . 'patterns/';

		if ( ! is_dir( $directory ) ) {
			return;
		}

		foreach ( glob( $directory . '*.php' ) as $file ) {
			// Extract the filename without the directory path and extension.
			$filename = basename( $file, '.php' );

			// Use the filename to create the key.
			$key = 'lsx-tour-operator/' . $filename;

			// Check if pattern is already registered.
			if ( \WP_Block_Patterns_Registry::get_instance()->is_registered( $key ) ) {
				continue;
			}

			// Require the file and register the pattern.
			register_block_pattern( $key, require $file );
		}
	}
}
