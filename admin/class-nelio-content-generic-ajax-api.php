<?php
/**
 * The plugin uses several AJAX calls. This class implements some AJAX
 * callbacks required by our plugin, which did not fit in any other
 * class (such as, for instance, post-related or reference-related
 * calls).
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
 * This class implements several admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Generic_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		add_action( 'wp_ajax_nelio_content_get_users', array( $this, 'get_users' ) );
		add_action( 'wp_ajax_nelio_content_get_api_auth_token', array( $this, 'get_api_auth_token' ) );
		add_action( 'wp_ajax_nelio_content_update_profiles_availability', array( $this, 'update_profiles_availability' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint updates the information of a given reference.
	 *
	 * As a response, it returns the following:
	 *
	 *  * array of users, each compatible with User Backbone model.
	 *
	 * Possible `$_REQUEST` params:
	 *
	 *  * array $users Required. A list of user IDs.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_users() {

		// Retrieve and sanitize the list of user ids.
		$user_ids = array();
		if ( isset( $_REQUEST['users'] ) && is_array( $_REQUEST['users'] ) ) { // Input var okay.
			$user_ids = array_map( 'absint', $_REQUEST['users'] ); // Input var okay.
		}//end if

		// Result variable.
		$result = array();

		// Args for avatar.
		$args = array(
			'size'    => 60,
			'default' => 'blank',
		);

		// Query the users and save them to the result variable.
		$wp_users = get_users( array(
			'blog_id' => $GLOBALS['blog_id'],
			'include' => $user_ids,
		) );

		foreach ( $wp_users as $wp_user ) {

			$data = $wp_user->data;
			array_push( $result, array(
				'id'       => absint( $data->ID ),
				'email'    => $data->user_email,
				'name'     => $data->display_name,
				'photo'    => nc_get_avatar_url( $data->user_email, $args ),
				'editLink' => get_edit_user_link( $data->ID ),
				'role'     => nc_get_user_role( $wp_user ),
			) );

		}//end foreach

		// Send the result.
		wp_send_json( $result );

	}//end get_users()

	/**
	 * This AJAX endpoint returns a new token for accessing the API.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_api_auth_token() {

		wp_send_json( nc_generate_api_auth_token() );

	}//end get_api_auth_token()

	/**
	 * XXX
	 *
	 * @since  1.2.3
	 * @access public
	 */
	public function update_profiles_availability() {

		// Retrieve and sanitize the value.
		$has_profiles = array();
		if ( ! isset( $_REQUEST['value'] ) ) { // Input var okay.
			return;
		}//end if

		$has_profiles = trim( sanitize_text_field( wp_unslash(  $_REQUEST['value'] ) ) ) === 'yes';
		update_option( 'nc_has_social_profiles', $has_profiles );

	}//end update_profiles_availability()

}//end class

