<?php
/**
 * Tour Operator - Team Metabox config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

global $lsx_to_team;

$metabox = array(
	'title'  => esc_html__( 'Tour Operator Plugin', 'to-team' ),
	'pages'  => 'team',
	'fields' => array(),
);

$metabox['fields'][] = array(
	'id'   => 'featured',
	'name' => esc_html__( 'Featured', 'to-team' ),
	'type' => 'checkbox',
);

$metabox['fields'][] = array(
	'id'   => 'disable_single',
	'name' => esc_html__( 'Disable Single', 'to-team' ),
	'type' => 'checkbox',
);

if ( ! class_exists( 'LSX_Banners' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'tagline',
		'name' => esc_html__( 'Tagline', 'to-team' ),
		'type' => 'text',
	);
}

$metabox['fields'][] = array(
	'id'	=> 'role',
	'name'	=> esc_html__( 'Role', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'name' => esc_html__( 'Site User', 'to-team' ),
	'id' => 'site_user',
	'allow_none' => true,
	'type' => 'select',
	'options' => $lsx_to_team->site_users,
);

$metabox['fields'][] = array(
	'id'	=> 'contact_title',
	'name'	=> esc_html__( 'Contact', 'to-team' ),
	'type'	=> 'title',
);

$metabox['fields'][] = array(
	'id'	=> 'contact_email',
	'name'	=> esc_html__( 'Email', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'contact_number',
	'name'	=> esc_html__( 'Number (international format)', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'skype',
	'name'	=> esc_html__( 'Skype', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'social_title',
	'name'	=> esc_html__( 'Social Profiles', 'to-team' ),
	'type'	=> 'title',
);

$metabox['fields'][] = array(
	'id'	=> 'facebook',
	'name'	=> esc_html__( 'Facebook', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'twitter',
	'name'	=> esc_html__( 'Twitter', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'googleplus',
	'name'	=> esc_html__( 'Google Plus', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'linkedin',
	'name'	=> esc_html__( 'LinkedIn', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'	=> 'pinterest',
	'name'	=> esc_html__( 'Pinterest', 'to-team' ),
	'type'	=> 'text',
);

$metabox['fields'][] = array(
	'id'   => 'gallery_title',
	'name' => esc_html__( 'Gallery', 'to-team' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
	'id'         => 'gallery',
	'name'       => esc_html__( 'Gallery', 'to-team' ),
	'type'       => 'image',
	'repeatable' => true,
	'show_size'  => false,
);

if ( class_exists( 'Envira_Gallery' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'envira_title',
		'name' => esc_html__( 'Envira Gallery', 'to-team' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => 'envira_gallery',
		'name'       => esc_html__( 'Envira Gallery', 'to-team' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'envira',
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);

	if ( class_exists( 'Envira_Videos' ) ) {
		$metabox['fields'][] = array(
			'id'         => 'envira_video',
			'name'       => esc_html__( 'Envira Video Gallery', 'to-team' ),
			'type'       => 'post_select',
			'use_ajax'   => false,
			'allow_none' => true,
			'query'      => array(
				'post_type'      => 'envira',
				'nopagin'        => true,
				'posts_per_page' => '-1',
				'orderby'        => 'title',
				'order'          => 'ASC',
			),
		);
	}
}

$post_types = array(
	'accommodation'		=> esc_html__( 'Accommodation', 'to-team' ),
	'destination'		=> esc_html__( 'Destinations', 'to-team' ),
	'tour'				=> esc_html__( 'Tours', 'to-team' ),
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'   => $slug . '_title',
		'name' => $label,
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => $slug . '_to_team',
		'name'       => $label . esc_html__( ' related with this team', 'to-team' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'repeatable' => true,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => $slug,
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_team_custom_fields', $metabox['fields'] );

return $metabox;
