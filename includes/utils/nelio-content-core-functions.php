<?php
/**
 * Nelio Content core functions.
 *
 * General core functions available on both the front-end and admin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Returns this site's ID.
 *
 * @return string This site's ID. This option is used for accessing AWS.
 *
 * @since 1.0.0
 */
function nc_get_site_id() {

	return get_option( 'nc_site_id', false );

}//end nc_get_site_id()

/**
 * Returns the user ID of the current manager.
 *
 * @return int User ID of the current manager.
 *
 * @since 1.0.0
 */
function nc_get_manager_id() {

	return absint( get_option( 'nc_manager_id', 0 ) );

}//end nc_get_manager_id()

/**
 * Returns the limits the plugin has, based on the current subscription and so on.
 *
 * @return array the limits the plugin has.
 *
 * @since 1.0.0
 */
function nc_get_site_limits() {

	return get_option( 'nc_site_limits', array(
		'maxProfiles'           => -1,
		'maxProfilesPerNetwork' => 1,
	)	);

}//end nc_get_site_limits()

/**
 * Returns the API url for the specified method.
 *
 * @param string $method  The metho we want to use.
 * @param string $context Either 'wp' or 'browser', depending on the location
 *                        in which the resulting URL has to be used.
 *                        Only wp calls might use the proxy URL.
 *
 * @return string the API url for the specified method.
 *
 * @since 1.1.0
 */
function nc_get_api_url( $method, $context ) {

	if ( 'browser' === $context ) {
		return NELIO_CONTENT_API_URL . $method;
	}//end if

	$settings = Nelio_Content_Settings::instance();
	if ( $settings->get( 'uses_proxy' ) ) {
		return NELIO_CONTENT_API_URL_WITHOUT_SNI . $method;
	} else {
		return NELIO_CONTENT_API_URL . $method;
	}//end if

}//end nc_get_api_url()

/**
 * A token for accessing the API.
 *
 * @since 1.0.0
 * @var   string
 */
$nc_api_auth_token = '';

/**
 * Returns a new token for accessing the API.
 *
 * @param string mode Either 'regular' or 'skip-errors'. If the latter is used, the function
 *                    won't generate any HTML errors.
 *
 * @return string a new token for accessing the API.
 *
 * @since 1.0.0
 */
function nc_generate_api_auth_token( $mode = 'regular' ) {

	global $nc_api_auth_token;

	// If we already have a token, return it.
	if ( ! empty( $nc_api_auth_token ) ) {
		return $nc_api_auth_token;
	}//end if

	// Get the new token.
	$uid = get_current_user_id();
	$role = nc_get_current_user_role();
	$secret = get_option( 'nc_api_secret', false );

	$settings = Nelio_Content_Settings::instance();
	$data = array(
		'method'    => 'POST',
		'timeout'   => 30,
		'sslverify' => ! $settings->get( 'uses_proxy' ),
		'headers'   => array(
			'accept'       => 'application/json',
			'content-type' => 'application/json',
		),
		'body' => wp_json_encode( array(
			'id'   => $uid,
			'role' => $role,
			'auth' => md5( $uid . $role . $secret ),
		)	),
	);

	$nc_plan = false;

	// Iterate to obtain the token, or else things will go wrong.
	for ( $i = 0; $i < 3; ++$i ) {

		$url = nc_get_api_url( '/site/' . get_option( 'nc_site_id' ) . '/key', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( ! nc_is_response_valid( $response ) ) {
			sleep( 3 );
			continue;
		}//end if

		// Save the new token.
		$response = json_decode( $response['body'], true );
		if ( isset( $response['token'] ) ) {
			$nc_api_auth_token = $response['token'];
			$nc_plan = nc_get_product_plan( $response['product'] );
		}//end if

		if ( ! empty( $nc_api_auth_token ) ) {
			break;
		}//end if

		sleep( 3 );

	}//end for

	// Send error if we couldn't get an API key.
	if ( 'skip-errors' !== $mode ) {

		$error_message = _x( 'There was an error while accessing Nelio Content\'s API.', 'error', 'nelio-content' );

		if ( empty( $nc_api_auth_token ) ) {

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				header( 'HTTP/1.1 500 Internal Server Error' );
				wp_send_json( $error_message );
			} else {
				return false;
			}//end if

		}//end if

		// Maybe update the subscription if it differs from what we have.
		if ( nc_is_subscribed() && ! nc_is_subscribed_to( $nc_plan ) ) {

			$data = array(
				'method'    => 'GET',
				'timeout'   => 30,
				'sslverify' => ! $settings->get( 'uses_proxy' ),
				'headers'   => array(
					'Authorization' => 'Bearer ' . $nc_api_auth_token,
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				),
			);

			$url = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
			$response = wp_remote_request( $url, $data );

			if ( nc_is_response_valid( $response ) ) {
				$site_info = json_decode( $response['body'], true );
				nc_update_subscription_information_with_site_object( $site_info );
			}//end if

		}//end if

	}//end if

	return $nc_api_auth_token;

}//end nc_generate_api_auth_token()

