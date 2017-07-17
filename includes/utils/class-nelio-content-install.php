<?php
/**
 * The file that includes installation-related functions and actions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class configures WordPress and installs some capabilities.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Install {

	/**
	 * Hook in tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function init() {

		add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 );

	}//end init()

	/**
	 * Checks the currently-installed version and, if it's old, installs the new one.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function check_version() {

		$last_install_version = get_option( 'nc_version' );
		$this_version = Nelio_Content()->get_version();
		if ( ! defined( 'IFRAME_REQUEST' ) && ( $last_install_version !== $this_version ) ) {
			self::install();

			/**
			 * Fires once the plugin has been updated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'nelio_content_updated' );
		}//end if

	}//end check_version()

	/**
	 * Install Nelio Content.
	 *
	 * This function registers new post types, adds a few capabilities, and more.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function install() {

		if ( ! defined( 'NELIO_CONTENT_INSTALLING' ) ) {
			define( 'NELIO_CONTENT_INSTALLING', true );
		}//end if

		// Installation actions here.
		self::set_proper_permissions();

		// Update version.
		delete_option( 'nc_version' );
		add_option( 'nc_version', Nelio_Content()->get_version() );

		// Check if the user has social profiles.
		update_option( 'nc_has_social_profiles', self::has_social_profiles() );

		if ( ! get_transient( 'nelio_content_was_free_trial_offered' ) ) {
			set_transient( 'nelio_content_was_free_trial_offered', true, 600 );
		}//end if

		/**
		 * Fires once the plugin has been installed.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_installed' );

	}//end install()

	/**
	 * Creates capabilities for editing references and assigns them to different
	 * user roles.
	 *
	 * @since  1.2.3
	 * @access public
	 */
	private static function has_social_profiles() {

		if ( ! nc_get_site_id() ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/profiles', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		$profiles = array();
		if ( isset( $response['body'] ) ) {
			$profiles = json_decode( $response['body'] );
		}//end if

		return count( $profiles ) > 0;

	}//end has_social_profiles()

	/**
	 * Creates capabilities for editing references and assigns them to different
	 * user roles.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private static function set_proper_permissions() {

		$contributor_caps = array(
			'read_nc_reference',
			'read_private_nc_references',
		);

		$author_caps = array_merge( $contributor_caps, array(
			'edit_nc_references',
			'edit_nc_reference',
			'edit_published_nc_references',
		) );

		$editor_caps = array_merge( $author_caps, array(
			'edit_others_nc_references',
			'publish_nc_references',
			'edit_private_nc_references',
			'edit_others_nc_reference',
			'create_nc_references',
			'delete_nc_reference',
			'delete_nc_references',
			'delete_others_nc_references',
			'delete_private_nc_references',
			'delete_published_nc_references',
		) );

		// Set new roles.
		$role = get_role( 'administrator' );
		foreach ( $editor_caps as $cap ) {
			$role->add_cap( $cap );
		}//end foreach

		$role = get_role( 'editor' );
		foreach ( $editor_caps as $cap ) {
			$role->add_cap( $cap );
		}//end foreach

		$role = get_role( 'author' );
		foreach ( $author_caps as $cap ) {
			$role->add_cap( $cap );
		}//end foreach

		$role = get_role( 'contributor' );
		foreach ( $contributor_caps as $cap ) {
			$role->add_cap( $cap );
		}//end foreach

	}//end set_proper_permissions()

}//end class

Nelio_Content_Install::init();
