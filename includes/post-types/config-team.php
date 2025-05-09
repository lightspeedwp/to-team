<?php
/**
 * Tour Operator - Team Post Type config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$post_type = array(
	'class'               => 'LSX_TO_Team',
	'menu_icon'           => 'dashicons-id-alt',
	'labels'              => array(
		'name'               => esc_html__( 'Team', 'to-team' ),
		'singular_name'      => esc_html__( 'Team Member', 'to-team' ),
		'add_new'            => esc_html__( 'Add New', 'to-team' ),
		'add_new_item'       => esc_html__( 'Add New Team Member', 'to-team' ),
		'edit_item'          => esc_html__( 'Edit', 'to-team' ),
		'new_item'           => esc_html__( 'New', 'to-team' ),
		'all_items'          => esc_html__( 'Team', 'to-team' ),
		'view_item'          => esc_html__( 'View', 'to-team' ),
		'search_items'       => esc_html__( 'Search the Team', 'to-team' ),
		'not_found'          => esc_html__( 'No team member found', 'to-team' ),
		'not_found_in_trash' => esc_html__( 'No team member found in Trash', 'to-team' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Team', 'to-team' ),
		'featured_image'	=> esc_html__( 'Profile Picture', 'to-team' ),
		'set_featured_image'	=> esc_html__( 'Set Profile Picture', 'to-team' ),
		'remove_featured_image'	=> esc_html__( 'Remove profile picture', 'to-team' ),
		'use_featured_image'	=> esc_html__( 'Use as profile picture', 'to-team' ),
	),
	'public'              => true,
	'publicly_queryable'  => true,
	'show_ui'             => true,
	'show_in_menu'        => true,
	'menu_position'       => 35,
	'query_var'           => true,
	'rewrite'             => array(
		'slug'       => 'team',
		'with_front' => false,
	),
	'exclude_from_search' => false,
	'capability_type'     => 'post',
	'has_archive'         => 'team',
	'hierarchical'        => false,
	'show_in_rest'        => true,
	'supports'            => array(
		'title',
		'slug',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	),
);

return $post_type;
