<?php
/**
 * This file contains a helper class for working with (external) featured images.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/helper
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements some helper functions for working with featured images.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/helper
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */
class Nelio_Content_External_Featured_Image_Helper {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.1.1
	 * @access protected
	 * @var    Nelio_Content_External_Featured_Image_Helper
	 */
	protected static $_instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_External_Featured_Image_Helper the single instance of this class.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.1.1' ); // @codingStandardsIgnoreLine

	}//end __clone()


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.1.1' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * This function returns the URL of a Nelio Featured Image.
	 *
	 * Nelio Featured Images are either the user-set NelioEFI URL or, if featured
	 * image autosetting is enabled, the URL of one image included in the post.
	 *
	 * @param integer $post_id the ID of the post.
	 *
	 * @return boolean|string the URL of the featured image or false if none.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function get_nelio_featured_image( $post_id ) {

		$url = $this->get_external_featured_image( $post_id );
		if ( $url ) {
			return $url;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$auto_feat_image = $settings->get( 'auto_feat_image' );
		if ( 'disabled' === $auto_feat_image ) {
			return false;
		}//end if

		return $this->get_auto_featured_image( $post_id, $auto_feat_image );

	}//end get_nelio_featured_image()

	/**
	 * This function returns the value of the post meta `_nelioefi_url` if any.
	 *
	 * @param integer $post_id the ID of the post.
	 *
	 * @return boolean|string the URL of the featured image or false if none.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function get_external_featured_image( $post_id ) {

		// Use the external featured image (if any).
		$efi_url = get_post_meta( $post_id, '_nelioefi_url', true );
		if ( ! is_string( $efi_url ) || ! strlen( $efi_url ) ) {
			return false;
		}//end if

		return $efi_url;

	}//end get_external_featured_image()

	/**
	 * This function returns the alt value of the external featured image.
	 *
	 * @param integer $post_id the ID of the post.
	 *
	 * @return boolean|string the alt value of the external featured image
	 *                        or false if there isn't any.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function get_external_featured_alt( $post_id ) {

		if ( ! $this->get_external_featured_image( $post_id ) ) {
			return false;
		}//end if

		return get_post_meta( $post_id, '_nelioefi_alt', true );

	}//end get_external_featured_alt()

	/**
	 * This function returns the URL of one image included in the post.
	 *
	 * @param integer $post_id  the ID of the post.
	 * @param string  $position Optional. The image to return. Accepted values
	 *                          are `first`, `last`, and `any`. Default: `first`.
	 *
	 * @return boolean|string the URL of the featured image or false if none.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function get_auto_featured_image( $post_id, $position = 'first' ) {

		$images = get_post_meta( $post_id, '_nc_auto_efi', true );

		if ( ! is_array( $images ) || ! isset( $images[ $position ] ) ) {
			$images = $this->extract_featured_images_for_autoset( $post_id );
		}//end if

		if ( ! $images ) {
			return false;
		}//end if

		if ( isset( $images[ $position ] ) ) {
			return $images[ $position ];
		} else {
			return false;
		}//end if

	}//end get_auto_featured_image()

	/**
	 * This function analyzes a post and extracts the included images, so that
	 * they can be used as featured images.
	 *
	 * @param integer $post_id the ID of the post.
	 *
	 * @return boolean|array the extracted images or false if none could be
	 * w                     extracted.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function extract_featured_images_for_autoset( $post_id ) {

		if ( wp_is_post_revision( $post_id) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		$post = get_post( $post_id );
		if ( ! $post || is_wp_error( $post ) ) {
			delete_post_meta( $post_id, '_nc_auto_efi' );
			return false;
		}//end if

		$matches = array();
		preg_match_all(
			'/<img[^>]*src=("[^"]*"|\'[^\']*\')/i',
			$post->post_content,
			$matches
		);

		if ( count( $matches ) <= 1 ) {
			delete_post_meta( $post_id, '_nc_auto_efi' );
			return false;
		}//end if

		$matches = $matches[1];
		foreach ( $matches as $key => $value ) {
			$matches[ $key ] = preg_replace( '/^.(.*).$/', '$1', $value );
		}//end foreach

		$result = array(
			'first' => '',
			'any'   => '',
			'last'  => '',
		);

		if ( count( $matches ) > 0 ) {
			$last = count( $matches ) - 1;
			$result['first'] = $matches[0];
			$result['any']   = $matches[ $last ];
			$result['last']  = $matches[ $last ];
		}//end if

		if ( count( $matches ) > 2 ) {

			unset( $matches[0] );
			unset( $matches[ count( $matches ) ] );

			$old_images = get_post_meta( $post_id, '_nc_auto_efi', true );
			if ( ! is_array( $old_images ) ) {
				$old_images = array();
			}//end if

			if ( isset( $old_images['any'] ) && in_array( $old_images['any'], $matches ) ) {
				$result['any'] = $old_images['any'];
			} else {
				$result['any'] = $matches[ rand( 1, count( $matches ) ) ];
			}//end if

		}//end if

		update_post_meta( $post_id, '_nc_auto_efi', $result );
		return $result;

	}//end extract_featured_images_for_autoset()

}//end class

