<?php
/**
 * The file that defines an autoloader class, for automatically loading classes.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nelio Content Settings Autoloader
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Settings_Autoloader {

	/**
	 * The Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {

		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
		}
		spl_autoload_register( array( $this, 'autoload' ) );

	}//end __construct()

	/**
	 * Loads a class file. Returns whether the file has been successfully loaded.
	 *
	 * @param string $path The class file to be loaded.
	 *
	 * @return bool Whether the file has been successfully loaded.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}
		return false;
	}//end load_file()

	/**
	 * Auto-load Nelio_Content classes on demand to reduce memory consumption.
	 *
	 * @param string $class The class to be loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function autoload( $class ) {
		if ( strpos( $class, 'Nelio_Content_' ) !== 0 ) {
			return;
		}

		$dictionary = array();
		$dictionary['Nelio_Content_Abstract_Setting'] = '/lib/settings/abstract-class-nelio-content-abstract-setting.php';
		$dictionary['Nelio_Content_Checkbox_Setting'] = '/lib/settings/class-nelio-content-checkbox-setting.php';
		$dictionary['Nelio_Content_Checkbox_Set_Setting'] = '/lib/settings/class-nelio-content-checkbox-set-setting.php';
		$dictionary['Nelio_Content_Conversion_Value_Setting'] = '/lib/settings/class-nelio-content-conversion-value-setting.php';
		$dictionary['Nelio_Content_Input_Setting'] = '/lib/settings/class-nelio-content-input-setting.php';
		$dictionary['Nelio_Content_Notification_Email_Setting'] = '/lib/settings/class-nelio-content-notification-email-setting.php';
		$dictionary['Nelio_Content_Quota_Limit_Setting'] = '/lib/settings/class-nelio-content-quota-limit-setting.php';
		$dictionary['Nelio_Content_Radio_Setting'] = '/lib/settings/class-nelio-content-radio-setting.php';
		$dictionary['Nelio_Content_Range_Setting'] = '/lib/settings/class-nelio-content-range-setting.php';
		$dictionary['Nelio_Content_Select_Algorithm_Setting'] = '/lib/settings/class-nelio-content-select-algorithm-setting.php';
		$dictionary['Nelio_Content_Select_Setting'] = '/lib/settings/class-nelio-content-select-setting.php';
		$dictionary['Nelio_Content_Setting'] = '/lib/settings/interface-nelio-content-setting.php';
		$dictionary['Nelio_Content_Text_Area_Setting'] = '/lib/settings/class-nelio-content-text-area-setting.php';

		/**
		 * The path.
		 * @var string $path
		 */
		$path = '';
		if ( isset( $dictionary[ $class ] ) ) {
			$path = NELIO_CONTENT_INCLUDES_DIR . $dictionary[ $class ];
		}

		/**
		 * This action fires right before a certain Nelio Content class is automatically loaded.
		 *
		 * @param string $class The name of the class to be loaded.
		 * @param string $path  The path of the file that contains this class.
		 *                      If it's empty, the class was not found.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_autoload_class', $class, $path );

		if ( ! empty( $path ) ) {
			$this->load_file( $path );
		}
	}//end autoload()

}//end class

new Nelio_Content_Settings_Autoloader();