/**
 * Given an ordered list of keys, returns the value of the first key that
 * has a value in the array.
 *
 * @param array        $array       A list of key-value pairs.
 * @param array|string $key_options An ordered list of keys.
 * @param mixed        $default     Optional. The value that has to be
 *                         returned if none of the given keys appear in
 *                         the given array. Default: `false`.
 *
 * @return mixed The value of the first key in `$key_options` that appears
 *               in $array.
 *
 * @since 1.0.0
 */
function nc_get_first_option( $array, $key_options, $default = false ) {

	if ( ! is_array( $key_options ) ) {
		$key_options = array( $key_options );
	}//end if

	foreach ( $key_options as $key ) {

		if ( isset( $array[ $key ] ) ) {
			return $array[ $key ];
		}//end if

	}//end foreach

	return $default;

}//end nc_get_first_option()

/**
 * Returns the role of the current user.
 *
 * @param integer|WP_User $user the user.
 *
 * @return string the role of the current user.
 *
 * @since 1.0.0
 */
function nc_get_user_role( $user ) {

	if ( is_int( $user ) ) {
		$user = get_user_by( 'id', $user );
	}//end if

	if ( ! $user ) {
		return 'subscriber';
	} elseif ( $user->has_cap( 'manage_options' ) ) {
		return 'administrator';
	} elseif ( $user->has_cap( 'edit_others_posts' ) ) {
		return 'editor';
	} elseif ( $user->has_cap( 'publish_posts' ) ) {
		return 'author';
	} elseif ( $user->has_cap( 'edit_posts' ) ) {
		return 'contributor';
	} else {
		return 'subscriber';
	}//end if

}//end nc_get_user_role()

/**
 * Returns the role of the current user.
 *
 * @return string the role of the current user.
 *
 * @since 1.0.0
 */
function nc_get_current_user_role() {

	if ( ! function_exists( 'current_user_can' ) ) {
		return 'subscriber';
	} elseif ( current_user_can( 'manage_options' ) ) {
		return 'administrator';
	} elseif ( current_user_can( 'edit_others_posts' ) ) {
		return 'editor';
	} elseif ( current_user_can( 'publish_posts' ) ) {
		return 'author';
	} elseif ( current_user_can( 'edit_posts' ) ) {
		return 'contributor';
	} else {
		return 'subscriber';
	}//end if

}//end nc_get_current_user_role()

/**
 * Checks whether the current user can behave as the specified role or not.
 *
 * @param string $req_role The (minimum) required role.
 * @param string $mode     Optional. If `exactly`, the current user must have
 *                         this very role. If `or-above`, that role or above
 *                         would return true. Default: `or-above`.
 *
 * @return boolean whether the user can behave as the specified role or not.
 *
 * @since 1.0.0
 */
function nc_is_current_user( $req_role, $mode = 'or-above' ) {

	$role = nc_get_current_user_role();

	// If the required role is the user's role, return true.
	if ( $role === $req_role ) {
		return true;
	}//end if

	// If the required role is not the user's role, we have to look for
	// "subsumed" roles when the mode is not "exactly".
	if ( 'or-above' === $mode ) {

		switch ( $role ) {

			case 'administrator':
				return true;

			case 'editor':
				return in_array( $req_role, array( 'author', 'contributor', 'subscriber' ), true );

			case 'author':
				return in_array( $req_role, array( 'contributor', 'subscriber' ), true );

			case 'contributor':
				return in_array( $req_role, array( 'subscriber' ), true );

		}//end switch

	}//end if

	return false;

}//end nc_is_current_user()

