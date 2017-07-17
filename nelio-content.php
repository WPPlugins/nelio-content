<?php
/**
 * The plugin bootstrap file.
 *
 * Plugin Name: Nelio Content
 * Plugin URI:  https://neliosoftware.com/content
 * Description: Auto-post, schedule, and share your posts on Twitter, Facebook, LinkedIn, Instagram, and other social networks. Save time with useful automations.
 * Version:     1.2.14
 *
 * Author:      Nelio Software
 * Author URI:  https://neliosoftware.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: nelio-content
 * Domain Path: /languages
 *
 * @package    Nelio_Content
 * @subpackage Root
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}//end if

define( 'NELIO_CONTENT_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'NELIO_CONTENT_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nelio-content-activator.php
 */
function activate_nelio_content() {
	require_once( NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-activator.php' );
	Nelio_Content_Activator::activate();
}//end activate_nelio_content()

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nelio-content-deactivator.php
 */
function deactivate_nelio_content() {
	require_once( NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-deactivator.php' );
	Nelio_Content_Deactivator::deactivate();
}//end deactivate_nelio_content()

register_activation_hook( __FILE__, 'activate_nelio_content' );
register_deactivation_hook( __FILE__, 'deactivate_nelio_content' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @noinspection PhpIncludeInspection
 */

// The core plugin class that is used to define internationalization,
// admin-specific hooks, and public-facing site hooks.
require( NELIO_CONTENT_DIR . '/includes/class-nelio-content.php' );

// Let's begin the execution of the plugin.
//
// Since everything within the plugin is registered via hooks,
// then kicking off the plugin from this point in the file does
// not affect the page life cycle.
Nelio_Content();
