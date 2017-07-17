<?php
/**
 * The plugin uses several AJAX calls. This class implements analytics-related
 * admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class implements analytics-related admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 *
 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Analytics_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		// These operations are available to all users with access to the plugin.
		add_action( 'wp_ajax_nelio_content_get_top_posts', array( $this, 'get_top_posts' ) );
		add_action( 'wp_ajax_nelio_content_get_ga_token', array( $this, 'get_ga_token' ) );
		add_action( 'wp_ajax_nelio_content_get_ga_views', array( $this, 'get_ga_views' ) );
		add_action( 'wp_ajax_nelio_content_update_analytics', array( $this, 'update_analytics' ) );

		add_action( 'wp_ajax_nelio_content_enable_analytics', array( $this, 'enable_analytics' ) );
		add_action( 'wp_ajax_nelio_content_update_analytics_last_global_update', array( $this, 'update_analytics_last_global_update' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint enables the analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function enable_analytics() {

		$settings = Nelio_Content_Settings::instance();
		$values = get_option( $settings->get_name(), array() );
		$values['use_analytics'] = true;
		update_option( $settings->get_name(), $values );

		wp_send_json( 'ok' );

	}//end enable_analytics()

	/**
	 * This AJAX endpoint updates the last global update for analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_analytics_last_global_update() {

		$value = time();
		update_option( 'nc_analytics_last_global_update', $value );
		wp_send_json( $value );

	}//end update_analytics_last_global_update()

	/**
	 * This AJAX endpoint returns the top posts according to a given metric.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * int    $author    The author id to whom the posts belong to.
	 *  * string $post_type The type of posts to retrieve.
	 *  * string $category  The category of posts to retrieve.
	 *  * string $metric    The type of metric to rank for (engagement|pageviews).
	 *  * string $start     The first day YYYY-MM-DD in which posts are retrieved, inclusive.
	 *  * string $end       The last day YYYY-MM-DD in which posts are retrieved, inclusive.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_top_posts() {

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$post_helper = Nelio_Content_Post_Helper::instance();
		$analytics   = Nelio_Content_Analytics_Helper::instance();

		// Get the author id.
		$author_id = 0;
		if ( nc_is_current_user( 'author', 'exactly' ) ) {
			$current_user = wp_get_current_user();
			$author_id = $current_user->ID;
		} else {
			if ( isset( $_REQUEST['author'] ) ) { // Input var okay.
				$author_id = sanitize_text_field( wp_unslash( $_REQUEST['author'] ) ); // Input var okay.
			}//end if
		}//end if

		// Get the time interval.
		$first_day = false;
		if ( isset( $_REQUEST['from'] ) ) { // Input var okay.
			$first_day = sanitize_text_field( wp_unslash( $_REQUEST['from'] ) ); // Input var okay.
		}//end if

		$last_day = false;
		if ( isset( $_REQUEST['to'] ) ) { // Input var okay.
			$last_day = sanitize_text_field( wp_unslash( $_REQUEST['to'] ) ); // Input var okay.
			$last_day .= ' 23:59:59';
		}//end if

		$ranking_field = '_nc_engagement_total';
		if ( isset( $_REQUEST['metric'] ) ) { // Input var okay.
			$metric = sanitize_text_field( wp_unslash( $_REQUEST['metric'] ) ); // Input var okay.
			if ( 'pageviews' === $metric ) {
				$ranking_field = '_nc_pageviews_total';
			}
		}//end if

		$category = false;
		if ( isset( $_REQUEST['category'] ) ) { // Input var okay.
			$category = sanitize_text_field( wp_unslash( $_REQUEST['category'] ) ); // Input var okay.
		}//end if

		if ( isset( $_REQUEST['postType'] ) ) { // Input var okay.
			$enabled_post_types = array( sanitize_text_field( wp_unslash( $_REQUEST['postType'] ) ) ); // Input var okay.
		}//end if

		$args = array(
			'posts_per_page' => 50,
			'author'         => $author_id,
			'meta_key'       => $ranking_field,
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
			'date_query'     => array(
				'after'     => $first_day,
				'before'    => $last_day,
				'inclusive' => true,
			),
		);

		if ( $category ) {
			$args['category__in'] = $category;
		}//end if

		$posts = $analytics->get_posts( $args );
		$ids = array();
		foreach ( $posts as $post ) {
			array_push( $ids, $post['id'] );
		}//end foreach

		if ( 50 > count( $posts ) ) {
			$args = array(
				'posts_per_page' => 50 - count( $posts ),
				'author'         => $author_id,
				'post_type'      => $enabled_post_types,
				'post__not_in'   => $ids,
				'date_query'     => array(
					'after'     => $first_day,
					'before'    => $last_day,
					'inclusive' => true,
				),
			);

			if ( $category ) {
				$args['category__in'] = $category;
			}//end if

			$posts = array_merge( $posts, $analytics->get_posts( $args ) );
		}//end if

		// Build result object, ready for pagination.
		$result = array(
			'items' => $posts,
			'more'  => $params['page'] < $query->max_num_pages,
		);

		wp_send_json( $result );

	}//end get_top_posts()


	/**
	 * This AJAX endpoint updates the analytics values for a given post.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $post The post id to update its statistics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_analytics() {
		if ( ! isset( $_REQUEST['post'] ) ) {
			return;
		}//end if

		$post_id = absint( wp_unslash( $_REQUEST['post'] ) ); // Input var okay.

		$analytics = Nelio_Content_Analytics_Helper::instance();
		if ( $analytics->needs_to_be_updated( $post_id ) ) {
			$analytics->update_statistics( $post_id );
		}//end if

		wp_send_json( 'ok' );
	}//end update_analytics()

	/**
	 * This AJAX endpoint returns the access token of Google Analytics.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * string $code The code to exchange for an access token.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_ga_token() {
		if ( ! isset( $_REQUEST['code'] ) ) {
			return;
		}

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$token = $analytics->get_ga_token( $_REQUEST['code'] );

		if ( empty( $token ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Unable to retrieve Google Analytics token.', 'error', 'nelio-content' ) );
		}//end if

		$result = array(
			'token' => $token,
		);

		wp_send_json( $result );
	}//end get_ga_token()

	/**
	 * This AJAX endpoint returns the views of Google Analytics.
	 *
	 * Expected `$_REQUEST` params:
	 *
	 *  * None.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_ga_views() {

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$views = $analytics->get_ga_views();

		if ( empty( $views ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Unable to retrieve Google Analytics views.', 'error', 'nelio-content' ) );
		}//end if

		wp_send_json( $views );
	}//end get_ga_views()

}//end class
