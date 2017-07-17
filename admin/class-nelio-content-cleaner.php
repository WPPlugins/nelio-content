<?php
/**
 * This file contains the class for cleaning WordPress after plugin deactivation.
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
 * Helper methods for cleaning WordPress database after plugin deactivation.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Cleaner {

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
	 * AJAX callback for cleaning the database.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function clean_database() {

		$this->clean_database_and_maybe_cancel_account();

	}//end clean_database()

	/**
	 * AJAX callback for cleaning the database and cancelling the account.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function clean_database_and_cancel_account() {

		$this->clean_database_and_maybe_cancel_account();

	}//end clean_database_and_cancel_account()

	/**
	 * Helper function that cleans the database and, if the user is subscribed,
	 * cancels his subscription.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function clean_database_and_maybe_cancel_account() {

		$site_id = nc_get_site_id();
		$is_cloud_clean = true;
		$is_subscr_canceled = true;

		if ( $site_id ) {

			$settings = Nelio_Content_Settings::instance();
			$data = array(
				'method'    => 'DELETE',
				'timeout'   => 30,
				'sslverify' => ! $settings->get( 'uses_proxy' ),
				'headers'   => array(
					'Authorization' => 'Bearer ' . nc_generate_api_auth_token( 'skip-errors' ),
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				),
			);

			// Cancel subscription (if any).
			if ( nc_is_subscribed() ) {

				$url = nc_get_api_url( '/site/' . $site_id . '/subscription', 'wp' );
				$response = wp_remote_request( $url, $data );
				if ( ! nc_is_response_valid( $response ) ) {
					$is_subscr_canceled = false;
				}//end if

			}//end if

			// And remove all site information from AWS.
			$url = nc_get_api_url( '/site/' . $site_id, 'wp' );
			$response = wp_remote_request( $url, $data );
			if ( ! nc_is_response_valid( $response ) ) {
				$is_cloud_clean = false;
			}//end if

		}//end if

		// Delete local database.
		global $wpdb;

		$wpdb->query( // @codingStandardsIgnoreLine
			"DELETE FROM $wpdb->postmeta
			WHERE meta_key LIKE '_nc_%'"
		);

		$wpdb->delete( // @codingStandardsIgnoreLine
			$wpdb->posts,
			array( 'post_type' => 'nc_reference' )
		);

		$wpdb->query( // @codingStandardsIgnoreLine
			"DELETE FROM $wpdb->options
			WHERE option_name LIKE 'nc_%' OR
			      option_name LIKE 'nelio_content_%' OR
			      option_name LIKE 'nelio-content_%'"
		);

		// Done!
		if ( $is_cloud_clean && $is_subscr_canceled ) {
			wp_send_json( 0 );
		}//end if

		if ( ! $is_subscr_canceled ) {
			$error_message = sprintf(
				_x( 'Something went wrong when cancelling your subscription. Please, <a href="%1$s">get in touch with us</a> and we\'ll take care of it manually.', 'error', 'nelio-content' ),
				'mailto:support.content@neliosoftware.com'
			);
			$error_message .= '<br><br><code>Error: <strong>CANCSUBS</strong></code>';
		} else {
			$error_message = sprintf(
				_x( 'Something went wrong when clearing your scheduled social messages. Please, <a href="%s">get in touch with us</a> and we\'ll take care of it manually.', 'error', 'nelio-content' ),
				'mailto:support.content@neliosoftware.com'
			);
			$error_message .= '<br><br><code>Error: <strong>CLEANAWS</strong></code>';
		}//end if

		header( 'HTTP/1.1 500 Internal Server Error' );
		wp_send_json( $error_message );

	}//end clean_database_and_maybe_cancel_account()

	/**
	 * Hook for tweaking Nelio Content's links in the plugins page.
	 *
	 * @param array  $links Plugin links.
	 * @param string $file  Current plugin.
	 *
	 * @return array Plugin links.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_cleaning_option( $links, $file ) {

		// This function should only modify the links of our plugin.
		if ( plugin_basename( 'nelio-content/nelio-content.php' ) !== $file ) {
			return $links;
		}//end if

		if ( ! isset( $links['deactivate'] ) ) {
			return $links;
		}//end if

		if ( is_network_admin() ) {
			return $links;
		}//end if

		if ( is_plugin_active_for_network( $file ) ) {
			return $links;
		}//end if

		// If the user can Deactivate the plugin, he should be able to clean the database
		// and cancel the subscription at the same time.
		preg_match_all( '/<a[^>]+href="(.+?)"[^>]*>/i', $links['deactivate'], $matches );
		if ( empty( $matches ) ) {
			return $links;
		}//end if

		$links['deactivate'] = sprintf(
			'<a id="nc-deactivate" href="%s">%s</a>',
			$matches[1][0], // @codingStandardsIgnoreLine
			esc_html( _x( 'Deactivate', 'command (plugins)', 'nelio-content' ) )
		);

		return $links;

	}//end add_cleaning_option()

	/**
	 * Enqueue the stylesheets for the plugins page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue_styles() {

		// These styles can only be included if we're on a Nelio Content's page.
		$screen = get_current_screen();
		if ( ! in_array( $screen->id, array( 'plugins', 'plugins-network' ), true ) ) {
			return;
		}//end if

		wp_enqueue_style(
			'nelio-content-cleaner',
			NELIO_CONTENT_ADMIN_URL . '/css/cleaner.min.css',
			array( 'wp-jquery-ui-dialog' ),
			$this->version,
			'all'
		);

	}//end enqueue_styles()

	/**
	 * Enqueue the JavaScript for the plugins page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	public function enqueue_scripts() {

		// These styles can only be included if we're on a Nelio Content's page.
		$screen = get_current_screen();
		if ( ! in_array( $screen->id, array( 'plugins', 'plugins-network' ), true ) ) {
			return;
		}//end if

		// Load calendar page customizations.
		wp_enqueue_script(
			'nelio-content-cleaner-js',
			NELIO_CONTENT_ADMIN_URL . '/js/cleaner.min.js',
			array( 'jquery', 'backbone', 'jquery-ui-dialog' ),
			$this->version,
			true
		);

		$remove = _x( 'Cancel Subscription and Deactivate', 'command (plugins)', 'nelio-content' );
		if ( nc_is_free_trial() ) {
			$remove = _x( 'Stop Trial and Deactivate', 'command (plugins)', 'nelio-content' );
		}//end if
		$spinner = '<span class="dashicons dashicons-update nc-animate-spinner"></span> ';
		wp_localize_script(
			'nelio-content-cleaner-js',
			'NelioContent',
			array(
				'i18n' => array(
					'actions' => array(
						'close'                           => _x( 'Close', 'command', 'nelio-content' ),
						'deactivate'                      => _x( 'Deactivate', 'command (plugins)', 'nelio-content' ),
						'cleanAndDeactivate'              => _x( 'Clean and Deactivate', 'command (plugins)', 'nelio-content' ),
						'cancelSubscriptionAndDeactivate' => $remove,
					),
					'feedback' => array(
						'deactivating'          => $spinner . _x( 'Deactivating&hellip;', 'text', 'nelio-content' ),
						'cleaning'              => $spinner . _x( 'Cleaning&hellip;', 'text', 'nelio-content' ),
						'cancelingSubscription' => $spinner . _x( 'Canceling Subscription&hellip;', 'text', 'nelio-content' ),
					),
					'titles'  => array(
						'error'            => _x( 'Error', 'text', 'nelio-content' ),
						'deactivatePlugin' => _x( 'Deactivate', 'title (plugins)', 'nelio-content' ),
					),
				),
			)
		);

	}//end enqueue_scripts()

	/**
	 * Add dialog partials.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_dialog_partials() {

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/cleaner/cleaner-dialog.php' );

	}//end add_dialog_partials()

}//end class

