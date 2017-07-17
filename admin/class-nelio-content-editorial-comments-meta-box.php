<?php
/**
 * This file defines the editorial comments meta box.
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
 * This class defines the editorial comments meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Editorial_Comments_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes the meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'add' ) );

	}//end init()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		if ( ! nc_is_subscribed_to( 'team-plan' ) ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-editorial-comments',
				__( 'Editorial Comments', 'nelio-content' ),
				array( $this, 'display' ),
				$post_type,
				'normal',
				'default'
			);

		}//end foreach

	}//end add()

	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	// @Implements
	public function display( $post ) { // @codingStandardsIgnoreLine

		$container_name = 'nelio-content-editorial-comments-container';

		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/editorial-comments/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine
		// No need to save anything (this meta box works with the cloud).
	}//end save()

}//end class

