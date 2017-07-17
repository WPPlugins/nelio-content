<?php
/**
 * This file contains a class with some analytics-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements analytics-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */
class Nelio_Content_Analytics_Helper {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    Nelio_Content_Analytics_Helper
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Analytics_Helper the single instance of this class.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Create or destroy WordPress cron tasks to update analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function manage_cron_tasks() {
		$settings = Nelio_Content_Settings::instance();

		if ( $settings->get( 'use_analytics' ) ) {
			$this->enable_analytics_cron_tasks();
		} else {
			$this->disable_analytics_cron_tasks();
		}//end if

	}//end manage_cron_tasks()

	/**
	 * Enable WP cron tasks for analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function enable_analytics_cron_tasks() {

		$time = time();

		add_action( 'nc_analytics_today_cron_hook', array( $this, 'update_today_posts' ) );
		if ( ! wp_next_scheduled( 'nc_analytics_today_cron_hook' ) ) {
			wp_schedule_event( $time, 'nc_four_hours', 'nc_analytics_today_cron_hook' );
		}//end if

		add_action( 'nc_analytics_month_cron_hook', array( $this, 'update_month_posts' ) );
		if ( ! wp_next_scheduled( 'nc_analytics_month_cron_hook' ) ) {
			wp_schedule_event( $time + 3600, 'nc_four_hours', 'nc_analytics_month_cron_hook' );
		}//end if

		add_action( 'nc_analytics_other_cron_hook', array( $this, 'update_other_posts' ) );
		if ( ! wp_next_scheduled( 'nc_analytics_other_cron_hook' ) ) {
			wp_schedule_event( $time + 7200, 'nc_four_hours', 'nc_analytics_other_cron_hook' );
		}//end if

		add_action( 'nc_analytics_top_cron_hook', array( $this, 'update_top_posts' ) );
		if ( ! wp_next_scheduled( 'nc_analytics_top_cron_hook' ) ) {
			wp_schedule_event( $time + 10800, 'nc_four_hours', 'nc_analytics_top_cron_hook' );
		}//end if

	}//end enable_analytics_cron_tasks()

	/**
	 * Disable WP cron tasks for analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function disable_analytics_cron_tasks() {

		$timestamp = wp_next_scheduled( 'nc_analytics_today_cron_hook' );
		wp_unschedule_event( $timestamp, 'nc_analytics_today_cron_hook' );

		$timestamp = wp_next_scheduled( 'nc_analytics_month_cron_hook' );
		wp_unschedule_event( $timestamp, 'nc_analytics_month_cron_hook' );

		$timestamp = wp_next_scheduled( 'nc_analytics_other_cron_hook' );
		wp_unschedule_event( $timestamp, 'nc_analytics_other_cron_hook' );

		$timestamp = wp_next_scheduled( 'nc_analytics_top_cron_hook' );
		wp_unschedule_event( $timestamp, 'nc_analytics_top_cron_hook' );

	}//end disable_analytics_cron_tasks()

	/**
	 * Update analytics of all posts published today.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_today_posts() {

		// Let's get the posts published today.
		$today = getdate();
		$args = array(
			'posts_per_page' => -1,
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			if ( $this->needs_to_be_updated( $post_id ) ) {
				$this->update_statistics( $post_id );
			}//end if

		}//end foreach

	}//end update_today_posts()

	/**
	 * Update analytics of 10 random posts published this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_month_posts() {

		// Let's get the posts to update.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			if ( $this->needs_to_be_updated( $post_id ) ) {
				$this->update_statistics( $post_id );
			}//end if

		}//end foreach

	}//end update_month_posts()

	/**
	 * Update analytics of 10 random posts published before this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_other_posts() {

		// Let's get 10 posts before this month.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			if ( $this->needs_to_be_updated( $post_id ) ) {
				$this->update_statistics( $post_id );
			}//end if

		}//end foreach

	}//end update_other_posts()

	/**
	 * Update analytics of top posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_top_posts() {

		// Let's get 5 top posts (according to engagement)
		$args = array(
			'posts_per_page' => 5,
			'meta_key'       => '_nc_engagement_total',
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);

		$posts = $this->get_posts( $args );
		$ids = array();
		foreach ( $posts as $post ) {
			array_push( $ids, $post['id'] );
		}//end foreach

		// Let's get 5 top posts (according to pageviews)
		$args = array(
			'posts_per_page' => 5,
			'post__not_in'   => $ids,
			'meta_key'       => '_nc_pageviews_total',
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);

		$posts_to_update = array_merge( $posts, $this->get_posts( $args ) );

		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			if ( $this->needs_to_be_updated( $post_id ) ) {
				$this->update_statistics( $post_id );
			}//end if

		}//end foreach

	}//end update_top_posts()

	/**
	 * Helper function that, given a certain post ID, updates its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be updated.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_statistics( $post_id ) {

		$url = get_permalink( $post_id );
		$publication_date = get_the_date( 'Y-m-d', $post_id );

		// Compute engagement.
		$engagement = array(
			'facebook'   => $this->get_facebook_count( $url ),
			'pinterest'  => $this->get_pinterest_count( $url ),
			'linkedin'   => $this->get_linkedin_count( $url ),
			'googleplus' => $this->get_googleplus_count( $url ),
			'comments'   => intval( wp_count_comments( $post_id )->approved ),
		);

		$this->save_engagement( $post_id, $engagement );

		// Compute pageviews.
		$settings = Nelio_Content_Settings::instance();
		$ga_view = $settings->get( 'google_analytics_view' );
		if ( ! empty( $ga_view ) ) {
			$pageviews = $this->get_ga_data( $ga_view, $post_id, $url, $publication_date );
			$this->save_pageviews( $ga_view, $post_id, $pageviews );
		}//end if

		// Refresh last update.
		update_post_meta( $post_id, '_nc_last_update', time() );

	}//end update_statistics()

	/**
	 * Helper function that, given a certain post ID, retrieves its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be recovered.
	 *
	 * @return array the statistics of the given post.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_post_stats( $post_id ) {

		// LAST UPDATE.
		$last_update = get_post_meta( $post_id, '_nc_last_update', true );
		if ( empty( $last_update ) ) {
			$last_update = 0;
		}//end if

		// ENGAGEMENT.
		$engagement = array();

		$engagement_metrics = array( 'total', 'facebook', 'linkedin', 'pinterest', 'googleplus' );
		foreach ( $engagement_metrics as $metric ) {
			$value = get_post_meta( $post_id, '_nc_engagement_' . $metric, true );
			if ( '' === $value ) {
				$value = -1;
			}//end if
			$engagement[ $metric ] = intval( $value );
			$engagement[ $metric . 'Human' ] = $this->human_number( $engagement[ $metric ] );
		}//end foreach
		$engagement['comments'] = intval( wp_count_comments( $post_id )->approved );
		$engagement['commentsHuman'] = $this->human_number( $engagement['comments'] );

		// PAGEVIEWS.
		$settings   = Nelio_Content_Settings::instance();
		$ga_view_id = $settings->get( 'google_analytics_view' );

		$pageviews = get_post_meta( $post_id, '_nc_pageviews_data', true );
		if ( empty( $pageviews ) || empty( $pageviews[ $ga_view_id ] ) ) {
			$value = get_post_meta( $post_id, '_nc_pageviews_total_' . $ga_view_id, true );
			if ( '' === $value ) {
				$value = -1;
			}//end if
			$pageviews = array(
				'total' => intval( $value ),
			);
		} else {
			$pageviews = $pageviews[ $ga_view_id ];
		}//end if

		// Put default values.
		$pageviews = wp_parse_args( $pageviews, array(
			'total'      => -1,
			'twitter'    => -1,
			'facebook'   => -1,
			'linkedin'   => -1,
			'pinterest'  => -1,
			'googleplus' => -1,
		) );
		$pageview_metrics = array( 'total', 'twitter', 'facebook', 'linkedin', 'pinterest', 'googleplus' );
		foreach ( $pageview_metrics as $metric ) {

			if ( 'total' === $metric ) {
				$pageviews['totalHuman'] = $this->human_number( $pageviews[ $metric ] );
			} else {
				$pageviews[ $metric . 'Human' ] = $this->human_percentage( $pageviews[ $metric ], $pageviews['total'] );
			}//end if

		}//end foreach

		return array(
			'engagement' => $engagement,
			'pageviews'  => $pageviews,
		);

	}//end get_post_stats()

	/**
	 * Get a set of posts from WordPress.
	 *
	 * @param  array $params Parameters to filter the search.
	 *
	 * @return array         The retrieved posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_posts( $params ) {
		$post_helper = Nelio_Content_Post_Helper::instance();

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$defaults = array(
			'post_status'    => 'publish',
			'post_type'      => $enabled_post_types,
		);

		$args = wp_parse_args( $params, $defaults );

		if ( isset( $args['meta_key'] ) &&
			'_nc_pageviews_total' === $args['meta_key'] ) {
			$args['meta_key'] = '_nc_pageviews_total_' . $settings->get( 'google_analytics_view' );
		}//end if

		$query = new WP_Query( $args );

		$posts = array();
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json();
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if

		}//end while

		wp_reset_postdata();

		return $posts;
	}//end get_posts()

	/**
	 * Get a set of posts from WordPress. First, return those posts whose
	 * statistics weren't updated before. Then, complete the returned list of
	 * posts with those posts whose update time is older.
	 *
	 * @param  array $params Parameters to filter the search.
	 *
	 * @return array         The retrieved posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_posts_using_last_update( $params ) {

		$params = wp_parse_args( $params, array( 'posts_per_page' => 10 ) );

		$post_helper = Nelio_Content_Post_Helper::instance();

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_query'  => array(
				array(
					'key'     => '_nc_last_update',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );

		$posts = array();
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json();
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if

		}//end while

		wp_reset_postdata();

		$posts_to_find = intval( $params['posts_per_page'] );
		if ( count( $posts ) === $posts_to_find ) {
			return $posts;
		}//end if

		if ( -1 !== $posts_to_find ) {
			$params['posts_per_page'] = $posts_to_find - count( $posts );
		}//end if

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_key'    => '_nc_last_update',
			'orderby'     => 'meta_value_num',
			'order'       => 'ASC',
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json();
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if

		}//end while

		wp_reset_postdata();

		return $posts;
	}//end get_posts_using_last_update()

	/**
	 * Checks whether the statistics of the given post needs to be updated or not.
	 * @param  integer $post_id the post.
	 * @return boolean          whether the statistics of the post need to be
	 *                          updated or not.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function needs_to_be_updated( $post_id ) {

		$last_update = get_post_meta( $post_id, '_nc_last_update', true );

		// If the custom field has no value, the post needs to be updated.
		if ( empty( $last_update ) ) {
			return true;
		}//end if

		$publication_date = get_post_time( 'U', true, $post_id );
		$now = time();

		// Less than 1 hour since last update means no update required.
		if ( $now - $last_update < 60 * 60 ) {
			return false;
		}//end if

		// Update posts published less than 1 week ago.
		if ( $now - $publication_date < 7 * 24 * 60 * 60 ) {
			return true;
		}//end if

		// Update other posts if they were last updated more than one day ago.
		if ( $now - $last_update > 24 * 60 * 60 ) {
			return true;
		} else {
			return false;
		}//end if

	}//end needs_to_be_updated()

	/**
	 * Helper function that updates the total engagement value when a new comment
	 * occurs in WordPress or an old comment changes its status.
	 *
	 * @param  integer $post_id the post whose engagement needs to be updated.
	 * @param  integer $new the new comment count.
	 * @param  integer $old the old comment count.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_comment_count( $post_id, $new, $old ) {

		$total = intval( get_post_meta( $post_id, '_nc_engagement_total', true ) );
		$aux = $new - $old; // Difference in comments.

		// Apply the difference to the engagement to update its value.
		update_post_meta( $post_id, '_nc_engagement_total', $total + $aux );

	}//end update_comment_count()

	/**
	 * Helper function that obtains the access token and refresh token in Google
	 * Analytics.
	 *
	 * @param string $code the provisional code to exchange in Google Analytics.
	 *
	 * @return string The token to access Google Analytics.
	 */
	public function get_ga_token( $code ) {

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body' => wp_json_encode( array(
				'code' => $code,
			)	),
		);

		$url = nc_get_api_url( '/ga/token', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$json = json_decode( $response['body'] );
		if ( ! isset( $json->token ) ||
			 ! isset( $json->refresh ) ||
			 ! isset( $json->expiration ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$expiration = $json->expiration - ( 10 * MINUTE_IN_SECONDS );
		set_transient( 'nc_ga_token', $json->token, $expiration );
		update_option( 'nc_ga_refresh_token', $json->refresh );
		delete_option( 'nc_ga_token_error' );

		return $json->token;

	}//end get_ga_token()

	/**
	 * Helper function that obtains the views from Google Analytics using the
	 * token in WordPress option.
	 *
	 * @return array The views from Google Analytics.
	 */
	public function get_ga_views() {
		$settings = Nelio_Content_Settings::instance();

		if ( false === ( $ga_token = get_transient( 'nc_ga_token' ) ) ) {
			// Get a new valid Google Analytics token.
			$ga_token = $this->refresh_ga_token();
		}//end if

		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
				'authorization' => 'Bearer ' . $ga_token,
			),
		);

		$url = 'https://www.googleapis.com/analytics/v3/management/accountSummaries?max-results=1000';
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		$response = json_decode( $response['body'] );

		if ( isset( $response->error ) ) {
			return false;
		}//end if

		return $response;
	}//end get_ga_views()

	/**
	 * Helper function that saves post engagement statistic values.
	 *
	 * @param  integer $post_id the post whose engagement has to be saved.
	 * @param  array   $engagement the engagement values to be saved.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function save_engagement( $post_id, $engagement ) {

		// Properly compute total value.
		$aux = 0;
		foreach ( $engagement as $key => $value ) {
			if ( -1 !== $value ) {
				$aux += $value;
			} else {
				$aux += intval( get_post_meta( $post_id, '_nc_engagement_' . $key, true ) );
			}//end if
		}//end foreach

		$engagement['total'] = $aux;

		// Save.
		foreach ( $engagement as $key => $value ) {
			if ( 'comments' !== $key && -1 !== $value ) {
				update_post_meta( $post_id, '_nc_engagement_' . $key, $value );
			}//end if
		}//end foreach
	}//end save_engagement()

	/**
	 * Helper function that saves post pageviews.
	 *
	 * @param  integer $ga_view_id the id of the Google Analytics view.
	 * @param  integer $post_id    the post whose pageviews have to be saved.
	 * @param  array   $pageviews  the pageview values to be saved.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function save_pageviews( $ga_view_id, $post_id, $pageviews ) {
		if ( $ga_view_id ) {

			if ( -1 === $pageviews['total'] ) {
				return;
			}//end if

			update_post_meta( $post_id, '_nc_pageviews_total_' . $ga_view_id, $pageviews['total'] );

			// Process and update traffic percentages from social networks.
			$value = get_post_meta( $post_id, '_nc_pageviews_data', true );
			if ( ! is_array( $value ) ) {
				$value = array();
			}//end if

			// Filter empty data.
			$pageviews = array_filter( $pageviews, array( $this, 'has_proper_value' ) );

			if ( isset( $value[ $ga_view_id ] ) ) {
				$current_values = $value[ $ga_view_id ];
				$pageviews = wp_parse_args( $pageviews, $current_values );
			}//end if

			$value[ $ga_view_id ] = $pageviews;

			update_post_meta( $post_id, '_nc_pageviews_data', $value );

		}//end if
	}//end save_pageviews()

	private function has_proper_value( $element ) {
		return -1 !== $val;
	}//end has_proper_value()

	/**
	 * Helper function that, given a certain number, returns an easier-to-read
	 * string by humans.
	 *
	 * @param integer $number    the number to humanize.
	 * @param integer $precision the number of decimal cyphers we want.
	 *
	 * @return string the simplified representation of the input number.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function human_number( $number, $precision = 1 ) {

		if ( -1 === $number ) {
			return '&ndash;';
		}//end if

		$units = array( '','k','M','G','T','P','E','Z','Y' );
		$step = 1000;
		$i = 0;
		while ( ( $number / $step ) > 0.9 ) {
			$number = $number / $step;
			$i++;
		}//end while

		if ( floor( $number ) >= 100 ) {
			$number = number_format_i18n( $number, 0 );
		} elseif ( floor( $number ) * 10 !== floor( $number * 10 ) ) {
			$number = number_format_i18n( $number, $precision );
		} else {
			$number = number_format_i18n( $number, 0 );
		}//end if

		return $number . $units[ $i ];
	}//end human_number()

	/**
	 * Helper function that, given a certain number and a total value, returns the
	 * percentage of that number with respect to the total in an easier-to-read
	 * string by humans.
	 *
	 * @param integer $number    the number.
	 * @param integer $total     the total.
	 *
	 * @return string the simplified representation of the percentage of the
	 *                input number with respect to the total.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function human_percentage( $number, $total ) {

		if ( 0 === $total ) {
			return '0';
		}//end if

		if ( -1 === $number || -1 === $total ) {
			return '&ndash;';
		}//end if

		$percentage = ( $number * 100 ) / $total;

		if ( 0 === $percentage ) {
			return '0';
		}//end if

		if ( $percentage < 10 ) {
			return number_format_i18n( $percentage, 1 );
		}//end if

		return number_format_i18n( $percentage, 0 );

	}//end human_percentage()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Facebook.
	 *
	 * @param string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in Facebook.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_facebook_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_get(
			'https://graph.facebook.com/?id=' . urlencode( $url ),
			array(
				'sslverify' => false,// Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Facebook total activities for requested URL.
		if ( ! isset( $json['share']['share_count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['share']['share_count'] );
		return $value;

	}//end get_facebook_count()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in LinkedIn.
	 *
	 * @param string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in LinkedIn.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_linkedin_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_get(
			'http://www.linkedin.com/countserv/count/share?format=json&url=' . urlencode( $url ),
			array(
				'sslverify' => false,// Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of LinkedIn Shares for requested URL.
		if ( ! isset( $json['count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['count'] );
		return $value;

	}//end get_linkedin_count()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Pinterest.
	 *
	 * @param  string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in Pinterest.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_pinterest_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_get(
			'https://api.pinterest.com/v1/urls/count.json?url=' . urlencode( $url ),
			array(
				'sslverify' => false,// Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );
		$json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Pinterest Shares for requested URL.
		if ( ! isset( $json['count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['count'] );
		return $value;

	}//end get_pinterest_count()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Google+.
	 *
	 * @param  string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in Google+.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_googleplus_count( $url ) {

		$args = array(
			'method'    => 'POST',
			'sslverify' => false, // Disable checking SSL certificates.
			'headers'   => array(
				// Setup content type to JSON.
				'Content-Type' => 'application/json',
			),
			// Setup POST options to Google API.
			'body' => wp_json_encode( array(
				'method'     => 'pos.plusones.get',
				'id'         => 'p',
				'jsonrpc'    => '2.0',
				'key'        => 'p',
				'apiVersion' => 'v1',
				'params'     => array(
					'nolog'   => true,
					'id'      => $url,
					'source'  => 'widget',
					'userId'  => '@viewer',
					'groupId' => '@self',
				),
			) ),
		);

		// Retrieves JSON with HTTP POST method for current URL.
		$json_string = wp_remote_post( 'https://clients6.google.com/rpc', $args );

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		$json = json_decode( $json_string['body'], true );

		// Get count of Google +1 for requsted URL.
		if ( ! isset( $json['result']['metadata']['globalCounts']['count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['result']['metadata']['globalCounts']['count'] );
		return $value;

	}//end get_googleplus_count()

	/**
	 * Helper function that, given a certain URL, finds its pageviews
	 * count and referred trafic from social media in Google Analytcs.
	 *
	 * @param  integer $ga_view_id the identifier of Google Analytics view.
	 * @param  integer $post_id    the identifier of the post.
	 * @param  string  $url        the URL whose analytics has to be found.
	 * @param  string  $start_date the starting date from which look at data.
	 *
	 * @return array the analytics of the given URL in Google Analytics.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_ga_data( $ga_view_id, $post_id, $url, $start_date ) {

		$defaults = array(
			'total'      => -1,
			'twitter'    => -1,
			'facebook'   => -1,
			'linkedin'   => -1,
			'pinterest'  => -1,
			'googleplus' => -1,
		);

		$result = get_post_meta( $post_id, '_nc_pageviews_data', true );
		if ( empty( $result ) || ! isset( $result[ $ga_view_id ] ) ) {
			$result = array();
		} else {
			$result = $result[ $ga_view_id ];
		}//end if

		$result = wp_parse_args( $result, $defaults );

		$path = preg_replace( '/^https?:\/\/[^\/]+/', '', $url );
		if ( ! $path ) {
			$path = '/';
		}//end if

		if ( false === ( $ga_token = get_transient( 'nc_ga_token' ) ) ) {
			// Get a new valid Google Analytics token.
			$ga_token = $this->refresh_ga_token();
		}//end if

		$args = array(
			'method'    => 'POST',
			'sslverify' => false, // Disable checking SSL certificates.
			'headers'   => array(
				// Setup content type to JSON.
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $ga_token,
			),
			// Setup POST options to Google API.
			'body' => wp_json_encode( array(
				'reportRequests' => array(
					array(
						'viewId'     => $ga_view_id,
						'dimensions' => array(
							array( 'name' => 'ga:pagePath' ),
						),
						'metrics' => array(
							array( 'expression' => 'ga:pageviews' ),
						),
						'dimensionFilterClauses' => array(
							array(
								'filters' => array(
									array(
										'operator'      => 'EXACT',
										'dimensionName' => 'ga:pagePath',
										'expressions'   => array( $path ),
									),
								),
							),
						),
						'dateRanges' => array(
							array(
								'startDate' => $start_date,
								'endDate'   => 'today',
							),
						),
					),
					array(
						'viewId'     => $ga_view_id,
						'dimensions' => array(
							array( 'name' => 'ga:pagePath' ),
							array( 'name' => 'ga:socialNetwork' ),
						),
						'metrics' => array(
							array( 'expression' => 'ga:pageviews' ),
						),
						'dimensionFilterClauses' => array(
							array(
								'operator' => 'AND',
								'filters'  => array(
									array(
										'operator'      => 'EXACT',
										'dimensionName' => 'ga:pagePath',
										'expressions'   => array( $path ),
									),
									array(
										'operator'      => 'IN_LIST',
										'dimensionName' => 'ga:socialNetwork',
										'expressions'   => array(
											'Twitter',
											'Facebook',
											'Pinterest',
											'LinkedIn',
											'Google+',
										),
									),
								),
							),
						),
						'dateRanges' => array(
							array(
								'startDate' => $start_date,
								'endDate'   => 'today',
							),
						),
					),
				),
			) ),
		);

		// Retrieves JSON with HTTP POST method for current URL.
		$json_string = wp_remote_post( 'https://analyticsreporting.googleapis.com/v4/reports:batchGet', $args );
		if ( is_wp_error( $json_string ) ) {
			return $result;
		}//end if

		$json = json_decode( $json_string['body'] );

		// Get pageviews.
		if ( ! isset( $json->reports[0]->data->totals[0]->values[0] ) ) {
			return $result;
		}//end if

		$result['total'] = intval( $json->reports[0]->data->totals[0]->values[0] );

		// Pageviews referred by social networks.
		if ( ! isset( $json->reports[1]->data->rows ) ) {
			return $result;
		}//end if

		$rows = $json->reports[1]->data->rows;
		foreach ( $rows as $row ) {

			if ( ! isset( $row->metrics[0]->values[0] ) ) {
				continue;
			}//end if

			if ( ! isset( $row->dimensions[1] ) ) {
				continue;
			}//end if

			$value = intval( $row->metrics[0]->values[0] );
			switch ( $row->dimensions[1] ) {
				case 'Twitter':
					$result['twitter'] = $value;
					break;

				case 'Facebook':
					$result['facebook'] = $value;
					break;

				case 'LinkedIn':
					$result['linkedin'] = $value;
					break;

				case 'Pinterest':
					$result['pinterest'] = $value;
					break;

				case 'Google+':
					$result['googleplus'] = $value;
					break;

				default:
					break;
			}//end switch

		}//end foreach

		return $result;

	}//end get_ga_data()

	/**
	 * Helper function that gets a valid token to access Google Analytcs.
	 * See https://developers.google.com/identity/protocols/OAuth2WebServer#refresh
	 *
	 * @return string the token to access Google Analytics.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function refresh_ga_token() {

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body' => wp_json_encode( array(
				'code' => get_option( 'nc_ga_refresh_token', '' ),
			)	),
		);

		$url = nc_get_api_url( '/ga/token/refresh', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			update_option( 'nc_ga_token_error', true );
			return;
		}//end if

		$json = json_decode( $response['body'] );
		if ( ! isset( $json->token ) ) {
			update_option( 'nc_ga_token_error', true );
			return;
		}//end if

		$expiration = $json->expiration - ( 10 * MINUTE_IN_SECONDS );
		set_transient( 'nc_ga_token', $json->token, $expiration );
		delete_option( 'nc_ga_token_error' );

		return $json->token;

	}//end refresh_ga_token()

}//end class
