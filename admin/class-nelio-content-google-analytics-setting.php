<?php
/**
 * This file contains the setting for connecting Google Analytics with
 * Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents the setting for connecting Google Analytics with
 * Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */
class Nelio_Content_Google_Analytics_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * The current value of this setting.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    string
	 */
	protected $value;

	/**
	 * Creates a new instance of this class.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function __construct() {

		parent::__construct( 'google_analytics_view' );

	}//end __construct()

	/**
	 * Specifies the value of the setting.
	 *
	 * @param string $value The current value of this setting.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function set_value( $value ) {

		$this->value = $value;

	}//end set_value()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable, PHPMD.ShortVariableName ) */
	public function display() { // @codingStandardsIgnoreLine

		$id    = str_replace( '_', '-', $this->name );
		$name  = $this->option_name . '[' . $this->name . ']';
		$value = $this->value;
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/settings/google-analytics.php';

	}//end display()

	// @Implements
	public function sanitize( $input ) { // @codingStandardsIgnoreLine

		$value = $input[ $this->name ];
		if ( ! empty( $value ) ) {
			$value = sanitize_text_field( $value );
		} else {
			$value = false;
		}//end if

		$input[ $this->name ] = $value;
		return $input;

	}//end sanitize()

}//end class
