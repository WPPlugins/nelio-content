<?php
/**
 * The plugin uses several AJAX calls. This class implements post-related admin
 * AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements post-related admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Post_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		// These operations are available to all users with access to the plugin.
		add_action( 'wp_ajax_nelio_content_get_post', array( $this, 'get_post' ) );
		add_action( 'wp_ajax_nelio_content_get_posts', array( $this, 'get_posts' ) );
		add_action( 'wp_ajax_nelio_content_get_post_ids', array( $this, 'get_post_ids' ) );
		add_action( 'wp_ajax_nelio_content_get_post_permalinks', array( $this, 'get_post_permalinks' ) );
		add_action( 'wp_ajax_nelio_content_get_monthly_posts', array( $this, 'get_monthly_posts' ) );

		add_action( 'wp_ajax_nelio_content_get_url_meta_data', array( $this, 'get_url_meta_data' ) );

		// TODO. Make sure permissions of these operations are honored.
		add_action( 'wp_ajax_nelio_content_create_post_in_calendar', array( $this, 'create_post_in_calendar' ) );
		add_action( 'wp_ajax_nelio_content_update_post_in_calendar', array( $this, 'update_post_in_calendar' ) );

		add_action( 'wp_ajax_nelio_content_reschedule_post', array( $this, 'reschedule_post' ) );
		add_action( 'wp_ajax_nelio_content_trash_post', array( $this, 'trash_post' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint creates a new post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer  $author         the ID of the author.
	 *  * string   $title          the post's title.
	 *  * string   $localDatetime  post's date and time. Format: YYYY-MM-DD HH:mm
	 *  * string   $reference      Optional. A URL with a reference.
	 *  * string   $comment        Optional. An initial comment for the author.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.NPathComplexity, PHPMD.ExcessiveMethodLength )
	 */
	public function create_post_in_calendar() {

		$author_id = 0;
		if ( isset( $_REQUEST['author'] ) ) { // Input var okay.
			$author_id = absint( wp_unslash( $_REQUEST['author'] ) ); // Input var okay.
		}//end if

		$title = '';
		if ( isset( $_REQUEST['title'] ) ) { // Input var okay.
			$title = trim( sanitize_text_field( wp_unslash( $_REQUEST['title'] ) ) ); // Input var okay.
		}//end if

		$date = '';
		if ( isset( $_REQUEST['localDatetime'] ) ) { // Input var okay.
			$date = trim( sanitize_text_field( wp_unslash( $_REQUEST['localDatetime'] ) ) ); // Input var okay.
		}//end if

		$post_type = false;
		if ( isset( $_REQUEST['postType'] ) ) { // Input var okay.
			$post_type = trim( sanitize_text_field( wp_unslash( $_REQUEST['postType'] ) ) ); // Input var okay.
		}//end if


		$settings = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );
		if ( ! $post_type || ! in_array( $post_type, $enabled_post_types ) ) {
			$post_type = $enabled_post_types[0];
		}//end if

		if ( 'page' === $post_type && ! current_user_can( 'edit_pages' ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'You\'re not allowed to create pages.', 'error', 'nelio-content' ) );
		}//end if

		// Error control.
		if ( empty( $title ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Title cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( empty( $date ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid date.', 'error', 'nelio-content' ) );
		}//end if

		// Add a reference, if there's one.
		$reference = '';
		if ( isset( $_REQUEST['reference'] ) ) { // Input var okay.
			$reference = trim( sanitize_text_field( wp_unslash( $_REQUEST['reference'] ) ) ); // Input var okay.
		}//end if

		if ( ! empty( $reference ) ) {

			if ( false === filter_var( $reference, FILTER_VALIDATE_URL ) ) {
				header( 'HTTP/1.1 400 Bad Request' );
				wp_send_json( _x( 'Reference has to be a valid URL.', 'error', 'nelio-content' ) );
			}//end if

		}//end if

		/**
		 * Modifies the title that will be used in the given post.
		 *
		 * This filter is called right before the post is saved in the database.
		 *
		 * @param string $title the new post title.
		 *
		 * @since 1.0.0
		 */
		$title = apply_filters( 'nelio_content_calendar_create_post_title', $title );

		// Create new post.
		$post_data = array(
			'post_title'    => $title,
			'post_author'   => $author_id,
			'post_date'     => $date . ':00',
			'post_date_gmt' => get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $date . ':00' ) ) ),
			'post_type'     => $post_type,
		);

		if ( $settings->get( 'use_custom_post_statuses' ) ) {
			$post_data['post_status'] = 'idea';
		}//end if

		$post_id = wp_insert_post( $post_data );
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Post could not be created.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! empty( $reference ) ) {
			$ref = nc_create_reference( $reference );
			nc_suggest_post_reference( $post_id, $ref->ID, get_current_user_id() );
			wp_update_post( $ref->ID, array( 'post_status' => 'nc_pending' ) );
		}//end if

		// Add the first editorial comment, if there's one.
		if ( nc_is_subscribed_to( 'team-plan' ) ) {

			$comment = '';
			if ( isset( $_REQUEST['comment'] ) ) { // Input var okay.
				$comment = trim( sanitize_text_field( wp_unslash( $_REQUEST['comment'] ) ) ); // Input var okay.
			}//end if

			if ( '' !== $comment ) {

				$data = array(
					'method'    => 'POST',
					'timeout'   => 30,
					'sslverify' => ! $settings->get( 'uses_proxy' ),
					'headers'   => array(
						'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
						'accept'       => 'application/json',
						'content-type' => 'application/json',
					),
					'body' => wp_json_encode( array(
						'authorId' => get_current_user_id(),
						'postId'   => $post_id,
						'postType' => $post_type,
						'comment'  => $comment,
					) ),
				);

				$url = sprintf(
					nc_get_api_url( '/site/%s/comment', 'wp' ),
					nc_get_site_id()
				);
				$response = wp_remote_request( $url, $data );

				// If the response is an error, leave.
				if ( ! nc_is_response_valid( $response ) ) {
					wp_delete_post( $post_id );
					nc_maybe_send_error_json( $response );
				}//end if

			}//end if

		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		$aux = $post_helper->post_to_json( $post_id );
		if ( empty( $aux ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Post was successfully created, but could not be retrieved.', 'error', 'nelio-content' ) );
		} else {
			wp_send_json( $aux );
		}//end if

	}//end create_post_in_calendar()

	/**
	 * This AJAX endpoint updates the information of a post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer  $post           the ID of the post whose publication date has to be updated.
	 *  * integer  $author         the ID of the author.
	 *  * string   $title          the post's title.
	 *  * string   $localDatetime  post's date and time. Format: YYYY-MM-DD HH:mm
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.NPathComplexity )
	 */
	public function update_post_in_calendar() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( wp_unslash( $_REQUEST['post'] ) ); // Input var okay.
		}//end if

		$author_id = 0;
		if ( isset( $_REQUEST['author'] ) ) { // Input var okay.
			$author_id = absint( wp_unslash( $_REQUEST['author'] ) ); // Input var okay.
		}//end if

		$title = '';
		if ( isset( $_REQUEST['title'] ) ) { // Input var okay.
			$title = trim( sanitize_text_field( wp_unslash( $_REQUEST['title'] ) ) ); // Input var okay.
		}//end if

		$date = '';
		if ( isset( $_REQUEST['localDatetime'] ) ) { // Input var okay.
			$date = trim( sanitize_text_field( wp_unslash( $_REQUEST['localDatetime'] ) ) ); // Input var okay.
		}//end if

		// Error control.
		if ( empty( $title ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Title cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( empty( $date ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid date.', 'error', 'nelio-content' ) );
		}//end if

		// Or update old post.
		$the_post = get_post( $post_id );
		if ( ! $the_post || is_wp_error( $the_post ) ) {
			header( 'HTTP/1.1 404 Not Found' );
			wp_send_json( sprintf( _x( 'Post %s not found.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

		if ( 'page' === $the_post->post_type && ! current_user_can( 'edit_pages' ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'You\'re not allowed to edit pages.', 'error', 'nelio-content' ) );
		}//end if

		/**
		 * Modifies the title that will be saved in the given post.
		 *
		 * This filter is called right before the post is updated and saved in the database.
		 *
		 * @param string $title   the new post title.
		 * @param int    $post_id the ID of the post we're updating.
		 *
		 * @since 1.0.0
		 */
		$the_post->post_title = apply_filters( 'nelio_content_calendar_update_post_title', $title, $post_id );

		$the_post->post_author = $author_id;
		$the_post->post_date = $date . ':00';
		$the_post->post_date_gmt = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $the_post->post_date ) ) );

		$aux = wp_update_post( $the_post );
		if ( is_wp_error( $aux ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( sprintf( _x( 'Post %s could not be updated.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		$aux = $post_helper->post_to_json( $post_id );
		if ( empty( $aux ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Unable to retrieve post.', 'error', 'nelio-content' ) );
		} else {
			wp_send_json( $aux );
		}//end if

	}//end update_post_in_calendar()

	/**
	 * This AJAX endpoint reschedules a given post to the new date.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer  $post  The ID of the post whose publication date has to be updated.
	 *  * string   $date  A string (YYYY-MM-DD) with the new date in which the post
	 *                    has to be scheduled.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function reschedule_post() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( wp_unslash( $_REQUEST['post'] ) ); // Input var okay.
		}//end if

		$date = false;
		if ( isset( $_REQUEST['date'] ) ) { // Input var okay.
			$date = sanitize_text_field( wp_unslash( $_REQUEST['date'] ) ); // Input var okay.
		}//end if

		if ( empty( $post_id ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		if ( empty( $date ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Date is missing.', 'error', 'nelio-content' ) );
		}//end if

		$post = get_post( $post_id ); // @codingStandardsIgnoreLine
		if ( is_wp_error( $post ) || empty( $post ) ) {
			header( 'HTTP/1.1 404 Not Found' );
			wp_send_json( sprintf( _x( 'Post %s not found.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

		$post->post_date = $date . date( ' H:i:s', strtotime( $post->post_date ) );
		$post->post_date_gmt = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $post->post_date ) ) );
		wp_update_post( $post );

		$post_helper = Nelio_Content_Post_Helper::instance();
		$aux = $post_helper->post_to_json( $post->ID );
		wp_send_json( $aux );

	}//end reschedule_post()

	/**
	 * This AJAX endpoint trashes the given post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer  $post  The ID of the post we want to trash.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function trash_post() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( wp_unslash( $_REQUEST['post'] ) ); // Input var okay.
		} else {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		if ( 0 === $post_id ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid post ID.', 'error', 'nelio-content' ) );
		}//end if

		if ( wp_trash_post( $post_id ) ) {
			wp_send_json( $post_id );
		} else {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( sprintf( _x( 'Post %s could not be deleted.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

	}//end trash_post()

	/**
	 * This AJAX endpoint returns the specified post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $post The ID of the post we want to retrieve.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_post() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) ) { // Input var okay.
			$post_id = absint( wp_unslash( $_REQUEST['post'] ) ); // Input var okay.
		} else {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Post ID is missing.', 'error', 'nelio-content' ) );
		}//end if

		if ( 0 === $post_id ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid post ID.', 'error', 'nelio-content' ) );
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		$the_post = $post_helper->post_to_json( $post_id );
		if ( ! $the_post ) {
			header( 'HTTP/1.1 404 Not Found' );
			wp_send_json( sprintf( _x( 'Post %s not found.', 'error', 'nelio-content' ), $post_id ) );
		}//end if

		wp_send_json( $the_post );

	}//end get_post()

	/**
	 * This AJAX endpoint returns a list of 10 posts that match the query.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string  $term  A string that has to appear in the titles of all posts
	 *                   included in the result.
	 *  * integer $page  The current page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_posts() {

		// Load some settings.
		$settings = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );
		$post_helper = Nelio_Content_Post_Helper::instance();

		// Prepare search arguments.
		$defaults = array(
			'page' => 1,
			'term' => '',
		);
		$params = wp_parse_args( $_REQUEST, $defaults ); // Input var okay.

		$args = array(
			'post_title__like' => $params['term'],
			'paged'            => $params['page'],
			'posts_per_page'   => 50,
			'orderby'          => 'date',
			'order'            => 'desc',
			'post_type'        => $enabled_post_types,
		);

		if ( isset( $_REQUEST['status'] ) ) { // Input var okay.
			$args['post_status'] = sanitize_text_field( wp_unslash( $_REQUEST['status'] ) ); // Input var okay.
		}//end if

		// If the user is using "id:x", we need to look for the post whose post_id is x.
		$posts = array();
		if ( strpos( $params['term'], 'id:' ) !== false ) {

			preg_match( '/id:([0-9]+)/', $params['term'], $matches );

			if ( count( $matches ) >= 1 ) {
				$post_id = $matches[1];
			} else {
				$post_id = false;
			}

			$aux = $post_helper->post_to_json( $post_id );
			if ( ! empty( $aux ) && in_array( $aux['post_type'], $enabled_post_types, true ) ) {
				array_push( $posts, $aux );
				$args['post__not_in'] = array( $post_id );
			}//end if

		}//end if

		// Get list of results based on the search string (whatever it is)
		// and format them as expected by a ncselect2 component.
		add_filter( 'posts_where', array( $this, 'add_title_filter_to_wp_query' ), 10, 2 );
		$query = new WP_Query( $args );
		remove_filter( 'posts_where', array( $this, 'add_title_filter_to_wp_query' ), 10, 2 );

		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json();
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if

		}//end while

		wp_reset_postdata();

		// Build result object, ready for pagination.
		$result = array(
			'items' => $posts,
			'more'  => $params['page'] < $query->max_num_pages,
		);

		wp_send_json( $result );

	}//end get_posts()

	/**
	 * This AJAX endpoint returns a list of post ids that match the query.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * integer $period  The period to filter ('month', 'year', 'all').
	 *  * integer $page    The current page.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_post_ids() {

		// Load some settings.
		$settings = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		// Prepare search arguments.
		$defaults = array(
			'page' => 1,
		);
		$params = wp_parse_args( $_REQUEST, $defaults ); // Input var okay.

		$args = array(
			'paged'            => $params['page'],
			'post_status'      => 'publish',
			'posts_per_page'   => 10,
			'orderby'          => 'date',
			'order'            => 'desc',
			'post_type'        => $enabled_post_types,
		);

		if ( isset( $_REQUEST['period'] ) ) { // Input var okay.
			$period = sanitize_text_field( wp_unslash( $_REQUEST['period'] ) ); // Input var okay.

			if ( 'month' === $period || 'year' === $period ) {
				$args['date_query'] = array(
					array(
						'column' => 'post_date_gmt',
						'after'  => '1 ' . $period . ' ago',
					),
				);
			}//end if

		}//end if

		$posts = array();

		// Get list of results and return just the ids.
		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {

			$query->the_post();
			array_push( $posts, get_the_ID() );

		}//end while

		wp_reset_postdata();

		// Build result object, ready for pagination.
		$result = array(
			'items' => $posts,
			'more'  => $params['page'] < $query->max_num_pages,
			'found' => $query->found_posts,
		);

		wp_send_json( $result );

	}//end get_post_ids()

	/**
	 * This AJAX endpoint returns all the posts that were published / are scheduled
	 * within the given time interval.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $start The first day YYYY-MM-DD in which posts are retrieved, inclusive.
	 *  * string $end   The last day YYYY-MM-DD in which posts are retrieved, inclusive.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.NPathComplexity )
	 */
	public function get_monthly_posts() {

		global $post;

		$result = array();

		// Get the time interval.
		$first_day = false;
		if ( isset( $_REQUEST['start'] ) ) { // Input var okay.
			$first_day = sanitize_text_field( wp_unslash( $_REQUEST['start'] ) ); // Input var okay.
		}//end if

		$last_day = false;
		if ( isset( $_REQUEST['end'] ) ) { // Input var okay.
			$last_day = sanitize_text_field( wp_unslash( $_REQUEST['end'] ) ); // Input var okay.
		}//end if

		// If time interval is not properly set, leave.
		if ( ! $first_day || ! $last_day ) {
			wp_send_json( $result );
		}//end if

		// Load some settings.
		$settings = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$args = array(
			'date_query' => array(
				'after'     => $first_day,
				'before'    => $last_day,
				'inclusive' => true,
			),
			'posts_per_page' => -1, // @codingStandardsIgnoreLine
			'orderby'        => 'date',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
		);

		$query = new WP_Query( $args );
		$post_helper = Nelio_Content_Post_Helper::instance();

		while ( $query->have_posts() ) {
			$query->the_post();

			if ( '0000-00-00 00:00:00' === $post->post_date_gmt ) {
				continue;
			}//end if

			$the_categories = get_the_category();
			$categories = array();
			foreach ( $the_categories as $cat ) {

				$root_cat = $post_helper->find_root_category( $cat->term_id );
				if ( $root_cat && ! in_array( $root_cat->term_id, $categories, true ) ) {
					array_push( $categories, $root_cat->term_id );
				}//end if

			}//end foreach

			$aux = $post_helper->post_to_json();
			if ( ! empty( $aux ) ) {
				array_push( $result, $aux );
			}//end if

		}//end while

		wp_reset_postdata();

		wp_send_json( $result );

	}//end get_monthly_posts()

	/**
	 * A filter to search posts based on their title.
	 *
	 * This function modifies the posts query so that we can search posts based
	 * on a term that should appear in their titles.
	 *
	 * @param string   $where    The where clause, as it's originally defined.
	 * @param WP_Query $wp_query The $wp_query object that contains the params
	 *                           used to build the where clause.
	 *
	 * @return string PHPDOC.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_title_filter_to_wp_query( $where, &$wp_query ) {

		global $wpdb;

		if ( $search_term = $wp_query->get( 'post_title__like' ) ) {
			$search_term = $wpdb->esc_like( $search_term );
			$search_term = ' \'%' . $search_term . '%\'';
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE ' . $search_term;
		}//end if

		return $where;

	}//end add_title_filter_to_wp_query()

	/**
	 * This AJAX endpoint returns meta data about the given URL.
	 *
	 * This meta data includes the page's title, author, excerpt, permalink...
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string  $url  The URL whose meta data has to be obtained.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_url_meta_data() {

		$link_meta = new Nelio_Content_Link_Meta();

		if ( isset( $_REQUEST['url'] ) ) { // Input var ok.
			$url = sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ); // Input var ok.
		} else {
			$url = '';
		}//end if

		wp_send_json( $link_meta->get_url_meta_data( $url ) );

	}//end get_url_meta_data()

	/**
	 * This AJAX endpoint returns a list of 10 posts that match the query.
	 *
	 * @since  1.2.7
	 * @access public
	 */
	public function get_post_permalinks() {

		$post_ids = array();
		if ( isset( $_REQUEST['posts'] ) ) { // Input var ok.
			$post_ids = array_map( 'absint', wp_unslash( $_REQUEST['posts'] ) ); // Input var ok.
		}//end if

		if ( ! count( $post_ids ) ) {
			wp_send_json( array() );
		}//end if

		$result = array();
		foreach ( $post_ids as $post_id ) {
			$permalink = get_permalink( $post_id );
			if ( $permalink ) {
				$result[ $post_id ] = $permalink;
			}//end if
		}//end foreach

		wp_send_json( $result );

	}//end get_post_permalinks()

}//end class
