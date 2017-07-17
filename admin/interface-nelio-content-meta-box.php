<?php
/**
 * This file defines an interface for creating meta boxes in WordPress.
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
 * An interface for creating meta boxes in WordPress.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
interface Nelio_Content_Meta_Box {

	/**
	 * This function wraps WordPress' built-in function `add_meta_box` and it's
	 * used for adding a meta box to the UI.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add();

	/**
	 * Loads the HTML content of the implementing meta box.
	 *
	 * @param WP_Post $post The post that this meta box is related to.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function display( $post );

	/**
	 * Saves any information provided by the user, assuming all required checks are OK.
	 *
	 * @param int post_id The post that we're saving.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function save( $post_id );

}//end interface

