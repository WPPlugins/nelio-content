<?php
/**
 * The plugin uses several AJAX calls. This class implements all
 * reference-related admin AJAX callbacks.
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
 * This class implements all reference-related admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Reference_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		// These operations are available to all users with access to the plugin.
		add_action( 'wp_ajax_nelio_content_get_post_references', array( $this, 'get_post_references' ) );
		add_action( 'wp_ajax_nelio_content_autoload_reference', array( $this, 'autoload_reference' ) );

		add_filter( 'post_type_link', array( $this, 'fix_reference_permalink' ), 10, 2 );
		add_filter( 'wp_link_query_args', array( $this, 'include_references_in_link_query' ) );
		add_filter( 'wp_link_query', array( $this, 'fix_info_field_in_link_query_results' ) );

		// The remaining operations are only available to authors or above.
		if ( ! nc_is_current_user( 'author' ) ) {
			return;
		}//end if

		add_action( 'wp_ajax_nelio_content_update_reference', array( $this, 'update_reference' ) );

		add_action( 'wp_ajax_nelio_content_add_post_reference', array( $this, 'add_post_reference' ) );
		add_action( 'wp_ajax_nelio_content_delete_post_reference', array( $this, 'delete_post_reference' ) );

		add_action( 'wp_ajax_nelio_content_suggest_post_reference', array( $this, 'suggest_post_reference' ) );
		add_action( 'wp_ajax_nelio_content_discard_post_reference', array( $this, 'discard_post_reference' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint updates the information of a given reference.
	 *
	 * As a response, it returns the following:
	 *
	 *  * `false` if the given reference didn't exist.
	 *  * A `JSON object` with the new values of the reference otherwise.
	 *
	 * Possible `$_REQUEST` params:
	 *
	 *  * integer $reference Required. The ID of the reference that will be updated.
	 *  * string  $title     Optional. The title of the reference.
	 *  * string  $url       Optional. The URL of the reference (it only works if the reference is broken).
	 *  * string  $author    Optional. The name of the author.
	 *  * string  $email     Optional. The email of the author.
	 *  * string  $twitter   Optional. The twitter username of the author (including `@`).
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.NPathComplexity )
	 */
	public function update_reference() {

		$reference_id = 0;
		if ( isset( $_REQUEST['reference'] ) ) { // Input var okay.
			$reference_id = absint( $_REQUEST['reference'] ); // Input var okay.
		}//end if

		if ( empty( $reference_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Reference ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$reference = nc_get_reference( $reference_id );
		if ( ! $reference ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( sprintf( _x( 'Reference %s not found.', 'error', 'nelio-content' ), $reference_id ) );
		}//end if

		if ( ! $reference->is_external() ) {
			wp_send_json( $reference->json_encode() );
		}//end if

		$url = '';
		if ( isset( $_REQUEST['url'] ) ) { // Input var okay.
			$url = trim( sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ) ); // Input var okay.
		}//end if

		if ( empty( $url ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'URL cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid URL.', 'error', 'nelio-content' ) );
		}//end if

		if ( 'broken' === $reference->get_status() ) {
			$reference->set_url( $url );
		}//end if

		if ( isset( $_REQUEST['title'] ) ) { // Input var okay.
			$title = trim( sanitize_text_field( wp_unslash( $_REQUEST['title'] ) ) ); // Input var okay.
			$reference->set_title( $title );
		}//end if

		if ( isset( $_REQUEST['author'] ) ) { // Input var okay.
			$author = trim( sanitize_text_field( wp_unslash( $_REQUEST['author'] ) ) ); // Input var okay.
			$reference->set_author_name( $author );
		}//end if

		if ( isset( $_REQUEST['email'] ) ) { // Input var okay.
			$email = trim( sanitize_email( wp_unslash( $_REQUEST['email'] ) ) ); // Input var okay.
			$reference->set_author_email( $email );
		}//end if

		if ( isset( $_REQUEST['twitter'] ) ) { // Input var okay.
			$twitter = trim( sanitize_text_field( wp_unslash( $_REQUEST['twitter'] ) ) ); // Input var okay.
			$reference->set_author_twitter( $twitter );
		}//end if

		wp_send_json( $reference->json_encode() );

	}//end update_reference()

	/**
	 * This AJAX endpoint returns the list of included and suggested references in a post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $post The ID of the post whose references we want to retrieve.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.NPathComplexity )
	 */
	public function get_post_references() {

		$result = array(
			'included'  => array(),
			'suggested' => array(),
		);

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( $_REQUEST['post'] ); // Input var okay.
		}//end if

		// Error control.
		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$post = get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( sprintf( _x( 'Post %s not found.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

		// Extract the URLs that are indeed included in the post content, as well as
		// the list of references that, apparently, are also included in the post.
		$urls = $this->extract_urls( $post->post_content );
		$reference_ids = nc_get_post_reference( $post_id, 'included' );

		$references = array();
		foreach ( $reference_ids as $id ) {
			$reference = new Nelio_Content_Reference( $id );
			$meta = nc_get_suggested_reference_meta( $id, $post_id );
			if ( ! empty( $meta ) ) {
				$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
			}//end if
			array_push( $references, $reference );
		}//end foreach

		// And make sure these references are really included in the post.
		foreach ( $references as $ref ) {

			if ( in_array( $ref->get_url(), $urls, true ) ) {

				$aux = $ref->json_encode();
				array_push( $result['included'], $aux );

			} else {

				nc_delete_post_reference( $post_id, $ref->ID );

			}//end if

		}//end foreach

		// Now let's get all the suggested references.
		$reference_ids = nc_get_post_reference( $post_id, 'suggested' );

		foreach ( $reference_ids as $id ) {
			$reference = new Nelio_Content_Reference( $id );
			$meta = nc_get_suggested_reference_meta( $id, $post_id );
			if ( ! empty( $meta ) ) {
				$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
			}//end if
			array_push( $result['suggested'], $reference->json_encode() );
		}//end foreach

		wp_send_json( $result );

	}//end get_post_references()

	/**
	 * This AJAX endpoint looks for the reference with a given URL (or creates a
	 * new one if none exists), and tries to autoload all its information.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $url The URL of the reference whose information we want to autoload and retrieve.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function autoload_reference() {

		$url = '';
		if ( isset( $_REQUEST['url'] ) ) { // Input var okay.
			$url = sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ); // Input var okay.
		}//end if

		if ( empty( $url ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'URL cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid URL.', 'error', 'nelio-content' ) );
		}//end if

		// If the reference already exists and its values have already been defined,
		// return those.
		$reference = nc_get_reference_by_url( $url );
		if ( ! $reference ) {
			$reference = new Nelio_Content_Reference();
			$reference->set_url( $url );
		}//end if

		// Autoload information.
		$reference->autoload();

		// Only send those values that are not empty.
		$result = $reference->json_encode();

		foreach ( $result as $key => $value ) {
			if ( empty( $value ) ) {
				unset( $result[ $key ] );
			}//end if
		}//end foreach

		wp_send_json( $result );

	}//end autoload_reference()

	/**
	 * This AJAX endpoint adds the given URL as an included reference in the given post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $post The ID of the post to which we want to add a reference.
	 *  * string  $url  The URL of the reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_post_reference() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( $_REQUEST['post'] ); // Input var okay.
		}//end if

		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$url = '';
		if ( isset( $_REQUEST['url'] ) ) { // Input var okay.
			$url = sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ); // Input var okay.
		}//end if

		if ( empty( $url ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'URL cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid URL.', 'error', 'nelio-content' ) );
		}//end if

		// Add the reference to the post and save it.
		$reference = nc_create_reference( $url );
		nc_add_post_reference( $post_id, $reference->ID );

		// Send the result.
		$result = $reference->json_encode();
		wp_send_json( $result );

	}//end add_post_reference()

	/**
	 * This AJAX endpoint removes the given reference from the given post, so that it
	 * is no longer present in the included list.
	 *
	 * Note, however, that this doesn't remove the URL from the post content.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $post      The ID of the post to which we want to add a
	 *                       reference.
	 *  * integer $reference The ID of the reference that should no longer be
	 *                       included in the post.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function delete_post_reference() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( $_REQUEST['post'] ); // Input var okay.
		}//end if

		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$reference_id = '';
		if ( isset( $_REQUEST['reference'] ) ) { // Input var okay.
			$reference_id = absint( $_REQUEST['reference'] ); // Input var okay.
		}//end if

		if ( empty( $reference_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Reference ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		// Add the reference to the post and save it.
		nc_delete_post_reference( $post_id, $reference_id );

		// Send the result.
		wp_send_json( true );

	}//end delete_post_reference()

	/**
	 * This AJAX endpoint adds a URL as a suggested reference in the given post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $post The ID of the post to which we want to add a reference.
	 *  * string  $url  The URL of the reference.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function suggest_post_reference() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( $_REQUEST['post'] ); // Input var okay.
		}//end if

		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$url = '';
		if ( isset( $_REQUEST['url'] ) ) { // Input var okay.
			$url = sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ); // Input var okay.
		}//end if

		if ( empty( $url ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'URL cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid URL.', 'error', 'nelio-content' ) );
		}//end if

		// Add the reference to the post and save it.
		$reference = nc_create_reference( $url );
		nc_suggest_post_reference( $post_id, $reference->ID, get_current_user_id() );

		if ( $reference->get_status() === 'pending' ) {
			$reference->autoload();
		}//end if

		// Send the result.
		$result = $reference->json_encode();
		wp_send_json( $result );

	}//end suggest_post_reference()

	/**
	 * This AJAX endpoint discards the suggested reference from the given post.
	 *
	 * Note, however, that this doesn't remove the URL from the post content.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $post      The ID of the post to which we want to add a
	 *                       reference.
	 *  * integer $reference The ID of the reference that should no longer be
	 *                       included in the post.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function discard_post_reference() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( $_REQUEST['post'] ); // Input var okay.
		}//end if

		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		$reference_id = '';
		if ( isset( $_REQUEST['reference'] ) ) { // Input var okay.
			$reference_id = absint( $_REQUEST['reference'] ); // Input var okay.
		}//end if

		if ( empty( $reference_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Reference ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		// Add the reference to the post and save it.
		nc_discard_post_reference( $post_id, $reference_id );

		// Send the result.
		wp_send_json( true );

	}//end discard_post_reference()

	/**
	 * This function, used in `post_type_link` filter, replaces custom post type
	 * "Nelio_Content_Reference" internal WordPress link with the value of the
	 * appropriate meta field (see the method `get_url` in said class).
	 *
	 * @param string  $post_link The post's permalink.
	 * @param WP_Post $post      The post in question.
	 *
	 * @return string the actual link of a Nelio Content Reference (instead of
	 *                WordPress' CPT link).
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function fix_reference_permalink( $post_link, $post ) {

		if ( 'nc_reference' !== $post->post_type ) {
			return $post_link;
		}//end if

		$reference = new Nelio_Content_Reference( $post );
		$post_link = $reference->get_url();

		return $post_link;

	}//end fix_reference_permalink()

	/**
	 * This function modifies the query so that Nelio Content references are also queried.
	 *
	 * Thanks to it, the UI for adding links in a post/page includes the references in
	 * our system.
	 *
	 * @param array $query An array of WP_Query arguments.
	 *
	 * @return array A new array of WP_Query args that includes the options for
	 *               returning Nelio Content references.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function include_references_in_link_query( $query ) {

		if ( ! isset( $query['post_type'] ) ) {
			$query['post_type'] = array();
		}//end if
		array_push( $query['post_type'], 'nc_reference' );

		if ( ! isset( $query['post_status'] ) ) {
			$query['post_status'] = array();
		}//end if

		if ( ! is_array( $query['post_status'] ) ) {
			$query['post_status'] = array( $query['post_status'] );
		}//end if

		array_push( $query['post_status'], 'nc_improvable' );
		array_push( $query['post_status'], 'nc_complete' );

		return $query;

	}//end include_references_in_link_query()

	/**
	 * This function tags as "Reference" the results that, apparently, are references.
	 *
	 * @param array $results An associative array of query results.
	 *
	 * @return array the given result set, but with (all) reference tuples (if
	 *               any) tagged as "Reference".
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function fix_info_field_in_link_query_results( $results ) {

		$maybe_ref_urls = array();

		// Look for all the tuples whose `info` attribute is empty.
		foreach ( $results as &$res ) {

			if ( empty( $res['info'] ) ) {
				array_push( $maybe_ref_urls, $res['permalink'] );
			}//end if

		}//end foreach

		if ( count( $maybe_ref_urls ) === 0 ) {
			return $results;
		}//end if

		// Let's check which of those are actually references.
		global $wpdb;
		$aux = array();
		foreach ( $maybe_ref_urls as $url ) {
			array_push( $aux, $wpdb->prepare( '%s', $url ) );
		}//end if
		$maybe_ref_urls = implode( ',', $aux );

		// @codingStandardsIgnoreStart
		$reference_urls = $wpdb->get_col(
			"SELECT meta_value " .
			"FROM $wpdb->postmeta " .
			"WHERE meta_key = '_nc_url' AND " .
			"meta_value IN ( " . $maybe_ref_urls . " )"
		);
		// @codingStandardsIgnoreEnd

		// The tuples whose permalink is a reference URL should be tagged as "Reference".
		foreach ( $results as &$res ) {

			if ( in_array( $res['permalink'], $reference_urls, true ) ) {
				$res['info'] = _x( 'Reference', 'text', 'nelio-content' );
			}//end if

		}//end foreach

		return $results;

	}//end fix_info_field_in_link_query_results()

	/**
	 * This helper function extracts all the URLs (roughly, any string that
	 * starts with http) from the given content.
	 *
	 * @param string $content Presumably, a post content. It can be any text, though.
	 *
	 * @return array A list with all the URLs found in `$content`.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function extract_urls( $content ) {

		// Extract all the URLs.
		preg_match_all(
			'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
			$content,
			$matches
		);

		if ( count( $matches ) > 0 ) {
			return $matches[0];
		} else {
			return array();
		}//end if

	}//end extract_urls()

}//end class

