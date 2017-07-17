<?php
/**
 * Setup menus in the WordPress Dashboard.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Setups Nelio Content's menu and submenus in the WordPress Dashboard.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.TooManyPublicMethods )
 */
class Nelio_Content_Admin_Menus {

	/**
	 * The identifying name of Nelio Content's menus and submenus.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $menu_slug;

	/**
	 * Menu's position.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_menu_page
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $position;

	/**
	 * Initializes the class and sets its properties.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {

		$this->position  = '25';

		if ( nc_is_subscribed_to( 'team-plan' ) || nc_is_current_user_the_manager() ) {

			$this->menu_slug = 'nelio-content';

		} else {

			$this->menu_slug = 'nelio-content-account';

		}//end if

	}//end __construct()

	/**
	 * Registers the requried hooks for adding a simplified version of the
	 * plugin's menu in the Dashboard.
	 *
	 * This menu should only be used when the plugins is not setup yet.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_setup_hooks() {

		if ( ! nc_is_current_user( 'administrator' ) ) {
			return;
		}//end if

		add_action( 'admin_menu', array( $this, 'create_menu_entry' ), 1 );
		add_action( 'admin_menu', array( $this, 'add_setup_page' ), 20 );

		add_filter( 'custom_menu_order', '__return_true', 99 );
		add_filter( 'menu_order', array( $this, 'fix_separator_location' ) );

	}//end define_setup_hooks()

	/**
	 * Registers the requried hooks for adding the plugin's menu in the Dashboard.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_admin_hooks() {

		if ( ! nc_is_current_user( 'administrator' ) &&
			! nc_is_subscribed_to( 'team-plan' ) &&
			! nc_is_current_user_the_manager() ) {
			return;
		}//end if

		add_action( 'admin_init', array( $this, 'register_standalone_pages' ), 1 );

		add_action( 'admin_menu', array( $this, 'create_menu_entry' ), 1 );
		add_action( 'admin_menu', array( $this, 'add_custom_pages' ), 20 );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 40 );
		add_action( 'admin_menu', array( $this, 'add_help_menu' ), 90 );

		add_action( 'admin_bar_menu', array( $this, 'add_calendar_in_admin_bar' ), 99 );

		add_filter( 'custom_menu_order', '__return_true', 99 );
		add_filter( 'menu_order', array( $this, 'fix_separator_location' ) );

		add_filter( 'option_page_capability_nelio-content_group', array( $this, 'get_capability_for_updating_options' ) );

	}//end define_admin_hooks()

	/**
	 * Returns the slug of the menu, so that plugins can add their own entries.
	 *
	 * @return string the slug of the menu, so that plugins can add their own entries.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_menu_slug() {

		return $this->menu_slug;

	}//end get_menu_slug()

	/**
	 * Creates a new menu entry.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function create_menu_entry() {

		global $menu;

		// Load default icon.
		$svg_icon_file = NELIO_CONTENT_ADMIN_DIR . '/images/logo.svg';
		$svg = 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( $svg_icon_file ) ); // @codingStandardsIgnoreLine

		$menu[] = array(
			'',
			'read',
			'separator-nelio-content',
			'',
			'wp-menu-separator nelio-content',
		);

		// Contents of the parent menu.
		add_menu_page(
			_x( 'Nelio Content', 'text (menu)', 'nelio-content' ),
			_x( 'Nelio Content', 'text (menu)', 'nelio-content' ),
			'edit_posts',
			$this->menu_slug,
			null,
			$svg,
			$this->position
		);

	}//end create_menu_entry()

	/**
	 * Adds Nelio Content custom pages to the menu.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_custom_pages() {

		if ( ! nc_is_subscribed_to( 'team-plan' ) &&
				! nc_is_current_user_the_manager() ) {
			return;
		}//end if

		add_submenu_page( $this->menu_slug,
			_x( 'Calendar', 'text (menu)', 'nelio-content' ),
			_x( 'Calendar', 'text (menu)', 'nelio-content' ),
			'edit_posts',
			$this->menu_slug,
			array( $this, 'print_calendar_page' )
		);

		$settings = Nelio_Content_Settings::instance();
		if ( $settings->get( 'use_analytics' ) && intval( get_option( 'nc_analytics_last_global_update' ) ) ) {

			add_submenu_page( $this->menu_slug,
				_x( 'Analytics', 'text (menu)', 'nelio-content' ),
				_x( 'Analytics', 'text (menu)', 'nelio-content' ),
				'publish_posts',
				'nelio-content-analytics',
				array( $this, 'print_analytics_page' )
			);

		} else {

			add_submenu_page( $this->menu_slug,
				_x( 'Analytics', 'text (menu)', 'nelio-content' ),
				_x( 'Analytics', 'text (menu)', 'nelio-content' ),
				'edit_others_posts',
				'nelio-content-analytics',
				array( $this, 'print_analytics_welcome_page' )
			);

		}//end if

	}//end add_custom_pages()

	/**
	 * Returns the required capability for updating Nelio Content's options.
	 *
	 * @return string the required capability for updating Nelio Content's options.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_capability_for_updating_options() {

		return 'publish_posts';

	}//end get_capability_for_updating_options()

	/**
	 * Adds "Subscription" (or "Upgrade") and the "Settings" submenu entries.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_settings_page() {

		// Subscription page, only available to administrators.
		add_submenu_page( $this->menu_slug,
			_x( 'Account Details', 'text (menu)', 'nelio-content' ),
			_x( 'Account Details', 'text (menu)', 'nelio-content' ),
			'manage_options',
			'nelio-content-account',
			array( $this, 'print_account_page' )
		);

		// Settings page. Depending on the capabilities of the user,
		// we show all the options, or just the social profile manager.
		if ( nc_is_subscribed_to( 'team-plan' ) ) {

			if ( nc_is_current_user( 'editor' ) ) {

				add_submenu_page( $this->menu_slug,
					_x( 'Settings', 'text (menu)', 'nelio-content' ),
					_x( 'Settings', 'text (menu)', 'nelio-content' ),
					$this->get_capability_for_updating_options(),
					'nelio-content-settings',
					array( $this, 'print_settings_page' )
				);

			} elseif ( nc_is_current_user( 'author', 'exactly' ) ) {

				add_submenu_page( $this->menu_slug,
					_x( 'Settings', 'text (menu)', 'nelio-content' ),
					_x( 'Settings', 'text (menu)', 'nelio-content' ),
					$this->get_capability_for_updating_options(),
					'nelio-content-settings',
					array( $this, 'print_social_profile_settings_page' )
				);

			}//end if

		} elseif ( nc_is_current_user_the_manager() ) {

			add_submenu_page( $this->menu_slug,
				_x( 'Settings', 'text (menu)', 'nelio-content' ),
				_x( 'Settings', 'text (menu)', 'nelio-content' ),
				$this->get_capability_for_updating_options(),
				'nelio-content-settings',
				array( $this, 'print_settings_page' )
			);

		}//end if

	}//end add_settings_page()

	/**
	 * Adds the Help menu entry. That is, a link to our website.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_help_menu() {

		add_submenu_page( $this->menu_slug,
			_x( 'Help', 'text (menu)', 'nelio-content' ),
			_x( 'Help', 'text (menu)', 'nelio-content' ),
			'edit_posts',
			'nelio-content-help'
		);

		global $submenu;
		if ( isset( $submenu[ $this->menu_slug ] ) ) {
			$count = count( $submenu[ $this->menu_slug ] );
			for ( $i = 0; $i < $count; ++$i ) {
				if ( 'nelio-content-help' === $submenu[ $this->menu_slug ][ $i ][2] ) {
					$submenu[ $this->menu_slug ][ $i ][2] = __( 'https://neliosoftware.com/content/help/', 'nelio-content' );
					break;
				}//end if
			}//end for
		}//end if

	}//end add_help_menu()

	/**
	 * Adds the Calendar entry in the admin bar (under the "Site" menu) and
	 * shortcuts to all blogs' calendars in a multisite installation (under the
	 * "My Sites" menu).
	 *
	 * @since  1.0.3
	 * @access public
	 */
	public function add_calendar_in_admin_bar() {

		global $wp_admin_bar;

		if ( $this->is_calendar_available() && ! is_network_admin() ) {

			// Add the option under the "Site" menu.
			$wp_admin_bar->add_node( array(
				'parent' => 'site-name',
				'id'     => 'nelio-content-calendar',
				'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
				'href'   => admin_url( 'admin.php?page=nelio-content' ),
			) );

		}//end if

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

	/**
	 * Adds a secondary "Add Experiment" entry and the Dashboard.
	 *
	 * The secondary "Add Experiment" entry is added so that we can have two
	 * different pages.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_setup_page() {

		add_submenu_page( $this->menu_slug,
			_x( 'Setup', 'text (menu)', 'nelio-content' ),
			_x( 'Setup', 'text (menu)', 'nelio-content' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'print_setup_page' )
		);

	}//end add_setup_page()

	/**
	 * Prints the calendar page and includes all required underscore templates.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_calendar_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-calendar-page.php' );

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/calendar/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/editorial-comments/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/editorial-tasks/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-message-editor/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/links/broken-links/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/links/references/underscore-templates.php' );

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-dialog.php' );

	}//end print_calendar_page()

	/**
	 * This function adds the analytics welcome page.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function print_analytics_welcome_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-analytics-welcome-page.php' );

	}//end print_analytics_welcome_page()

	/**
	 * This function adds the analytics page and includes all required underscore templates.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function print_analytics_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-analytics-page.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/analytics/underscore-templates.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-message-editor/underscore-templates.php' );

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-dialog.php' );

	}//end print_analytics_page()

	/**
	 * This function adds the account page and includes all required underscore templates.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_activity_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-activity-page.php' );

	}//end print_activity_page()

	/**
	 * This function adds the account page and includes all required underscore templates.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_account_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-account-page.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/account/underscore-templates.php' );

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-dialog.php' );

	}//end print_account_page()

	/**
	 * Prints the Settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_settings_page() {

		Nelio_Content_Settings::instance();
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-settings-page.php' );

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-dialog.php' );

	}//end print_settings_page()

	/**
	 * Prints the Social Profile Settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_social_profile_settings_page() {

		Nelio_Content_Settings::instance();
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-social-profile-settings-page.php' );

	}//end print_social_profile_settings_page()

	/**
	 * Prints the Setup page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_setup_page() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-setup-page.php' );

	}//end print_setup_page()

	/**
	 * This function re-orders top-level menus. It "ensures" that Nelio Content's
	 * menu has its own block, separated from the rest.
	 *
	 * @param array $menu_order An ordered array of menu items.
	 *
	 * @return array An array with a reordered list of menu items.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function fix_separator_location( $menu_order ) {

		// Initialize our custom order array.
		$new_menu_order = array();

		// Define Nelio Content menu entries.
		$nelio_menu_items = array(
			$this->menu_slug,
			'separator-nelio-content',
		);

		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $item ) {

			if ( $this->menu_slug === $item ) {

				array_push( $new_menu_order, 'separator-nelio-content' );
				array_push( $new_menu_order, $this->menu_slug );

			} elseif ( ! in_array( $item, $nelio_menu_items, true ) ) {

				array_push( $new_menu_order, $item );

			}//end if

		}//end foreach

		// Return order.
		return $new_menu_order;

	}//end fix_separator_location()

	/**
	 * Adds pages in WordPress that do not appear in the side menu.
	 *
	 * In order to do that, a special `GET` parameter is used: `nc-page`.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_standalone_pages() {

		global $pagenow;

		if ( 'admin.php' !== $pagenow || ! isset( $_GET['nc-page'] ) ) { // @codingStandardsIgnoreLine
			return;
		}//end if

		switch ( $_GET['nc-page'] ) { // @codingStandardsIgnoreLine

			case 'connected-profile-callback':
				require_once( NELIO_CONTENT_ADMIN_DIR . '/views/nelio-content-connected-profile-callback.php' );
				die();

		}//end if

	}//end register_standalone_pages()

}//end class
