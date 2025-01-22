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
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_action( 'init', array( $this, 'init' ), 20 );

		add_filter( 'lsx_get_post-types_configs', array( $this, 'post_type_config' ), 10, 1 );
		add_filter( 'lsx_get_metaboxes_configs', array( $this, 'meta_box_config' ), 10, 1 );
		add_filter( 'lsx_get_taxonomies_configs', array( $this, 'taxonomy_config' ), 10, 1 );

		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_special_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_review_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_taxonomies', array( $this, 'to_register_taxonomy' ), 10, 1 );
		add_filter( 'lsx_to_framework_taxonomies', array( $this, 'to_register_taxonomy' ), 10, 1 );
		add_filter( 'lsx_to_framework_taxonomies_plural', array( $this, 'to_register_taxonomy_plural' ), 10, 1 );
	}

	/**
	 * Register the taxonomy with the TO plugin
	 *
	 * @since 0.1.0
	 */
	public function to_register_taxonomy( $taxonomies ) {
		$taxonomies['role'] = esc_attr__( 'Role', 'to-team' );
		return $taxonomies;
	}

	/**
	 * Register the taxonomy with the TO plugin
	 *
	 * @since 0.1.0
	 */
	public function to_register_taxonomy_plural( $taxonomies ) {
		$taxonomies['role'] = esc_attr__( 'Roles', 'to-team' );
		return $taxonomies;
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

		add_filter( 'lsx_to_taxonomy_widget_taxonomies', array( $this, 'widget_taxonomies' ), 10, 1 );

		if ( false !== $this->taxonomies ) {
			add_action( 'create_term', array( $this, 'save_meta' ), 10, 2 );
			add_action( 'edit_term', array( $this, 'save_meta' ), 10, 2 );

			foreach ( $this->taxonomies as $taxonomy ) {
				add_action( "{$taxonomy}_edit_form_fields", array( $this, 'add_expert_form_field' ), 3, 1 );
			}
		}

		add_action( 'lsx_to_framework_team_tab_content', array( $this, 'general_settings' ), 10, 2 );
		add_action( 'lsx_to_framework_team_tab_content', array( $this, 'archive_settings' ), 10, 2 );
	}

	/**
	 * Register the activity post type config
	 *
	 * @param  $objects
	 * @return   array
	 */
	public function post_type_config( $objects ) {
		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_TEAM_PATH . 'includes/post-types/config-' . $key . '.php' ) ) {
				$objects[ $key ] = include LSX_TO_TEAM_PATH . 'includes/post-types/config-' . $key . '.php';
			}
		}

		return	$objects;
	}

	/**
	 * Register the activity metabox config
	 *
	 * @param  $meta_boxes
	 * @return   array
	 */
	public function meta_box_config( $meta_boxes ) {
		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_TEAM_PATH . 'includes/metaboxes/config-' . $key . '.php' ) ) {
				$meta_boxes[ $key ] = include LSX_TO_TEAM_PATH . 'includes/metaboxes/config-' . $key . '.php';
			}
		}

		return 	$meta_boxes;
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
	 * Adds the team specific options
	 */
	public function general_settings( $post_type = false, $tab = false ) {
		if ( 'general' !== $tab ) {
			return false;
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
		<tr class="form-field">
			<th scope="row">
				<label for="disable_team_panel"><?php esc_html_e( 'Disable Team Panel', 'to-team' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if disable_team_panel}} checked="checked" {{/if}} name="disable_team_panel" />
				<small><?php esc_html_e( 'This disables the team member panel on all post types.', 'to-team' ); ?></small>
			</td>
		</tr>
		<tr class="form-field-wrap">
			<th scope="row">
				<label> Select your consultants</label>
			</th>
			<td>
				<?php foreach ( $experts as $expert ) : ?>
					<label for="expert-<?php echo esc_attr( $expert->ID ) ?>">
						<input type="checkbox" {{#if expert-<?php echo esc_attr( $expert->ID ) ?>}} checked="checked" {{/if}} name="expert-<?php echo esc_attr( $expert->ID ) ?>" id="expert-<?php echo esc_attr( $expert->ID ) ?>" value="<?php echo esc_attr( $expert->ID ) ?>" /> <?php echo esc_html( $expert->post_title ) ?>
					</label>
					<br>
				<?php endforeach ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Adds the team specific options
	 */
	public function archive_settings( $post_type = false, $tab = false ) {
		if ( 'archives' !== $tab ) {
			return false;
		}
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="group_items_by_role"><?php esc_html_e( 'Group by Role', 'to-team' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if group_items_by_role}} checked="checked" {{/if}} name="group_items_by_role" />
				<small><?php esc_html_e( 'This groups archive items by role taxonomy and display the role title.', 'to-team' ); ?></small>
			</td>
		</tr>
		<?php
	}
}

new LSX_TO_Team_Admin();
