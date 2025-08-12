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

$user_query = new WP_User_Query(
	array(
		'role__not_in' => 'Subscriber',
	)
);

$users        = array();
$user_results = $user_query->get_results();
if ( ! empty( $user_results ) ) {
	foreach ( $user_results as $user ) {
		$users = array(
			'name' => $user->display_name,
			'value' => $user->ID,
		);
	}
}

$metabox['fields'][] = array(
	'name' => esc_html__( 'Site User', 'to-team' ),
	'id' => 'site_user',
	'allow_none' => true,
	'type' => 'select',
	'options' => $users,
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
	'name' => esc_html__( 'Media', 'to-team' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
    'name' => esc_html__( 'Gallery', 'tour-operator-team' ),
	'desc' => esc_html__( 'Add images related to the accommodation to be displayed in the Accommodation\'s gallery.', 'tour-operator-team' ),
    'id'   => 'gallery',
    'type' => 'file_list',
    'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
    'query_args' => array( 'type' => 'image' ), // Only images attachment
    'text' => array(
        'add_upload_files_text' => esc_html__( 'Add new image', 'tour-operator-team' ), // default: "Add or Upload Files"
    ),
);

if ( class_exists( 'Envira_Gallery' ) ) {

	$metabox['fields'][] = array(
		'id'         => 'envira_gallery',
		'name'       => esc_html__( 'Envira Gallery', 'to-team' ),
		'type'       => 'pw_multiselect',
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
			'type'       => 'pw_multiselect',
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
	'post'				=> esc_html__( 'Posts', 'to-team' ),
);

$metabox['fields'][] = array(
	'id'   => 'related_title',
	'name' => esc_html__( 'Related', 'to-team' ),
	'type' => 'title',
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'         => $slug . '_to_team',
		'name'       => $label . esc_html__( ' related with this team', 'to-team' ),
		'type'       => 'pw_multiselect',
		'use_ajax'   => false,
		'repeatable' => false,
		'allow_none' => true,
		'options'  => array(
			'post_type_args' => $slug,
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_team_custom_fields', $metabox['fields'] );

return $metabox;
