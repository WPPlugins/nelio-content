<?php
/**
 * The plugin uses several AJAX calls. This class implements user-related admin
 * AJAX callbacks.
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
 * This class implements user-related admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.5
 */
class Nelio_Content_User_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public function register_ajax_callbacks() {

		add_action( 'wp_ajax_nelio_content_get_user', array( $this, 'get_user' ) );
		add_action( 'wp_ajax_nelio_content_get_authors', array( $this, 'get_authors' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint returns the specified user.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $user The ID of the user we want to retrieve.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public function get_user() {

		$user_id = 0;
		if ( isset( $_REQUEST['user'] ) ) { // Input var okay.
			$user_id = absint( wp_unslash( $_REQUEST['user'] ) ); // Input var okay.
		} else {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Author ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		if ( 0 === $user_id ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid user ID.', 'error', 'nelio-content' ) );
		}//end if

		$user_helper = Nelio_Content_User_Helper::instance();
		$the_user = $user_helper->user_to_json( $user_id );
		if ( ! $the_user ) {
			header( 'HTTP/1.1 404 Not Found' );
			wp_send_json( sprintf( _x( 'Author %s not found.', 'error', 'nelio-content' ), $user_id ) );
		}//end if

		wp_send_json( $the_user );

	}//end get_user()

	/**
	 * This AJAX endpoint returns a list of 10 author users that match the query.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string  $term The name or e-mail of the author(s) we want to return.
	 *  * integer $page The current page.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	public function get_authors() {

		// Settings.
		$users_per_page = 10;
		add_filter( 'user_search_columns', array( $this, 'add_display_name_column_in_search' ) );

		// Prepare search arguments.
		$defaults = array(
			'page' => 1,
			'term' => '',
		);
		$params = wp_parse_args( $_REQUEST, $defaults ); // Input var okay.
		$params['page'] = intval( $params['page'] );

		// Search query.
		$args = array(
			'number'         => $users_per_page,
			'orderby'        => 'display_name',
			'paged'          => $params['page'],
			'who'            => 'authors',
			'search'         => '*' . $params['term'] . '*',
			'search_columns' => array( 'user_email', 'display_name' ),
		);

		$query = new WP_User_Query( $args );
		$wp_users = $query->get_results();

		$aux = Nelio_Content_User_Helper::instance();
		$authors = array();
		foreach ( $wp_users as $wp_user ) {
			array_push( $authors, $aux->user_to_json( $wp_user ) );
		}//end foreach

		// Decide if there are more users yet...
		$args['number'] = 1;
		$args['paged'] = $params['page'] * $users_per_page + 1;
		$query = new WP_User_Query( $args );
		$wp_users = $query->get_results();

		// Build result object, ready for pagination.
		$result = array(
			'items' => $authors,
			'more'  => count( $wp_users ) > 0,
		);

		wp_send_json( $result );

	}//end get_authors()

	/**
	 * Adds the `display_name` column in `WP_User_Query` searches.
	 *
	 * @param array $search_columns list of columns searched.
	 *
	 * @return array the list of columns searched, with `display_name` in it.
	 *
	 * @since 1.0.5
	 * @access public
	 */
	public function add_display_name_column_in_search( $search_columns ) {

		array_push( $search_columns, 'display_name' );
		return $search_columns;

	}//end add_display_name_column_in_search()

}//end class

