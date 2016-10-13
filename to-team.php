<?php
/*
 * Plugin Name:	Tour Operator Team
 * Plugin URI:	{plugin-url}
 * Description:	{plugin-description}
 * Author:		{your-name}
 * Version: 	1.0.0
 * Author URI: 	{your-url}
 * License: 	GPL2+
 * Text Domain: to-team
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('TO_TEAM_PATH',  plugin_dir_path( __FILE__ ) );
define('TO_TEAM_CORE',  __FILE__ );
define('TO_TEAM_URL',  plugin_dir_url( __FILE__ ) );
define('TO_TEAM_VER',  '1.0.0' );

/**
 * Runs once when the plugin is activated.
 */
function to_team_activate_plugin() {
	//Insert code here
}
register_activation_hook( __FILE__, 'to_team_activate_plugin' );

/* ======================= Below is the Plugin Class init ========================= */

require_once( TO_TEAM_PATH . '/classes/class-to-team.php' );