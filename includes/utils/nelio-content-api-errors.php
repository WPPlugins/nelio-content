<?php
/**
 * This file contains a single function that translates an API error code to
 * its error message.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * Returns the reference whose ID is the given ID.
 *
 * @param string         $code    API error code.
 * @param string|boolean $default Optional. Default error message.
 *
 * @return string Error message associated to the given error code.
 *
 * @since  1.0.0
 * @access public
 */
function nc_get_error_message( $code, $default = false ) {

	switch ( $code ) {

		case 'LicenseNotFound':
			return _x( 'Invalid license code.', 'error', 'nelio-content' );

		default:
			return $default;

	}//end switch

}//end nc_get_error_message()

/**
 * This function checks whether the response of a `wp_remote_*` call is valid
 * or not. A response is valid if it's not a WP_Error and the response code is
 * 200.
 *
 * @param array $response the response of a `wp_remote_*` call.
 *
 * @return boolean Whether the response is valid (i.e. not a WP_Error and a 200
 *                 response code) or not.
 *
 * @since 1.0.0
 */
function nc_is_response_valid( $response ) {

	// If we couldn't open the page, let's return an empty result object.
	if ( is_wp_error( $response ) ) {
		return false;
	}//end if

	if ( ! isset( $response['response'] ) ) {
		return true;
	}//end if

	$response = $response['response'];
	if ( ! isset( $response['code'] ) ) {
		return true;
	}//end if

	if ( 200 === $response['code'] ) {
		return true;
	}//end if

	return false;

}//end nc_is_response_valid()

/**
 * This function checks if the given response is valid or not. If it isn't,
 * it'll send an HTTP error header (forwarding the original error code or
 * generating a new `500 Internal Server Error`) and a JSON describing the
 * error.
 *
 * @param array $response the response of a `wp_remote_*` call.
 *
 * @since 1.0.0
 */
function nc_maybe_send_error_json( $response ) {

	if ( nc_is_response_valid( $response ) ) {
		return;
	}//end if

	// If we couldn't open the page, let's return an empty result object.
	if ( is_wp_error( $response ) ) {
		header( 'HTTP/1.1 500 Internal Server Error' );
		wp_send_json( _x( 'Unable to access Nelio Content\'s API.', 'error', 'nelio-content' ) );
	}//end if

	// Extract body and response.
	$body = json_decode( $response['body'], true );
	$response = $response['response'];

	// If the error is not an Unauthorized request, let's forward it to the user.
	$summary = $response['code'] . ' ' . $response['message'];
	$header = 'HTTP/1.1 ' . $summary;
	if ( false === preg_match( '/^HTTP\/1.1 [0-9][0-9][0-9]( [A-Z][a-z]+)+$/', $header ) ) {
		$summary = '500 Internal Server Error';
		$header = 'HTTP/1.1 500 Internal Server Error';
	}//end if

	// Check if the API returned an error code and error message.
	$error_message = false;
	if ( ! empty( $body['errorType'] ) && ! empty( $body['errorMessage'] ) ) {
		$error_message = nc_get_error_message( $body['errorType'], $body['errorMessage'] );
	}//end if

	if ( empty( $error_message ) ) {
		$error_message = sprintf(
			_x( 'There was an error while accessing Nelio Content\'s API: %s.', 'error', 'nelio-content' ),
			$summary
		);
	}//end if

	// Send code.
	header( $header );
	wp_send_json( $error_message );

}//end nc_maybe_send_error_json()

