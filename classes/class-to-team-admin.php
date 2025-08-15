<?php
/**
 * LSX_TO_Team_Admin
 *
 * @package   LSX_TO_Team_Admin
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Team_Admin
 * @author  LightSpeed
 */
class LSX_TO_Team_Admin extends LSX_TO_Team {

	/**
	 * The post type slug
	 *
	 * @var string
	 */
	public $post_type = 'team';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_action( 'init', array( $this, 'init' ), 20 );
		add_action( 'init', array( $this, 'register_post_type' ), 100 );
		add_action( 'cmb2_admin_init', array( $this, 'register_cmb2_fields' ) );

		add_filter( 'lsx_get_taxonomies_configs', array( $this, 'taxonomy_config' ), 10, 1 );

		add_filter( 'lsx_to_post_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_special_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_review_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );
	}

	/**
	 * Output the form field for this metadata when adding a new term
	 *
	 * @since 0.1.0
	 */
	public function init() {
		if ( function_exists( 'lsx_to_get_taxonomies' ) ) {
			$this->taxonomies = array_keys( lsx_to_get_taxonomies() );
		}

		if ( false !== $this->taxonomies ) {
			add_action( 'create_term', array( $this, 'save_meta' ), 10, 2 );
			add_action( 'edit_term', array( $this, 'save_meta' ), 10, 2 );

			foreach ( $this->taxonomies as $taxonomy ) {
				add_action( "{$taxonomy}_edit_form_fields", array( $this, 'add_expert_form_field' ), 3, 1 );
			}
		}
	}

	/**
	 * Registers the custom post type for the content model.
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type(
			'team',
			require_once LSX_TO_TEAM_PATH . '/includes/post-types/config-' . $this->post_type . '.php'
		);
	}

	/**
	 * Register the Role taxonomy
	 *
	 *
	 * @return    null
	 */
	public function taxonomy_config( $taxonomies ) {
		if ( file_exists( LSX_TO_TEAM_PATH . 'includes/taxonomies/config-role.php' ) ) {
			$taxonomies['role'] = include LSX_TO_TEAM_PATH . 'includes/taxonomies/config-role.php';
		}

		return 	$taxonomies;
	}

	/**
	 * Adds in the fields to the Tour Operators Post Types.
	 */
	public function custom_fields( $fields ) {
		global $post, $typenow, $current_screen;

		// @phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && $current_screen->post_type ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( isset( $_REQUEST['post'] ) ) {
			$post_type = get_post_type( sanitize_key( $_REQUEST['post'] ) );
		} else {
			$post_type = false;
		}
		// @phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( false !== $post_type ) {
			$fields[] = array(
				'id' => 'team_to_' . $post_type,
				'name' => 'Team members related to this ' . $post_type,
				'type' => 'pw_multiselect',
				'use_ajax' => false,
				'repeatable' => false,
				'allow_none' => true,
				'options'  => array(
					'post_type_args' => 'team',
				),
			);
		}

		return $fields;
	}

	/**
	 * Output the form field for this metadata when adding a new term
	 *
	 * @since 0.1.0
	 */
	public function add_expert_form_field( $term = false ) {
		if ( is_object( $term ) ) {
			$value = get_term_meta( $term->term_id, 'expert', true );
		} else {
			$value = false;
		}

		$experts = get_posts(
			array(
				'post_type' => 'team',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			)
		);
		?>
		<tr class="form-field form-required term-expert-wrap">
			<th scope="row">
				<label for="expert"><?php esc_html_e( 'Expert', 'to-team' ); ?></label>
			</th>
			<td>
				<select name="expert" id="expert" aria-required="true">
					<option value=""><?php esc_html_e( 'None', 'to-team' ); ?></option>

					<?php
						foreach ( $experts as $expert ) {
						echo '<option value="' . esc_attr( $expert->ID ) . '"' . selected( $value, $expert->ID, false ) . '>' . esc_html( $expert->post_title ) . '</option>';
						}
					?>
				</select>

				<?php wp_nonce_field( 'lsx_to_team_save_term_expert', 'lsx_to_team_term_expert_nonce' ); ?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Saves the Taxnomy term banner image
	 *
	 * @since 0.1.0
	 *
	 * @param  int     $term_id
	 * @param  string  $taxonomy
	 */
	public function save_meta( $term_id = 0, $taxonomy = '' ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['expert'] ) ) {
			return;
		}

		if ( check_admin_referer( 'lsx_to_team_save_term_expert', 'lsx_to_team_term_expert_nonce' ) ) {
			$meta = ! empty( sanitize_text_field( wp_unslash( $_POST['expert'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['expert'] ) )	: '';

			if ( empty( $meta ) ) {
				delete_term_meta( $term_id, 'expert' );
			} else {
				update_term_meta( $term_id, 'expert', $meta );
			}
		}
	}

	/**
	 * Registers the CMB2 custom fields
	 *
	 * @return void
	 */
	public function register_cmb2_fields() {
		/**
		 * Initiate the metabox
		 */
		$cmb = [];
		$fields = include( LSX_TO_TEAM_PATH . 'includes/metaboxes/config-' . $this->post_type . '.php' );

		$metabox_counter = 1;
		$cmb[ $metabox_counter ] = new_cmb2_box( array(
			'id'            => 'lsx_to_metabox_' . $this->post_type . '_' . $metabox_counter,
			'title'         => $fields['title'],
			'object_types'  => array( $this->post_type ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
		) );

		foreach ( $fields['fields'] as $field ) {

			if ( 'title' === $field['type'] ) {
				$metabox_counter++;
				$cmb[ $metabox_counter ] = new_cmb2_box( array(
					'id'            => 'lsx_to_metabox_' . $this->post_type . '_' . $metabox_counter,
					'title'         => $field['name'],
					'object_types'  => array( $this->post_type ), // Post type
					'context'       => 'normal',
					'priority'      => 'high',
					'show_names'    => true,
				) );
				continue;
			}

			/**
			 * Fixes for the extensions
			 */
			if ( 'post_select' === $field['type'] || 'post_ajax_search' === $field['type'] ) {
				$field['type'] = 'pw_multiselect';
			}

			$cmb[ $metabox_counter ]->add_field( $field );
		}
	}
}

new LSX_TO_Team_Admin();
