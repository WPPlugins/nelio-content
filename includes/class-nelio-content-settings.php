<?php
/**
 * This file has the Settings class, which defines and registers Nelio Content's Settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( NELIO_CONTENT_INCLUDES_DIR . '/lib/settings/abstract-class-nelio-content-abstract-settings.php' );

/**
 * The Settings class, responsible of defining, registering, and providing access to all Nelio Content's settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
final class Nelio_Content_Settings extends Nelio_Content_Abstract_Settings {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Nelio_Content_Settings
	 */
	private static $_instance;

	/**
	 * Initialize the class, set its properties, and add the proper hooks.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function __construct() {

		parent::__construct( 'nelio-content' );

	}//end __construct()

	/** . @Implements */
	public function set_tabs() { // @codingStandardsIgnoreLine

		// Add as many tabs as you want. If you have one tab only, no tabs will be shown at all.
		$tabs = array(

			array(
				'name'    => 'social-profiles',
				'label'   => _x( 'Social Profiles', 'text (settings tab)', 'nelio-content' ),
				'partial' => NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-settings-tab.php',
			),

			array(
				'name'   => 'content',
				'label'  => _x( 'Content', 'text (settings tab)', 'nelio-content' ),
				'fields' => include NELIO_CONTENT_INCLUDES_DIR . '/data/content-tab.php',
			),

			array(
				'name'   => 'advanced',
				'label'  => _x( 'Advanced', 'text (settings tab)', 'nelio-content' ),
				'fields' => include NELIO_CONTENT_INCLUDES_DIR . '/data/advanced-tab.php',
			),

		);

		$this->do_set_tabs( $tabs );

	}//end set_tabs()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Settings the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}//end instance()

}//end class
