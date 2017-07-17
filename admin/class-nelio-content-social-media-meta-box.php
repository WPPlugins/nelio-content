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
class Nelio_Content_Social_Media_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes this meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'add' ) );

	}//end init()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		if ( ! nc_is_subscribed_to( 'team-plan' ) && ! nc_is_current_user_the_manager() ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-social-media',
				_x( 'Social Media', 'text', 'nelio-content' ),
				array( $this, 'display' ),
				$post_type,
				'normal',
				'default'
			);

		}//end foreach

	}//end add()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	public function display( $post ) { // @codingStandardsIgnoreLine

		$container_name = 'nelio-content-social-timeline-container';
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';

		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-message-editor/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-timeline/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine
		// No need to save anything (this meta box works with the cloud).
	}//end save()

}//end class

