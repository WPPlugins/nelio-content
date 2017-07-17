<?php
/**
 * This file contains the most essential hooks required for configuring for the
 * plugin the first time.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class registers a simplified menu for configuring the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Setup {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name  The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}//end __construct()

	/**
	 * Includes all required classes.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_admin_menus() {

		if ( ! defined( 'DOING_AJAX' ) ) {
			require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-admin-menus.php' );
			$admin_menus = new Nelio_Content_Admin_Menus();
			$admin_menus->define_setup_hooks();
		}//end if

	}//end add_admin_menus()

	/**
	 * Enqueue dashicons in setup page.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function enqueue_styles() {

		// These styles can only be included if we're on a Nelio Content's page.
		$screen = get_current_screen();
		if ( 0 !== strpos( $screen->id, 'toplevel_page_nelio-content' ) ) {
			return;
		}//end if

		// Dashicons from WordPress 4.5.
		wp_enqueue_style(
			'nc-setup',
			NELIO_CONTENT_ADMIN_URL . '/css/setup.min.css',
			array(),
			$this->version,
			'all'
		);

		// Dashicons from WordPress 4.5.
		wp_enqueue_style(
			'dashicons-wp45',
			NELIO_CONTENT_ADMIN_URL . '/lib/dashicons/css/dashicons.css',
			array(),
			'4.5.0',
			'all'
		);

	}//end enqueue_styles()

}//end class

