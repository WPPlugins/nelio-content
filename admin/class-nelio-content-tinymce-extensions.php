<?php
/**
 * Class for extending TinyMCE.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Class for extending TinyMCE.
 *
 * Defines some methods for adding new buttons and so on.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_TinyMce_Extensions {

	/**
	 * Adds TinyMCE-related hooks.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function define_admin_hooks() {

		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_tinymce_toolbar_buttons' ) );

	}//end define_admin_hooks()

	/**
	 * Adds a TinyMCE plugin compatible JS file to TinyMCE.
	 *
	 * @param array $plugin_array Array of registered TinyMCE plugins.
	 *
	 * @return array Modified array of registered TinyMCE plugins.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function add_tinymce_plugin( $plugin_array ) {

		if ( $this->can_tinymce_be_extended() ) {
			$plugin_array['nelio_content'] = NELIO_CONTENT_ADMIN_URL . '/js/tinymce.min.js';
		}//end if
		return $plugin_array;

	}//end add_tinymce_plugin()

	/**
	 * Adds all buttons in the TinyMCE's toolbar.
	 *
	 * @param array $buttons Array of registered TinyMCE buttons.
	 *
	 * @return array Modified array of registered TinyMCE buttons.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function add_tinymce_toolbar_buttons( $buttons ) {

		if ( $this->can_tinymce_be_extended() ) {
			array_push( $buttons, 'nc_share_selection' );
		}//end if

		return $buttons;

	}//end add_tinymce_toolbar_buttons()

	/**
	 * Returns whether TinyMCE can be extended or not.
	 *
	 * TinyMCE can be extended if the current user can manage the plugin and the
	 * post type we're editing is a post type used in the calendar.
	 *
	 * @return boolean whether TinyMCE can be extended or not.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function can_tinymce_be_extended() {

		if ( ! nc_is_subscribed() && ! nc_is_current_user_the_manager() ) {
			return false;
		}//end if

		$screen = get_current_screen();
		$settings = Nelio_Content_Settings::instance();
		if ( ! in_array( $screen->id, $settings->get( 'calendar_post_types' ) ) ) {
			return false;
		}//end if

		return true;

	}//end can_tinymce_be_extended()

}//end class

