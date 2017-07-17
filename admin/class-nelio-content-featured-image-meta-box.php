<?php
/**
 * This file defines the class for adding the featured image meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class defines the featured image meta box and contains some useful
 * functions for managing it.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */
class Nelio_Content_Featured_Image_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes the meta box.
	 *
	 * @since  1.1.1
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'remove_wordpress_thumbnail_meta_box' ), 999 );
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}//end init()

	/**
	 * This function removes WordPress' default featured image meta box.
	 *
	 * @since 1.1.1
	 * @access public
	 */
	public function remove_wordpress_thumbnail_meta_box() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_single_feat_img_meta_box' ) ) {
			return;
		}//end if

		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {

			if ( ! post_type_supports( $post_type, 'thumbnail' ) ) {
				continue;
			}//end if

			remove_meta_box( 'postimagediv', $post_type, 'side' );

		}//end foreach

	}//end remove_wordpress_thumbnail_meta_bo()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		$settings = Nelio_Content_Settings::instance();
		$custom_box = $settings->get( 'use_single_feat_img_meta_box' );

		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {

			if ( ! post_type_supports( $post_type, 'thumbnail' ) ) {
				continue;
			}//end if

			$post_type_object = get_post_type_object( $post_type );

			if ( $custom_box ) {
				$label = $post_type_object->labels->featured_image;
			} else {
				$label = __( 'External Featured Image', 'text', 'nelio-content' );
			}//end if

			add_meta_box(
				'nelio-content-featured-image',
				$label,
				array( $this, 'display' ),
				$post_type,
				'side',
				'default'
			);

		}//end foreach

	}//end add()

	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	// @Implements
	public function display( $post ) { // @codingStandardsIgnoreLine

		echo '<div id="nc-featured-image-container"></div>';

		// Add default data in the edit screen.
		$mode = 'none';
		$thumbnail_id = -1;

		$feat_image_helper = Nelio_Content_External_Featured_Image_Helper::instance();
		$efi = $feat_image_helper->get_external_featured_image( $post->ID );
		$thumbnail_id = absint( get_post_thumbnail_id() );

		if ( $efi ) {

			$mode = 'efi';
			$thumbnail_id = -1;
			$url = $efi;
			$alt = get_post_meta( $post->ID, '_nelioefi_alt', true );
			if ( empty( $alt ) ) {
				$alt = '';
			}//end if

		} elseif ( is_int( $thumbnail_id ) && 0 < $thumbnail_id ) {

			$mode = 'wp';
			$url = wp_get_attachment_image_src( $thumbnail_id, 'full' );
			$url = $url[0];
			$alt = '';

		} else {

			$mode = 'none';
			$thumbnail_id = -1;
			$url = '';
			$alt = '';

		}//end if

		$auto_url = '';
		$settings = Nelio_Content_Settings::instance();
		$position = $settings->get( 'auto_feat_image' );
		if ( 'disabled' !== $position ) {
			$auto_url = $feat_image_helper->get_auto_featured_image( $post->ID, $position );
		}//end if

		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/featured-image/featured-image-data.php' );

		// Load templates.
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/featured-image/featured-image.php' );
		include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/featured-image/external-featured-image-dialog.php' );

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine

		if ( ! isset( $_REQUEST['_nc-featured-image-meta-box'] ) ) { // Input var okay.
			return;
		}//end if

		if ( wp_is_post_revision( $post_id) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		$nelioefi_url = '';
		if ( isset( $_REQUEST['_nelioefi_url'] ) ) { // Input var okay.
			$nelioefi_url = trim( sanitize_text_field( wp_unslash( $_REQUEST['_nelioefi_url'] ) ) ); // Input var okay.
		}//end if

		$nelioefi_alt = '';
		if ( isset( $_REQUEST['_nelioefi_alt'] ) ) { // Input var okay.
			$nelioefi_alt = trim( sanitize_text_field( wp_unslash( $_REQUEST['_nelioefi_alt'] ) ) ); // Input var okay.
		}//end if

		if ( empty( $nelioefi_url ) ) {
			delete_post_meta( $post_id, '_nelioefi_url' );
			delete_post_meta( $post_id, '_nelioefi_alt' );
		} else {
			update_post_meta( $post_id, '_nelioefi_url', $nelioefi_url );
			update_post_meta( $post_id, '_nelioefi_alt', $nelioefi_alt );
		}//end if

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_single_feat_img_meta_box' ) ) {
			return;
		}//end if

		if ( ! empty( $nelioefi_url ) ) {
			delete_post_meta( $post_id, '_thumbnail_id' );
			return;
		}//end if

		$thumbnail_id = '';
		if ( isset( $_REQUEST['_thumbnail_id'] ) ) { // Input var okay.
			$thumbnail_id = absint( $_REQUEST['_thumbnail_id'] ); // Input var okay.
		}//end if

		if ( 0 < $thumbnail_id ) {
			update_post_meta( $post_id, '_thumbnail_id', $thumbnail_id );
		} else {
			delete_post_meta( $post_id, '_thumbnail_id' );
		}//end if

	}//end save()

}//end class

