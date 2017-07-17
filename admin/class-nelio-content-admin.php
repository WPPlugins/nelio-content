<?php
/**
 * The admin-specific functionality of the plugin.
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
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Admin {

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

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		$this->add_meta_boxes();

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
			$admin_menus->define_admin_hooks();
		}//end if

	}//end add_admin_menus()

	/**
	 * Registers all the library scripts that are used throughout the admin
	 * script.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_styles() {

		// Dashicons from WordPress 4.5.
		wp_register_style(
			'dashicons-wp45',
			NELIO_CONTENT_ADMIN_URL . '/lib/dashicons/css/dashicons.css',
			array(),
			'4.5.0',
			'all'
		);

		// Select2's default stylesheet.
		wp_register_style(
			'ncselect2',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/css/ncselect2.min.css',
			array(),
			'4.0.1',
			'all'
		);

	}//end register_styles()

	/**
	 * Enqueue the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue_styles() {

		// These styles can only be included if we're on a Nelio Content's page.
		if ( ! $this->is_a_nelio_content_page() ) {
			return;
		}//end if

		$this->register_styles();

		// TODO. If we ever drop support for versions before WordPress 4.5, you no
		// longer need custom dashicons. Remember, then, to change all nc-dashicons
		// from the templates to dashicons.
		wp_enqueue_style(
			$this->plugin_name,
			NELIO_CONTENT_ADMIN_URL . '/css/admin.min.css',
			array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog' ),
			$this->version,
			'all'
		);

		if ( $this->is_current_screen( '_nc-post-with-featured-image' ) ) {

			// Featured Images Meta Box script.
			wp_enqueue_style(
				'nelio-content-featured-images',
				NELIO_CONTENT_ADMIN_URL . '/css/featured-image.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-calendar-page' ) ) {

			// Load Fullcalendar's default stylesheet.
			wp_enqueue_style(
				'nelio-content-calendar',
				NELIO_CONTENT_ADMIN_URL . '/css/calendar.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-activity' ) ) {

			// Load activity page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-activity',
				NELIO_CONTENT_ADMIN_URL . '/css/activity.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-analytics' ) ) {

			// Load analytics page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-analytics',
				NELIO_CONTENT_ADMIN_URL . '/css/analytics.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-account' ) ) {

			// Load account page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-account',
				NELIO_CONTENT_ADMIN_URL . '/css/account.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-settings' ) ) {

			// Load setting page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-settings',
				NELIO_CONTENT_ADMIN_URL . '/css/settings.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

	}//end enqueue_styles()

	/**
	 * Enqueues stylesheet reset-defaults.css.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function enqueue_wp_default_style_fix() {

		if ( apply_filters( 'nelio_content_enqueue_wp_default_style_fix', true ) ) {

			// Load setting page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-wp-style-fix',
				NELIO_CONTENT_ADMIN_URL . '/css/reset-defaults.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

	}//end enqueue_wp_default_style_fix()

	/**
	 * Registers all the library scripts that are used throughout the admin
	 * script.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_scripts() {

		// Register DebugJS library.
		wp_register_script(
			'debugjs',
			NELIO_CONTENT_ADMIN_URL . '/lib/debug/js/debug.min.js',
			array(),
			'2.2.0'
		);

		// Register Select 2 script.
		wp_register_script(
			'ncselect2',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/js/ncselect2.full.min.js',
			array( 'jquery' ),
			'4.0.1'
		);

		wp_register_script(
			'ncselect2-i18n',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/js/i18n.js',
			array( 'ncselect2' ),
			'4.0.1'
		);

		// Register Moment JS, a library required by our calendar.
		wp_register_script(
			'moment-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/momentjs/js/moment-with-locales.min.js',
			array(),
			'2.12.0'
		);
		wp_register_script(
			'moment-timezone-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/momentjs/js/moment-timezone.min.js',
			array( 'moment-js' ),
			'0.5.3'
		);

		// Latinize library.
		wp_register_script(
			'latinize',
			NELIO_CONTENT_ADMIN_URL . '/lib/latinize/js/latinize.min.js',
			array(),
			'2b7ee0c'
		);

		// Store library.
		wp_register_script(
			'ncstore-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/storejs/js/store.min.js',
			array(),
			'1.3.20a'
		);

		// Twitter library.
		wp_register_script(
			'twitter-text',
			NELIO_CONTENT_ADMIN_URL . '/lib/twitter/js/twitter-text.min.js',
			array(),
			'1.14.3'
		);

		// Our backbone views.
		wp_register_script(
			'nelio-content-views',
			NELIO_CONTENT_ADMIN_URL . '/js/views.min.js',
			array( $this->plugin_name, 'backbone', 'underscore', 'moment-timezone-js', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-dialog' ),
			$this->version,
			true
		);

		// Free Trial dialog.
		wp_register_script(
			'nelio-content-free-trial',
			NELIO_CONTENT_ADMIN_URL . '/js/free-trial.min.js',
			array( $this->plugin_name, 'backbone', 'underscore', 'moment-timezone-js', 'jquery-ui-dialog' ),
			$this->version,
			true
		);

	}//end register_scripts()

	/**
	 * Enqueue the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	public function enqueue_scripts() {

		$this->register_scripts();

		// These admin JavaScripts can only be included if we're on a Nelio Content's page.
		if ( ! $this->is_a_nelio_content_page() ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();

		wp_enqueue_script(
			$this->plugin_name,
			NELIO_CONTENT_ADMIN_URL . '/js/admin.min.js',
			array( 'jquery', 'backbone', 'debugjs', 'moment-timezone-js', 'ncselect2-i18n', 'latinize', 'ncstore-js', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-tooltip', 'twitter-text' ),
			$this->version,
			false
		);

		wp_localize_script(
			$this->plugin_name,
			'NelioContent',
			$this->generate_js_object()
		);

		if ( $this->is_current_screen( '_nc-calendar-page' ) ) {

			// Enqueue media scripts for image selection.
			wp_enqueue_media();

			// Load calendar page customizations.
			wp_enqueue_script(
				'nelio-content-calendar-js',
				NELIO_CONTENT_ADMIN_URL . '/js/calendar.min.js',
				array( $this->plugin_name, 'nelio-content-views', 'nelio-content-free-trial' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-post-with-featured-image' ) ) {

			// Enqueue media scripts for image selection.
			wp_enqueue_media();

			// Featured Images Meta Box script.
			wp_enqueue_script(
				'nelio-content-featured-images',
				NELIO_CONTENT_ADMIN_URL . '/js/featured-image.min.js',
				array(),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) ) {
			if ( nc_is_subscribed_to( 'team-plan' ) || nc_is_current_user_the_manager() ) {

				// Load calendar page customizations.
				wp_enqueue_script(
					'nelio-content-post-js',
					NELIO_CONTENT_ADMIN_URL . '/js/post.min.js',
					array( $this->plugin_name, 'nelio-content-views' ),
					$this->version,
					true
				);

			}//end if
		}//end if

		if ( $this->is_current_screen( '_nc-analytics' ) &&
				$settings->get( 'use_analytics' ) &&
				intval( get_option( 'nc_analytics_last_global_update' ) ) ) {

			// Load analytics page customizations.
			wp_enqueue_script(
				'nelio-content-analytics-js',
				NELIO_CONTENT_ADMIN_URL . '/js/analytics.min.js',
				array( $this->plugin_name, 'nelio-content-views', 'nelio-content-free-trial' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-account' ) ) {

			// Load calendar page customizations.
			wp_enqueue_script(
				'nelio-content-account-js',
				NELIO_CONTENT_ADMIN_URL . '/js/account.min.js',
				array( $this->plugin_name, 'nelio-content-free-trial' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-settings' ) ) {

			// Load Fullcalendar's default stylesheet.
			wp_enqueue_script(
				'nelio-content-settings-js',
				NELIO_CONTENT_ADMIN_URL . '/js/settings.min.js',
				array( $this->plugin_name, $settings->get_generic_script_name(), 'nelio-content-free-trial' ),
				$this->version,
				true
			);

			wp_localize_script(
				'nelio-content-settings-js',
				'NelioContentSettings',
				array(
					'isYoastSEOAvailable' => is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ),
				)
			);

		}//end if

	}//end enqueue_scripts()

	/**
	 * Callback for adding meta boxes in the post editor page.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function add_meta_boxes() {

		require_once( NELIO_CONTENT_ADMIN_DIR . '/interface-nelio-content-meta-box.php' );

		/**
		 * Fires before Nelio Content adds its own meta boxes.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_pre_add_meta_boxes' );

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-featured-image-meta-box.php' );
		$meta_box = new Nelio_Content_Featured_Image_Meta_Box();
		$meta_box->init();

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-social-media-meta-box.php' );
		$meta_box = new Nelio_Content_Social_Media_Meta_Box();
		$meta_box->init();

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-links-meta-box.php' );
		$meta_box = new Nelio_Content_Links_Meta_Box();
		$meta_box->init();

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-post-analysis-meta-box-partial.php' );
		$meta_box = new Nelio_Content_Post_Analysis_Meta_Box_Partial();
		$meta_box->init();

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-editorial-tasks-meta-box.php' );
		$meta_box = new Nelio_Content_Editorial_Tasks_Meta_Box();
		$meta_box->init();

		require_once( NELIO_CONTENT_ADMIN_DIR . '/class-nelio-content-editorial-comments-meta-box.php' );
		$meta_box = new Nelio_Content_Editorial_Comments_Meta_Box();
		$meta_box->init();

		/**
		 * Fires after Nelio Content has added its own meta boxes.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_add_meta_boxes' );

	}//end add_meta_boxes()

	/**
	 * Returns whether the current screen is one of the specified screens.
	 *
	 * @param string|array $screen_options a single screen name or a list (array)
	 *                            of screen names. The options can either be an
	 *                            actual screen name (such as `nelio-content_page_nelio-content`),
	 *                            or a beautifed version of Nelio's screens
	 *                            (`calendar-page`).
	 *
	 * @return boolean whether the current screen is one of the specified screens.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function is_current_screen( $screen_options ) {

		$screen = get_current_screen();
		$post_types = get_post_types();

		if ( ! is_array( $screen_options ) ) {
			$screen_options = array( $screen_options );
		}//end if

		foreach ( $screen_options as $screen_option ) {

			// If the screen option matches the current screen ID, we return true.
			if ( $screen_option === $screen->id ) {
				return true;
			}//end if

			// If they don't, we check if the screen option is "beautified".
			switch ( $screen_option ) {

				case '_nc-calendar-page':
					$actual_screen_option = 'toplevel_page_nelio-content';
					return $actual_screen_option === $screen->id;

				case '_nc-activity':
					$actual_screen_option = 'nelio-content_page_nelio-content-activity';
					return $actual_screen_option === $screen->id;

				case '_nc-analytics':
					$top_page = 'toplevel_page_nelio-content-analytics';
					$sub_page = 'nelio-content_page_nelio-content-analytics';
					return $top_page === $screen->id || $sub_page === $screen->id;

				case '_nc-account':
					$top_page = 'toplevel_page_nelio-content-account';
					$sub_page = 'nelio-content_page_nelio-content-account';
					return $top_page === $screen->id || $sub_page === $screen->id;

				case '_nc-settings':
					$actual_screen_option = 'nelio-content_page_nelio-content-settings';
					return $actual_screen_option === $screen->id;

				case '_nc-post-with-featured-image':
					return in_array( $screen->id, $post_types, true ) && post_type_supports( $screen->id, 'thumbnail' );

			}//end switch

		}//end foreach

		return false;

	}//end is_current_screen()

	/**
	 * Returns whether the current screen is the new/edit screen of a post type.
	 *
	 * @return boolean whether the current screen the new/edit screen of a post type.
	 *
	 * @since  1.1.3
	 * @access public
	 */
	public function is_edit_screen() {

		$screen = get_current_screen();
		$post_types = get_post_types();
		return in_array( $screen->id, $post_types, true );

	}//end is_edit_screen()

	/**
	 * Returns whether the current screen is a Nelio Content's page or not.
	 *
	 * @return boolean whether the current screen is a Nelio Content's page or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function is_a_nelio_content_page() {

		$screen = get_current_screen();
		$settings = Nelio_Content_Settings::instance();

		if ( strpos( $screen->id, 'page_nelio-content' ) !== false ) {
			return true;
		}//end if

		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) ) {
			return true;
		}//end if

		$post_types = get_post_types();
		if ( in_array( $screen->id, $post_types, true ) && post_type_supports( $screen->id, 'thumbnail' ) ) {
			return true;
		}//end if

		return false;

	}//end is_a_nelio_content_page()

	/**
	 * Returns a JavaScript object with all the data required by our plugin.
	 *
	 * @return array a JavaScript object with all the data required by our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function generate_js_object() {

		$limits = nc_get_site_limits();
		$settings = Nelio_Content_Settings::instance();

		$free_trial = false;
		if ( ! get_transient( 'nelio_content_was_free_trial_offered' ) ) {
			$free_trial = nc_get_possible_free_trial_plan();
		}//end if

		$subscription = nc_get_subscription();
		if ( ! isset( $subscription['mode'] ) ) {
			$subscription['mode'] = 'regular';
			$subscription['endDate'] = '';
		}//end if

		$result = array(
			'apiAuthToken'      => nc_generate_api_auth_token(),
			'apiUri'            => nc_get_api_url( '/site/' . nc_get_site_id(), 'browser' ),
			'blogUrl'           => get_site_url(),
			'freeTrialOption'   => $free_trial,
			'collections'       => array(), // This object is populated by the JS.
			'hasSocialProfiles' => get_option( 'nc_has_social_profiles' ),
			'helpers'           => array(), // This object is populated by the JS.
			'i18n'              => $this->get_i18n_object(),
			'models'            => array(), // This object is populated by the JS.
			'networkMetas'      => $this->get_network_metas(),
			'postTypes'         => $this->get_calendar_post_types(),
			'productsUri'       => nc_get_api_url( '/fastspring/products', 'browser' ),
			'profiles'          => false,   // This Backbone collection is populated by the JS.
			'userId'            => get_current_user_id(),
			'users'             => $this->get_current_user(),
			'version'           => $this->version,
			'views'             => array(), // This object is populated by the JS.
			'postAnalysis'      => array(
				'isYoastSeoIntegrated' => $settings->get( 'qa_is_yoast_seo_integrated' ),
				'minPostLength'        => absint( $settings->get( 'qa_min_word_count' ) ),
			),
			'subscription' => array(
				'endDate' => $subscription['endDate'],
				'mode'    => $subscription['mode'],
				'plan'    => $subscription['plan'],
			),
			'analytics' => array(
				'gaViewId' => $settings->get( 'google_analytics_view' ),
			),
			'limits' => array(
				'maxProfiles'           => $limits['maxProfiles'],
				'maxProfilesPerNetwork' => $limits['maxProfilesPerNetwork'],
			),
			'pages' => array(
				'account'             => admin_url( 'admin.php?page=nelio-content-account' ),
				/* translators: from the time being, our website is only available in English and Spanish. This is an "English" URL. */
				'subscriptionAtNelio' => sprintf( _x( 'https://neliosoftware.com/content/pricing/?plan=%s', 'text', 'nelio-content' ), '{plan}' ),
				'settings'            => admin_url( 'admin.php?page=nelio-content-settings' ),
			),
		);

		if ( ! $this->is_edit_screen() ) {

			/**
			 * Last published post cannot be loaded on post.php, because the loop for
			 * retrieving said post "breaks" the editor.
			 *
			 * @see https://core.trac.wordpress.org/ticket/18408
			 */
			$newest_post = $this->get_latest_published_post();
			if ( ! empty( $newest_post ) ) {
				$result['lastPublishedPost'] = $newest_post;
			}//end if

		}//end if

		return $result;

	}//end generate_js_object()

	/**
	 * Returns the latest published post, or false if there's none.
	 *
	 * This object is used in the calendar page, during social message creation.
	 *
	 * WARNING: Because of https://core.trac.wordpress.org/ticket/18408, this
	 * function doesn't work on `post.php`. However, we don't need the latest
	 * post in that page (this post is only needed in the calendar page), so
	 * the function contains a save guard that prevents a nested loop from
	 * running when in said page.
	 *
	 * @return array the latest published post, or false if there's none.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName )
	 */
	private function get_latest_published_post() {

		// Save guard for https://core.trac.wordpress.org/ticket/18408.
		$settings = Nelio_Content_Settings::instance();
		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) ) {
			return false;
		}//end if

		// Get latest published post.
		$lp_query = new WP_Query( array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		) );

		// If there are no published posts, leave.
		if ( ! $lp_query->have_posts() ) {
			wp_reset_postdata();
			return false;
		}//end if

		// If there's one, return the result.
		$lp_query->the_post();
		$post_helper = Nelio_Content_Post_Helper::instance();
		$result = $post_helper->post_to_json();

		// Reset lp_query.
		wp_reset_postdata();

		return $result;

	}//end get_latest_published_post()

	/**
	 * Returns an i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @return array i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_calendar_post_types() {

		$result = array();

		$settings = Nelio_Content_Settings::instance();
		$ptnames = $settings->get( 'calendar_post_types' );
		foreach ( $ptnames as $ptname ) {

			$post_type = get_post_type_object( $ptname );
			if ( ! $post_type || is_wp_error( $post_type ) ) {
				continue;
			}//end if

			$labels = $post_type->labels;
			array_push( $result, array(
				'name'   => $ptname,
				'labels' => array(
					'plural'     => $labels->name,
					'singular'   => $labels->singular_name,
					'addTitle'   => $labels->add_new_item,
					'addAction'  => $labels->add_new_item,
					'allItems'   => $labels->all_items,
					'editTitle'  => $labels->edit_item,
					'editAction' => $labels->edit_item,
					'viewAction' => $labels->view_item,
				),
				'supportsFeaturedImage' => post_type_supports( $ptname, 'thumbnail' ),
			) );

		}//end foreach

		return $result;

	}//end get_calendar_post_types()

	/**
	 * Returns an i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @return array i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_i18n_object() {

		$aux = get_post_type_object( 'post' );
		$post_name = $aux->labels->singular_name;

		$spinner = '<span class="dashicons dashicons-update nc-animate-spinner"></span> ';

		$sent_messages = array();
		for ( $i = 1; $i <= 50; ++$i ) {
			array_push( $sent_messages, sprintf(
				_nx( '<strong>+%d more</strong> social message sent', '<strong>+%d more</strong> social messages sent', $i, 'text', 'nelio-content' ),
				$i
			) );
		}//end for
		array_push( $sent_messages, sprintf(
			/* Translators: String used for 51 or more social messages sent. Up to 50, proper i18n is used in JavaScript */
			_x( '<strong>+%s more</strong>', 'text', 'nelio-content' ),
			'{count}'
		) );

		$wait_seconds = array();
		for ( $i = 0; $i < 10; ++$i ) {
			array_push( $wait_seconds, sprintf(
				_nx( 'Wait %s second&hellip;', 'Wait %s seconds&hellip;', $i, 'text', 'nelio-content' ),
				$i
			) );
		}//end for

		return array(
			'locale'                    => get_locale(),
			'daysAfterPublication'      => sprintf( _x( '%s days after publication', 'text', 'nelio-content' ), '{days}' ),
			'daysBeforePublication'     => sprintf( _x( '%s days before publication', 'text', 'nelio-content' ), '{days}' ),
			'defaultPostName'           => _x( 'post-name', 'text', 'nelio-content' ),
			'error'                     => _x( 'Error', 'text', 'nelio-content' ),
			'fullnameFormat'            => sprintf( _x( '%1$s %2$s', 'text: {firstname} {lastname}', 'nelio-content' ), '{firstname}', '{lastname}' ),
			'hoursAfterPublication'     => sprintf( _x( '%s hours after publication', 'text', 'nelio-content' ), '{hours}' ),
			'noInvoices'                => _x( 'No invoices found.', 'text', 'nelio-content' ),
			'onPublication'             => _x( 'Same time as publication', 'text', 'nelio-content' ),
			'oneHourAfterPublication'   => _x( 'One hour after publication', 'text', 'nelio-content' ),
			'postName'                  => $post_name,
			'publicationDay'            => _x( 'Publication day', 'text', 'nelio-content' ),
			'someday'                   => _x( 'Someday', 'text (date)', 'nelio-content' ),
			'startOfWeek'               => get_option( 'start_of_week' ),
			'timezone'                  => nc_get_timezone(),
			'sentMessagesInCalendar'    => $sent_messages,
			'subscribersOnly'           => _x( '(only available to subscribers)', 'text', 'nelio-content' ),
			'unknownUserName'           => _x( 'Unknown', 'text (username)', 'nelio-content' ),
			'gaSelectorPlaceholder'     => _x( 'Select which view can be accessed by Nelio Content', 'user', 'nelio-content' ),
			'filters' => array(
				'actions' => array(
					'showSocialMessages' => _x( 'Show All Messages', 'command (social filter)', 'nelio-content' ),
					'showTasks'          => _x( 'Show All Tasks', 'command (task filter)', 'nelio-content' ),
					'hideSocialMessages' => _x( 'Hide All Messages', 'command (social filter)', 'nelio-content' ),
					'hideTasks'          => _x( 'Hide All Tasks', 'command (task filter)', 'nelio-content' ),
					'showPosts'          => _x( 'Show All Posts', 'command (user filter)', 'nelio-content' ),
				),
				'groups' => array(
					'network'  => _x( 'Filter by Network', 'user (social filter, group name)', 'nelio-content' ),
					'profile'  => _x( 'Filter by Profile', 'user (social filter, group name)', 'nelio-content' ),
					'assignee' => _x( 'Filter by Assignee', 'user (task filter, group name)', 'nelio-content' ),
					'author'   => _x( 'Posts by author', 'text (user filter, group name)', 'nelio-content' ),
				),
				'selection' => array(
					'allPostTypes'      => _x( 'All Post Types', 'text (post filter, selection)', 'nelio-content' ),
					'allSocialMessages' => _x( 'All Social Messages', 'text (social filter, selection)', 'nelio-content' ),
					'allTasks'          => _x( 'All Tasks', 'text (task filter, selection)', 'nelio-content' ),
					'allAuthors'        => _x( 'All Authors', 'text (user filter, selection)', 'nelio-content' ),
					'engagement'        => _x( 'Sorted By Engagement', 'text (metric sorter, selection)', 'nelio-content' ),
					'pageviews'         => _x( 'Sorted By Pageviews', 'text (metric sorter, selection)', 'nelio-content' ),
					'noPosts'           => _x( 'No Posts', 'text (post filter, selection)', 'nelio-content' ),
					'noPostTypes'       => _x( 'No Post Types', 'text (post filter, selection)', 'nelio-content' ),
					'noSocialMessages'  => _x( 'No Social Messages', 'text (social filter, selection)', 'nelio-content' ),
					'noTasks'           => _x( 'No Tasks', 'text (task filter, selection)', 'nelio-content' ),
					/* translators: %s is a user's name. For instance: "David's Tasks". */
					'tasksOf'           => _x( '%s\'s Tasks', 'text (task filter, selection)', 'nelio-content' ),
					'postsBy'           => _x( 'Posts by %s', 'text (user filter, selection)', 'nelio-content' ),
				),
				'sortedBy' => array(
					'pageviews'  => _x( 'Sorted by <strong>Pageviews</strong>.', 'text (metric sorter, analytics)', 'nelio-content' ),
					'engagement' => _x( 'Sorted by <strong>Engagement</strong>.', 'text (metric sorter, analytics)', 'nelio-content' ),
				),
				'sortBy' => array(
					'pageviews'  => _x( 'Sort by Pageviews.', 'text (metric sorter, analytics)', 'nelio-content' ),
					'engagement' => _x( 'Sort by Engagement.', 'text (metric sorter, analytics)', 'nelio-content' ),
				),
			),
			'previewDates' => array(
				/* translators: format a date as Facebook does in your timeline, using the syntax of moment.js library. */
				'facebook'      => _x( 'MMMM Do, YYYY', 'text (Facebook preview date)', 'nelio-content' ),
				/* translators: format a date as Google Plus does in your timeline, using the syntax of moment.js library. */
				'googleplus'    => _x( 'MMMM Do, YYYY', 'text (Google+ preview date)', 'nelio-content' ),
				/* translators: format a date as Pinterest does in your timeline, using the syntax of moment.js library. */
				'instagram'     => _x( 'MMMM Do, YYYY', 'text (Instagram preview date)', 'nelio-content' ), // TODO
				/* translators: format a date as LinkedIn does in your timeline, using the syntax of moment.js library. */
				'linkedin'      => _x( 'MMMM Do, YYYY', 'text (LinkedIn preview date)', 'nelio-content' ),
				/* translators: format a date as Pinterest does in your timeline, using the syntax of moment.js library. */
				'pinterest'     => _x( 'MMMM Do, YYYY', 'text (Pinterest preview date)', 'nelio-content' ),
				/* translators: format a date as Twitter does in your timeline (including time), using the syntax of moment.js library. */
				'twitter'       => _x( 'h:mm A - DD MMM YYYY', 'text (Twitter preview date)', 'nelio-content' ),
				/* translators: format a date as Twitter does in your timeline (without time), using the syntax of moment.js library. */
				'twitterNoTime' => _x( 'DD MMM YYYY', 'text (Twitter preview date)', 'nelio-content' ),
			),
			'actions' => array(
				'add'                    => _x( 'Add', 'command', 'nelio-content' ),
				'addSocialProfile'       => _x( 'Add Social Profile', 'command', 'nelio-content' ),
				'cancel'                 => _x( 'Cancel', 'command', 'nelio-content' ),
				'cancelSubscription'     => _x( 'Cancel Subscription', 'command', 'nelio-content' ),
				'choose'                 => _x( 'Choose', 'command (image)', 'nelio-content' ),
				'close'                  => _x( 'Close', 'command', 'nelio-content' ),
				'collapseSentMessages'   => _x( 'Collapse sent messages', 'command', 'nelio-content' ),
				'continue'               => _x( 'Continue', 'command', 'nelio-content' ),
				'connect'                => _x( 'Connect', 'command (account)', 'nelio-content' ),
				'connectGoogleAnalytics' => _x( 'Connect Google Analytics', 'command', 'nelio-content' ),
				'createTask'             => _x( 'Create', 'command (task)', 'nelio-content' ),
				'delLabel'               => _x( 'Delete', 'command', 'nelio-content' ),
				'doDeleteSocial'         => _x( 'Yes, delete it', 'command (social message)', 'nelio-content' ),
				'doTrashPost'            => _x( 'Yes, trash it', 'command (post)', 'nelio-content' ),
				'doTrashElement'         => _x( 'Yes, trash it', 'command (element)', 'nelio-content' ),
				'editSocial'             => _x( 'Edit', 'command (social message)', 'nelio-content' ),
				'ignore'                 => _x( 'Ignore', 'command', 'nelio-content' ),
				'load'                   => _x( 'Load', 'command', 'nelio-content' ),
				'ok'                     => _x( 'OK', 'command', 'nelio-content' ),
				'postpone'               => _x( 'Remind Me Later', 'command', 'nelio-content' ),
				'reactivateSubscription' => _x( 'Reactivate Subscription', 'command', 'nelio-content' ),
				'refreshAnalytics'       => _x( 'Refresh Analytics', 'command (analytics)', 'nelio-content' ),
				'start'                  => _x( 'Start', 'command', 'nelio-content' ),
				'save'                   => _x( 'Save', 'command', 'nelio-content' ),
				'saveChanges'            => _x( 'Save Changes', 'command', 'nelio-content' ),
				'sendSocialNow'          => _x( 'Send Now', 'command (social message)', 'nelio-content' ),
				'shareSelection'         => _x( 'Share Selection', 'command', 'nelio-content' ),
				'subscribe'              => _x( 'Subscribe', 'command', 'nelio-content' ),
				'trash'                  => _x( 'Trash', 'command', 'nelio-content' ),
				'upgrade'                => _x( 'Upgrade', 'command (subscription)', 'nelio-content' ),
				'viewAccount'            => _x( 'View Account', 'command', 'nelio-content' ),
			),
			'dialogs' => array(
				'analyticsNoPostsFound'   => _x( 'No posts found.', 'user', 'nelio-content' ),
				'analyticsProcessed'      => sprintf( _x( 'Analytics successfully updated.<br>Posts processed: %s', 'user', 'nelio-content' ), '{posts}' ),
				'deleteSocialMessage'     => _x( 'Do you really want to delete this social message? <strong>This operation cannot be undone</strong>.', 'user', 'nelio-content' ),
				'cancelSubscription'      => sprintf( _x( 'Canceling your subscription will cause it not to renew. If you cancel your subscrition, it will continue until <strong>%s</strong>. Then, the subscription will expire and will not be invoiced again, but you will be able to use the Free Version of Nelio Content. Do you want to cancel your subscription?', 'user', 'nelio-content' ), '{date}' ),
				'reactivateSubscription'  => _x( 'Reactivating your subscription will cause it to renew. Do you want to reactivate your subscription?', 'user', 'nelio-content' ),
				'socialNotSentUnknown'    => _x( 'Social message couldn\'t be shared because of an unknown error.', 'error', 'nelio-content' ),
				'socialNotSent'           => sprintf( _x( 'The following error occurred while sharing social message:<br><strong>%s</strong>', 'error', 'nelio-content' ), '{error}' ),
				'trashPost'               => _x( 'Do you really want to trash this post?', 'user', 'nelio-content' ),
				'trashElement'            => _x( 'Do you really want to trash this element?', 'user', 'nelio-content' ),
				'invalidSocialScheduling' => _x( 'Social messages whose publication date is relative to the publication date of a post <strong>can\'t be scheduled before the post is published</strong>. If you want to do so, please edit the social message and use an <em>exact</em> date.', 'user', 'nelio-content' ),
			),
			'feedback' => array(
				'retrievingPosts'          => _x( 'Retrieving posts&hellip;', 'text (analytics)', 'nelio-content' ),
				'adding'                   => $spinner . _x( 'Adding&hellip;', 'text', 'nelio-content' ),
				'cancelingSubscription'    => $spinner . _x( 'Canceling Subscription&hellip;', 'text', 'nelio-content' ),
				'creating'                 => $spinner . _x( 'Creating&hellip;', 'text', 'nelio-content' ),
				'creatingTask'             => $spinner . _x( 'Creating&hellip;', 'text (task)', 'nelio-content' ),
				'deleting'                 => $spinner . _x( 'Deleting&hellip;', 'text', 'nelio-content' ),
				'gaTokensExpired'          => _x( '<strong>Warning!</strong> Your Google Analytics tokens have expired.', 'text', 'nelio-content' ),
				'loading'                  => $spinner . _x( 'Loading&hellip;', 'text', 'nelio-content' ),
				'loadingNoSpinner'         => _x( 'Loading&hellip;', 'text', 'nelio-content' ),
				'reactivatingSubscription' => $spinner . _x( 'Reactivating Subscription&hellip;', 'text', 'nelio-content' ),
				'refreshingAnalytics'      => $spinner . _x( 'Refreshing', 'text (analytics)', 'nelio-content' ),
				'saving'                   => $spinner . _x( 'Saving&hellip;', 'text', 'nelio-content' ),
				'starting'                 => $spinner . _x( 'Starting&hellip;', 'text', 'nelio-content' ),
				'trashing'                 => $spinner . _x( 'Trashing&hellip;', 'text', 'nelio-content' ),
				'upgrading'                => $spinner . _x( 'Upgrading&hellip;', 'text', 'nelio-content' ),
				'waitAMoment'              => _x( 'Please wait a moment&hellip;', 'user', 'nelio-content' ),
				'waitSeconds'              => $wait_seconds,
			),
			'titles' => array(
				'addSocialProfiles'       => _x( 'Add Social Profiles', 'title', 'nelio-content' ),
				'cancelSubscription'      => _x( 'Cancel Subscription', 'title', 'nelio-content' ),
				'delSocialMessage'        => _x( 'Delete Social Message', 'title', 'nelio-content' ),
				'editMessage'             => _x( 'Edit Social Message', 'title', 'nelio-content' ),
				'editReference'           => _x( 'Edit Reference', 'title', 'nelio-content' ),
				'error'                   => _x( 'Error', 'title', 'nelio-content' ),
				'externalFeaturedImage'   => _x( 'External Featured Image', 'title', 'nelio-content' ),
				'freeTrial'               => _x( 'Nelio Content Premium', 'title', 'nelio-content' ),
				'invalidSocialScheduling' => _x( 'Invalid Scheduling', 'title (social message)', 'nelio-content' ),
				'newMessage'              => _x( 'New Social Message', 'title', 'nelio-content' ),
				'newTask'                 => _x( 'New Task', 'title', 'nelio-content' ),
				'reactivateSubscription'  => _x( 'Reactivate Subscription', 'title', 'nelio-content' ),
				'refreshAnalytics'        => _x( 'Refresh Analytics', 'title', 'nelio-content' ),
				'selectImage'             => _x( 'Select an Image', 'title', 'nelio-content' ),
				'socialNotSent'           => _x( 'Social Message Failed', 'title', 'nelio-content' ),
				'subscribe'               => _x( 'New Subscription', 'title', 'nelio-content' ),
				'suggestSubscription'     => _x( 'Subscribe', 'title', 'nelio-content' ),
				'trashPost'               => _x( 'Trash Post', 'title', 'nelio-content' ),
				'trashElement'            => _x( 'Trash Element', 'title', 'nelio-content' ),
				'upgradeSubscription'     => _x( 'Upgrade Subscription', 'title', 'nelio-content' ),
			),
			'tinymce' => array(
				'shareSelectionTitle' => _x( 'Share Selection', 'command', 'nelio-content' ),
				'addProfilesFirst'    => _x( '(connect social profiles to enable)', 'user', 'nelio-content' ),
			),
			'date' => array(
				/* translators: Date using the syntax of moment.js library. Example: "Last Monday". */
				'lastWeek'         => _x( '[Last] dddd', 'momentjs', 'nelio-content' ),
				/* translators: Syntax of moment.js library. Translate "Yesterday" surrounded by square brackes. */
				'lastDay'          => _x( '[Yesterday]', 'momentjs', 'nelio-content' ),
				/* translators: Syntax of moment.js library. Translate "Today" surrounded by square brackes. */
				'sameDay'          => _x( '[Today]', 'momentjs', 'nelio-content' ),
				/* translators: Syntax of moment.js library. Translate "Tomorrow" surrounded by square brackes. */
				'nextDay'          => _x( '[Tomorrow]', 'momentjs', 'nelio-content' ),
				/* translators: Date using the syntax of moment.js library. Example: "Next Thursday". */
				'nextWeek'         => _x( '[Next] dddd', 'momentjs', 'nelio-content' ),
				'default'          => _x( 'L', 'momentjs (default date)', 'nelio-content' ),
				'cancelDateDialog' => _x( 'LL', 'momentjs (as in "The subscription will continue until {date}")', 'nelio-content' ),
				'cancelDateText'   => _x( 'LL', 'momentjs (as in "Your subscription will end on {date}")', 'nelio-content' ),
				'creationDate'     => _x( 'LL', 'momentjs (as in "Member since {date}")', 'nelio-content' ),
				'nextChargeDate'   => _x( 'LL', 'momentjs (as in "Next charge will be on {date}")', 'nelio-content' ),
			),
			'time' => array(
				/* translators: Syntax of moment.js library. Translate "Now" surrounded by square brackes. */
				'now'      => _x( '[Now]', 'momentjs', 'nelio-content' ),
				/* translators: Time using moment.js library's syntax. For instance, "8:03pm". */
				'default'  => _x( 'h:mma', 'momentjs', 'nelio-content' ),
				'calendar' => $this->get_calendar_time(),
			),
			'errors' => array(
				'api' => array(
					'emptyAjaxStatus'          => _x( 'There was an error while accessing Nelio Content\'s API. Please, try again later.', 'user', 'nelio-content' ),
					'cantGetInvoices'          => _x( 'There was an error while retrieving your invoices. Please, try again later.', 'user', 'nelio-content' ),
					'cantGetProducts'          => sprintf( _x( 'The following error occurred while retrieving the list of available plans: %s. Please, try again later.', 'user', 'nelio-content' ), '{error}' ),
					'unableToRetrieveProfiles' => _x( 'There was an error while accessing Nelio Content\'s API and your social profiles couldn\'t be retrieved. Please, try again later.', 'user', 'nelio-content' ),
					'unableToRetrieveCalendar' => _x( 'There was an error while accessing Nelio Content\'s API and some items in your calendar couldn\'t be retrieved. Please, try again later.', 'user', 'nelio-content' ),
				),
				'featuredImage' => array(
					'emptyUrl'        => _x( 'Please, write the URL of an external image.', 'error', 'nelio-content' ),
					'invalidUrl'      => _x( 'URL is invalid', 'error', 'nelio-content' ),
					'unableToLoadUrl' => _x( 'Image can\'t be loaded. Please, try again with a different URL.', 'error', 'nelio-content' ),
				),
				'freeTrial' => array(
					'noFirstName'   => _x( 'Please, type your first name', 'error', 'nelio-content' ),
					'noLastName'    => _x( 'Please, type your last name', 'error', 'nelio-content' ),
					'noEmail'       => _x( 'Please, type your email address', 'error', 'nelio-content' ),
					'invalidEmail'  => _x( 'Please, type a valid email address', 'error', 'nelio-content' ),
					'unableToStart' => sprintf( esc_html_x( 'Unable to start free trial: %s', 'user', 'nelio-content' ), '<strong>{error}</strong>' ),
				),
				'generic' => array(
					'unableToRescheduleItem' => _x( 'An error occured while rescheduling the item. Please, try again later.', 'user', 'nelio-content' ),
					'unableToDeleteItem'     => _x( 'An error occured while deleting the item. Please, try again later.', 'user', 'nelio-content' ),
				),
				'socialMessage' => array(
					'noMessage'        => _x( 'Please, write your status update', 'error', 'nelio-content' ),
					'messageTooLong'   => _x( 'Please, write a shorter social message', 'error', 'nelio-content' ),
					'noProfiles'       => _x( 'Please, select a social profile', 'error', 'nelio-content' ),
					'noImageMultiple'  => _x( 'Selected profiles require you to share an image', 'error', 'nelio-content' ),
					'noImageSingle'    => _x( 'Selected profile requires you to share an image', 'error', 'nelio-content' ),
					'noTarget'         => _x( 'Please, select a target', 'error', 'nelio-content' ),
					'unableToLoadPost' => sprintf( _x( 'The related post couldn\'t be loaded, because of the following error: %s', 'error', 'nelio-content' ), '{error}' ),
				),
				'reference' => array(
					'noUrl'          => _x( 'Please, specify the reference\'s URL', 'error', 'nelio-content' ),
					'invalidUrl'     => _x( 'URL is invalid', 'error', 'nelio-content' ),
					'invalidTwitter' => _x( 'Twitter username has to start with «@»', 'error', 'nelio-content' ),
					'invalidMail'    => _x( 'Email is invalid', 'error', 'nelio-content' ),
					'notSuggested'   => _x( 'The following error occurred while suggesting the reference:', 'error', 'nelio-content' ),
					'notDiscarded'   => _x( 'The following error occurred while discarding the reference:', 'error', 'nelio-content' ),
				),
				'task' => array(
					'noTask' => _x( 'Please, enter a task', 'error', 'nelio-content' ),
				),
				'post' => array(
					'noTitle'       => _x( 'Please, set post\'s title', 'error', 'nelio-content' ),
					'invalidUrl'    => _x( 'Reference\'s URL is invalid', 'error', 'nelio-content' ),
					'unableToTrash' => sprintf( _x( 'The following error ocurred while trashing the post: %s.', 'error', 'nelio-content' ), '{error}' ),
				),
				'datetime' => array(
					'noPastAllowed' => _x( 'Date and time cannot be older than current date', 'error', 'nelio-content' ),
					'invalidDate'   => _x( 'Date format is invalid', 'error', 'nelio-content' ),
					'noDate'        => _x( 'Please, specify a date', 'error', 'nelio-content' ),
					'invalidTime'   => _x( 'Time format is invalid', 'error', 'nelio-content' ),
					'noTime'        => _x( 'Please, specify a time', 'error', 'nelio-content' ),
				),
			),
		);

	}//end get_i18n_object()

	/**
	 * Returns a list with the current user only.
	 *
	 * @return array a list with the current user only.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function get_current_user() {

		$aux = Nelio_Content_User_Helper::instance();
		return array( $aux->user_to_json( wp_get_current_user() ) );

	}//end get_current_user()

	/**
	 * Helper function used for sorting an array of users.
	 *
	 * @param object $a A user.
	 * @param object $b Another user.
	 *
	 * @return integer Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName )
	 */
	public function compare_users( $a, $b ) {

		return strcasecmp( $a['name'], $b['name'] );

	}//end compare_users()

	/**
	 * Returns the momentjs format used for rendering time in calendar items.
	 *
	 * @return string the momentjs format used for rendering time in calendar items.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function get_calendar_time() {

		$time = get_option( 'time_format', 'a' );
		$time = str_ireplace( '\\a', '', $time );

		if ( stripos( $time, 'a' ) !== false ) {
			return 'h:mma';
		} else {
			return 'H:mm';
		}//end if

	}//end get_calendar_time()

	/**
	 * Returns meta information about the configured networks.
	 *
	 * @return array meta information about the configured networks.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function get_network_metas() {

		$metas = array(
			array(
				'id'                 => 'twitter',
				'name'               => __( 'Twitter', 'nelio-content' ),
				'maxLength'          => 140,
				'allowsMultiTargets' => false,
				'previewClassName'   => 'TwitterPreview',
				'isImageRequired'    => false,
			),
			array(
				'id'                 => 'facebook',
				'name'               => __( 'Facebook', 'nelio-content' ),
				'maxLength'          => 10000,
				'allowsMultiTargets' => false,
				'previewClassName'   => 'FacebookPreview',
				'isImageRequired'    => false,
			),
			array(
				'id'                 => 'googleplus',
				'name'               => __( 'Google+', 'nelio-content' ),
				'maxLength'          => 500,
				'allowsMultiTargets' => false,
				'previewClassName'   => 'GooglePlusPreview',
				'isImageRequired'    => false,
			),
			array(
				'id'                 => 'instagram',
				'name'               => __( 'Instagram', 'nelio-content' ),
				'maxLength'          => 2000,
				'allowsMultiTargets' => false,
				'previewClassName'   => 'InstagramPreview',
				'isImageRequired'    => true,
			),
			array(
				'id'                 => 'linkedin',
				'name'               => __( 'LinkedIn', 'nelio-content' ),
				'maxLength'          => 600,
				'allowsMultiTargets' => false,
				'previewClassName'   => 'LinkedInPreview',
				'isImageRequired'    => false,
			),
			array(
				'id'                 => 'pinterest',
				'name'               => __( 'Pinterest', 'nelio-content' ),
				'maxLength'          => 500,
				'allowsMultiTargets' => true,
				'multiTargetLabels'  => array(
					'title'       => _x( 'Select Boards', 'title (pinterest boards)', 'nelio-content' ),
					'explanation' => _x( 'Please, select the boards your message will be shared on:', 'user (pinterest boards)', 'nelio-content' ),
					'loading'     => _x( 'Loading boards…', 'text (pinterest boards)', 'nelio-content' ),
					'targetLabel' => _x( 'Board', 'text (pinterest target name)', 'nelio-content' ),
				),
				'previewClassName'   => 'PinterestPreview',
				'isImageRequired'    => true,
			),
		);

		/**
		 * Filters the set meta information of available social networks.
		 *
		 * Each meta has the following information:
		 *
		 *  * id: a unique identifier (e.g. twitter or googleplus)
		 *  * name: the name of the network (e.g. Twitter or Google+)
		 *  * maxLength: the maximum length a social message in the given
		 *         network might be.
		 *  * previewClassName: the JavaScript class name used for rendering
		 *         a preview of a social message in the social message
		 *         editor.
		 *
		 * @param array $metas the set of meta information.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'nelio_content_network_metas', $metas );

	}//end get_network_metas()

}//end class
