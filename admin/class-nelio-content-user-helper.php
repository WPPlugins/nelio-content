<?php
/**
 * This file contains a class with some user-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements user-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.5
 */
class Nelio_Content_User_Helper {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.5
	 * @access protected
	 * @var    Nelio_Content_Post_Helper
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.5' ); // @codingStandardsIgnoreLine

	}//end __clone()


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.5' ); // @codingStandardsIgnoreLine

	}//end __wakeup()


	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Author_Helper the single instance of this class.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * This function generates an ncselect2-ready object with the specified user.
	 *
	 * @param WP_User|integer $user The user we want to stringify or a user ID.
	 *
	 * @return boolean|array a ncselect2-ready object with the specified user.
	 *                       If the user couldn't be found, we return false.
	 *
	 * @since  1.0.5
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function user_to_json( $user ) {

		if ( is_int( $user ) ) {

			$users = get_users( array( 'include' => array( $user ) ) );

			if ( 0 === count( $users ) ) {
				return false;
			}//end if

			$user = $users[0];

		}//end if

		$data = $user->data;
		return array(
			'id'       => absint( $data->ID ),
			'email'    => $data->user_email,
			'name'     => $data->display_name,
			'photo'    => nc_get_avatar_url( $data->user_email, array( 'size' => 60, 'default' => 'blank' ) ),
			'editLink' => get_edit_user_link( $data->ID ),
			'role'     => nc_get_user_role( $user ),
		);

	}//end user_to_json()

}//end class

