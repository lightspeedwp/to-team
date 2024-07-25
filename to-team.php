<?php
/*
 * Plugin Name:	LSX Tour Operator Team
 * Plugin URI:	https://www.lsdev.biz/product/tour-operator-team/
 * Description:	Real peoples' faces go a long way to building trust with your clients. The Team Extension allows your business's staff to be added as Team Members with their own profile which can be associated with specific destinations and tours.
 * Version:     2.0.0
 * Author:      LightSpeed
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: to-team
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_TO_TEAM_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TO_TEAM_CORE', __FILE__ );
define( 'LSX_TO_TEAM_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_TO_TEAM_VER', '2.0.0' );

if ( ! defined( 'TEAM_ARCHIVE_URL' ) ) {
	define( 'TEAM_ARCHIVE_URL', 'team-members' );
}

/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_TO_TEAM_PATH . '/classes/class-lsx-to-team.php';
