<?php
/**
 * This file contains the setting for selecting which post types can be managed
 * using Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents a the setting for selecting which post types can be
 * managed using Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.0
 */
class Nelio_Content_Calendar_Post_Type_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * The currently-selected value of this select.
	 *
	 * @since  1.1.0
	 * @access protected
	 * @var    string
	 */
	protected $value;

	/**
	 * Creates a new instance of this class.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function __construct() {

		parent::__construct( 'calendar_post_types' );

	}//end __construct()

	/**
	 * Specifies which option is selected.
	 *
	 * @param string $value The currently-selected value of this select.
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function set_value( $value ) {

		$this->value = $value;

	}//end set_value()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable, PHPMD.ShortVariableName ) */
	public function display() { // @codingStandardsIgnoreLine

		$id   = str_replace( '_', '-', $this->name );
		$name = $this->option_name . '[' . $this->name . '][]';
		$relevant_post_types = $this->value;
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/settings/calendar-post-types.php';

	}//end display()

	// @Implements
	public function sanitize( $input ) { // @codingStandardsIgnoreLine

		return $input;

	}//end sanitize()

}//end class
