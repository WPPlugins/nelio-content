<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
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
	 * @param string $plugin_name  The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}//end __construct()

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->plugin_name,
			NELIO_CONTENT_PUBLIC_URL . '/css/public.css',
			array(),
			$this->version,
			'all'
		);

	}//end enqueue_styles()

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->plugin_name,
			NELIO_CONTENT_PUBLIC_URL . '/js/public.js',
			array( 'jquery' ),
			$this->version,
			false
		);

	}//end enqueue_scripts()

	/**
	 * Adds the Calendar entry in the admin bar (under the "Site" menu) and
	 * shortcuts to all blogs' calendars in a multisite installation (under the
	 * "My Sites" menu).
	 *
	 * @since  1.0.3
	 * @access public
	 */
	public function add_calendar_in_admin_bar() {

		if ( nc_is_subscribed_to( 'team-plan' ) ) {

			if ( ! nc_is_current_user( 'contributor' ) ) {
				return;
			}//end if

		} elseif ( ! nc_is_current_user_the_manager() ) {
				return;
		}//end if

		global $wp_admin_bar;

		$wp_admin_bar->add_node( array(
			'parent' => 'site-name',
			'id'     => 'nelio-content-calendar',
			'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
			'href'   => admin_url( 'admin.php?page=nelio-content' ),
		) );

		if ( is_multisite() ) {

			$original_blog_id = get_current_blog_id();

			// Add this option for each blog in "My Sites" where current user has
			// access to the calendar.
			foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

				switch_to_blog( $blog->userblog_id );

				if ( ! $this->is_calendar_available() ) {
					continue;
				}//end if

				$wp_admin_bar->add_node( array(
					'parent' => 'blog-' . get_current_blog_id(),
					'id'     => 'nelio-content-calendar-blog-' . get_current_blog_id(),
					'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
					'href'   => admin_url( 'admin.php?page=nelio-content' ),
				) );

			}//end foreach

			switch_to_blog( $original_blog_id );

		}//end if

	}//end add_calendar_in_admin_bar()

	/**
	 * Returns whether the current user can access Nelio Content's calendar.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	private function is_calendar_available() {

		if ( nc_is_subscribed_to( 'team-plan' ) ) {

			if ( ! nc_is_current_user( 'contributor' ) ) {
				return false;
			}//end if

		} elseif ( ! nc_is_current_user_the_manager() ) {
				return false;
		}//end if

		return true;

	}//end is_calendar_available()

}//end class