/**
 * Checks whether the current user is the only user that has access to plugin's
 * functionalities or not.
 *
 * @return boolean whether the current user is the only user that has access to
 *                 plugin's functionalities or not.
 *
 * @since 1.0.0
 */
function nc_is_current_user_the_manager() {

	if ( nc_is_subscribed_to( 'team-plan' ) ) {
		return false;
	}//end if

	return get_current_user_id() === nc_get_manager_id();

}//end nc_is_current_user_the_manager()

/**
 * This function makes sure that a certain pair of meta key and value for a
 * given posts exists only once in the database.
 *
 * @param string $post_id    the post ID related to the given meta.
 * @param string $meta_key   the meta key.
 * @param mixed  $meta_value the meta value.
 *
 * @return integer the meta ID, false otherwise.
 *
 * @since 1.0.0
 */
function nc_add_post_meta_once( $post_id, $meta_key, $meta_value ) {

	delete_post_meta( $post_id, $meta_key, $meta_value );
	return add_post_meta( $post_id, $meta_key, $meta_value );

}//end nc_add_post_meta_once()

/**
 * This function returns the timezone/UTC offset used in WordPress.
 *
 * @return string the meta ID, false otherwise.
 *
 * @since 1.0.0
 */
function nc_get_timezone() {

	$timezone_string = get_option( 'timezone_string', '' );
	if ( ! empty( $timezone_string ) ) {

		if ( 'UTC' === $timezone_string ) {
			return '+00:00';
		} else {
			return $timezone_string;
		}//end if

	}//end if

	$utc_offset = get_option( 'gmt_offset', 0 );

	if ( $utc_offset < 0 ) {
		$utc_offset_no_dec = ceil( $utc_offset );
		$result = sprintf( '-%02d', absint( $utc_offset_no_dec ) );
	} else {
		$utc_offset_no_dec = floor( $utc_offset );
		$result = sprintf( '+%02d', absint( $utc_offset_no_dec ) );
	}//end if

	if ( $utc_offset == $utc_offset_no_dec ) { // @codingStandardsIgnoreLine
		$result .= ':00';
	} else {
		$result .= ':30';
	}//end if

	return $result;

}//end nc_get_timezone()

/**
 * This function returns the two-letter locale used in WordPress.
 *
 * @return string the two-letter locale used in WordPress.
 *
 * @since 1.0.0
 */
function nc_get_language() {

	// Language of the blog.
	$lang = get_option( 'WPLANG' );
	if ( empty( $lang ) ) {
		$lang = 'en_US';
	}//end if

	// Convert into a two-char string.
	if ( strpos( $lang, '_' ) > 0 ) {
		$lang = substr( $lang, 0, strpos( $lang, '_' ) );
	}//end if

	return $lang;

}//end nc_get_language()

/**
 * Retrieves the avatar URL.
 *
 * @param mixed $id_or_email The Gravatar to retrieve a URL for. Accepts a user_id, gravatar md5 hash,
 *                           user email, WP_User object, WP_Post object, or WP_Comment object.
 * @param array $args        Optional. Arguments to return instead of the default arguments.
 *
 * @return false|string The URL of the avatar we found, or false if we couldn't find an avatar.
 *
 * @since 1.0.0
 */
function nc_get_avatar_url( $id_or_email, $args = null ) {

	// This function is only available since WordPress 4.2.0.
	if ( function_exists( 'get_avatar_url' ) ) {
		return get_avatar_url( $id_or_email, $args );
	}//end if

	// Try to extract the URL from the tag.
	$args = wp_parse_args( $args, array(
		'size'    => 96,
		'default' => 'blank',
		'alt'     => '',
	) );

	$avatar = get_avatar( $id_or_email, $args['size'], $args['default'], $args['alt'], $args );
	preg_match( '/^<img.*?src=(["\'])(.*?)\1.*$/', $avatar, $matches );

	if ( count( $matches ) > 2 ) {
		$result = str_replace( '&amp;', '&', $matches[2] );
	} else {
		$result = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
	}//end if

	return $result;

}//end nc_get_avatar_url()

