<?php
/**
 * Missing WordPress functions, due to old versions of WordPress.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

if ( ! function_exists( 'wp_json_encode' ) ) {

	/**
	 * Encode a variable into JSON, with some sanity checks.
	 *
	 * @param  mixed   $data    Variable (usually an array or object) to encode as JSON.
	 * @param  integer $options Optional. Options to be passed to json_encode(). Default 0.
	 * @param  integer $depth   Optional. Maximum depth to walk through $data. Must be
	 *                          greater than 0. Default 512.
	 * @return string|false The JSON encoded string, or false if it cannot be encoded.
	 *
	 * @since 1.0.0
	 */
	function wp_json_encode( $data, $options = 0, $depth = 512 ) {
		return json_encode( $data, $options, $depth ); // @codingStandardsIgnoreLine
	}//end wp_json_encode()

}//end if

