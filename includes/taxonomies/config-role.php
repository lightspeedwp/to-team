<?php
/**
 * Tour Operator - Role taxonomy config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$taxonomy = array(
	'object_types'  => 'team',
	'menu_position' => 77,
	'args'          => array(
		'hierarchical'        => true,
		'labels'              => array(
			'name'              => esc_html__( 'Roles', 'to-team' ),
			'singular_name'     => esc_html__( 'Role', 'to-team' ),
			'search_items'      => esc_html__( 'Search Roles', 'to-team' ),
			'all_items'         => esc_html__( 'Roles', 'to-team' ),
			'parent_item'       => esc_html__( 'Parent', 'to-team' ),
			'parent_item_colon' => esc_html__( 'Parent:', 'to-team' ),
			'edit_item'         => esc_html__( 'Edit Role', 'to-team' ),
			'update_item'       => esc_html__( 'Update Role', 'to-team' ),
			'add_new_item'      => esc_html__( 'Add New Role', 'to-team' ),
			'new_item_name'     => esc_html__( 'New Role', 'to-team' ),
			'menu_name'         => esc_html__( 'Roles', 'to-team' ),
		),
		'show_ui'             => true,
		'public'              => true,
		'show_tagcloud'       => false,
		'exclude_from_search' => true,
		'show_admin_column'   => true,
		'query_var'           => true,
		'rewrite'             => array( 'role' ),
	),
);

return $taxonomy;
