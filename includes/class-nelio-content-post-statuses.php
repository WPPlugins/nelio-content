<?php
/**
 * This file contains a class for registering new post types.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class is used for registering new post types.
 *
 * Special thanks to Edit Flow's "Custom Statuses" module; most of the code
 * included here uses the ideas and hacks they created. The main difference
 * between our approach and theirs is that we don't allow user-defined
 * custom statuses.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.9
 */
class Nelio_Content_Post_Statuses {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.2.9
	 * @access protected
	 * @var    Nelio_Content_Analytics_Helper
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.2.9
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
	 * @since  1.2.9
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function define_hooks() {

		add_action( 'init', array( $this, 'register_post_statuses' ) );
		add_filter( 'wp_insert_post_data', array( $this, 'fix_custom_status_timestamp' ), 10, 2 );
		add_action( 'wp_insert_post', array( $this, 'fix_post_name' ), 10, 2 );

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'display_post_states', array( $this, 'display_post_statuses' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'fix_post_row_actions' ), 10, 2 );
			add_filter( 'page_row_actions', array( $this, 'fix_post_row_actions' ), 10, 2 );

			add_action( 'admin_init', array( $this, 'check_timestamp_on_publish' ) );

		}//end if

	}//end define_hooks()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! $this->is_whitelisted_page() ) {
			return;
		}//end if

		// Get current user
		wp_get_current_user();

		// Enqueue the script and localize it.
		$aux = Nelio_Content();
		wp_enqueue_script(
			'nelio-content-custom-status',
			NELIO_CONTENT_ADMIN_URL . '/js/custom-status.min.js',
			array( 'jquery', 'underscore' ),
			$aux->get_version(),
			true
		);

		wp_localize_script(
			'nelio-content-custom-status',
			'NelioContentPostStatuses',
			$this->get_params_for_admin_script()
		);

	}//end enqueue_scripts()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function register_post_statuses() {

		$statuses = $this->get_custom_statuses();
		foreach ( $statuses as $status => $attrs ) {
			register_post_status( $status, $attrs );
		}//end foreach

	}//end register_post_statuses()

	/**
	 * XXX
	 *
	 * @param array   $actions XXX.
	 * @param WP_Post $post    XXX.
	 *
	 * @return array XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function fix_post_row_actions( $actions, $post ) {

		$screen = get_current_screen();
		if ( 'edit' !== $screen->base ) {
			return $actions;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		$statuses = array_keys( $this->get_custom_statuses() );

		// Only modify if we're using a pre-publish status on a supported custom post type
		if ( ! in_array( $post->post_status, $statuses )
			|| ! in_array( $post->post_type, $post_types ) ) {
			return $actions;
		}//end if

		// 'view' is only set if the user has permission to post
		if ( empty( $actions['view'] ) ) {
			return $actions;
		}//end if

		$actions['view'] = sprintf(
			'<a href="%s" title="%s" rel="permalink">%s</a>',
			esc_url( $this->get_preview_link( $post ) ),
			esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $post->post_title ) ),
			__( 'Preview' )
		);

		return $actions;

	}//end fix_post_row_actions()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function display_post_statuses( $post_statuses, $post ) {

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		$statuses = $this->get_custom_statuses();
		if ( isset( $_GET['post_status'] ) ) {
			if ( in_array( $_GET['post_status'], array_keys( $statuses ) ) ) {
				return '';
			}//end if
		}//end if

		foreach ( $statuses as $status => $attrs ) {
			if ( in_array( $post->post_type, $post_types ) && $post->post_status === $status ) {
				return array( $status => $attrs['label'] );
			}//end if
		}//end foreach

		return $post_statuses;

	}//end display_post_statuses()

	/**
	 * This is a hack! hack! hack! until core is fixed/better supports custom statuses
	 *
	 * When publishing a post with a custom status, set the status to 'pending' temporarily
	 *
	 * @see Works around this limitation: http://core.trac.wordpress.org/browser/tags/3.2.1/wp-includes/post.php#L2694
	 * @see Original thread: http://wordpress.org/support/topic/plugin-edit-flow-custom-statuses-create-timestamp-problem
	 * @see Core ticket: http://core.trac.wordpress.org/ticket/18362
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function check_timestamp_on_publish() {

		global $pagenow, $wpdb;

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return;
		}//end if

		// Handles the transition to 'publish' on edit.php
		if ( 'edit.php' === $pagenow && isset( $_REQUEST['bulk_edit'] ) ) {

			// For every post_id, set the post_status as 'pending' only when there's no timestamp set for $post_date_gmt
			if ( 'publish' === $_REQUEST['_status'] ) {

				$post_ids = array_map( 'intval', ( array ) $_REQUEST['post'] );
				foreach ( $post_ids as $post_id ) {
					$wpdb->update(
						$wpdb->posts,
						array( 'post_status' => 'pending' ),
						array( 'ID' => $post_id, 'post_date_gmt' => '0000-00-00 00:00:00' )
					);
					clean_post_cache( $post_id );
				}//end foreach

			}//end if

		}//end if

		// Handles the transition to 'publish' on post.php
		if ( 'post.php' === $pagenow && isset( $_POST['publish'] ) ) {

			// Set the post_status as 'pending' only when there's no timestamp set for $post_date_gmt
			if ( isset( $_POST['post_ID'] ) ) {

				$post_id = ( int ) $_POST['post_ID'];
				$ret = $wpdb->update(
					$wpdb->posts,
					array( 'post_status' => 'pending' ),
					array( 'ID' => $post_id, 'post_date_gmt' => '0000-00-00 00:00:00' )
				);
				clean_post_cache( $post_id );

				foreach ( array('aa', 'mm', 'jj', 'hh', 'mn') as $timeunit ) {
					if ( ! empty( $_POST[ 'hidden_' . $timeunit ] )
						&& $_POST[ 'hidden_' . $timeunit ] != $_POST[ $timeunit ] ) {
						$edit_date = '1';
						break;
					}//end if
				}//end foreach

				if ( $ret && empty( $edit_date ) ) {
					add_filter( 'pre_post_date', array( $this, 'helper_timestamp_hack' ) );
					add_filter( 'pre_post_date_gmt', array( $this, 'helper_timestamp_hack' ) );
				}//end if

			}//end if

		}//end if

	}//end check_timestamp_on_publish()

	/**
	 * PHP < 5.3.x doesn't support anonymous functions
	 * This helper is only used for the check_timestamp_on_publish method above
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function helper_timestamp_hack() {

		return ( 'pre_post_date' === current_filter() ) ? current_time('mysql') : '';

	}//end helper_timestamp_hack()

	/**
	 * This is a hack! hack! hack! until core is fixed/better supports custom statuses
	 *
	 * Normalize post_date_gmt if it isn't set to the past or the future
	 *
	 * @see Works around this limitation: https://core.trac.wordpress.org/browser/tags/4.5.1/src/wp-includes/post.php#L3182
	 * @see Original thread: http://wordpress.org/support/topic/plugin-edit-flow-custom-statuses-create-timestamp-problem
	 * @see Core ticket: http://core.trac.wordpress.org/ticket/18362
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function fix_custom_status_timestamp( $data, $postarr ) {

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return $data;
		}//end if

		$status_slugs = array_keys( $this->get_custom_statuses() );

		//Post is scheduled or published? Ignoring.
		if ( ! in_array( $postarr['post_status'], $status_slugs ) ) {
			return $data;
		}//end if

		//If empty, keep empty.
		if ( empty( $postarr['post_date_gmt'] ) || '0000-00-00 00:00:00' === $postarr['post_date_gmt'] ) {
			$data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		return $data;

	}//end fix_custom_status_timestamp()

	/**
	 * Another hack! hack! hack! until core better supports custom statuses
	 *
	 * @since 0.7.4
	 *
	 * Keep the post_name value empty for posts with custom statuses
	 * Unless they've set it customly
	 * @see https://github.com/danielbachhuber/Edit-Flow/issues/123
	 * @see http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/post.php#L2530
	 * @see http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/post.php#L2646
	 */
	public function fix_post_name( $post_id, $post ) {

		global $wpdb, $pagenow;

		// Only modify if we're using a pre-publish status on a supported custom post type
		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		$status_slugs = array_keys( $this->get_custom_statuses() );

		if ( 'post.php' !== $pagenow
			|| ! in_array( $post->post_status, $status_slugs )
			|| ! in_array( $post->post_type, $post_types ) ) {
			return;
		}//end if

		// The slug has been set by the meta box
		if ( ! empty( $_POST['post_name'] ) ) {
			return;
		}//end if

		$wpdb->update( $wpdb->posts, array( 'post_name' => '' ), array( 'ID' => $post_id ) );
		clean_post_cache( $post_id );

	}//end fix_post_name()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_custom_statuses() {

		return array(
			'idea' => array(
				'label'                     => _x( 'Idea', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Idea <span class="count">(%s)</span>', 'Ideas <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
			'assigned' => array(
				'label'                     => _x( 'Assigned', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Assigned <span class="count">(%s)</span>', 'Assigned <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
			'in-progress' => array(
				'label'                     => _x( 'In Progress', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'In Progress <span class="count">(%s)</span>', 'In Progress <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
		);

	}//end get_custom_statuses()

	/**
	 * XXX
	 *
	 * @param WP_Post $post XXX.
	 *
	 * @return string XXX.
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_preview_link( $post ) {

		switch ( $post->post_type ) {

			case 'page':
				$args = array(
					'page_id' => $post->ID,
					'preview' => 'true',
				);
				break;

			case 'post':
				$args = array(
					'p'       => $post->ID,
					'preview' => 'true',
				);
				break;

			default:
				$args = array(
					'p'         => $post->ID,
					'post_type' => $post->post_type,
					'preview'   => 'true',
				);

		}//end switch

		return add_query_arg( $args, home_url() );

	}//end get_preview_link()

	/**
	 * Whether custom post statuses should be disabled for this post type.
	 *
	 * Used to stop custom statuses from being registered for post types that
	 * don't support them.
	 *
	 * @since 1.2.9
	 *
	 * @return bool
	 */
	private function disable_custom_statuses_for_post_type( $post_type = null ) {

		global $pagenow;

		// Only allow deregistering on 'edit.php' and 'post.php'
		if ( ! in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
			return false;
		}//end if

		if ( is_null( $post_type ) ) {
			$post_type = $this->get_current_post_type();
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		if ( $post_type && ! in_array( $post_type, $post_types ) ) {
			return true;
		}//end if

		return false;

	}//end disable_custom_statuses_for_post_type()

	/**
	 * Returns the current post type.
	 *
	 * @return string|null $post_type The post type we've found, or null if no post type.
	 *
	 * @since 1.2.9
	 * @access private
	 */
	private function get_current_post_type() {

		global $post, $typenow, $pagenow, $current_screen;

		$post_id = false;
		if ( isset( $_REQUEST['post'] ) ) {
			$post_id = absint( $_REQUEST['post'] );
		}//end if

		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && ! empty( $current_screen->post_type ) ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( 'post.php' === $pagenow && $post_id && ! empty( get_post( $post_id )->post_type ) ) {
			$post_type = get_post( $post_id )->post_type;
		} elseif ( 'edit.php' === $pagenow && empty( $_REQUEST['post_type'] ) ) {
			$post_type = 'post';
		} else {
			$post_type = null;
		}//end if

		return $post_type;

	}//end get_current_post_type()

	/**
	 * Check whether custom status stuff should be loaded on this page
	 *
	 * @return boolean XXX.
	 *
	 * @since 1.2.9
	 */
	function is_whitelisted_page() {

		global $pagenow;

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return false;
		}//end if

		$post_type_obj = get_post_type_object( $this->get_current_post_type() );
		if( ! current_user_can( $post_type_obj->cap->edit_posts ) ) {
			return false;
		}//end if

		return in_array( $pagenow, array( 'post.php', 'edit.php', 'post-new.php', 'page.php', 'edit-pages.php', 'page-new.php' ) );

	}//end is_whitelisted_page()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_params_for_admin_script() {

		global $post;

		if ( empty( $post ) || ! $post->ID || 'auto-draft' === $post->post_status ) {

			$settings = Nelio_Content_Settings::instance();
			if ( $settings->get( 'use_custom_post_statuses' ) ) {
				$status = 'idea';
			} else {
				$status = 'draft';
			}//end if

		} else {

			$status = $post->post_status;

		}//end if

		$post_type_obj = get_post_type_object( $this->get_current_post_type() );
		return array(
			'canUserPublishPosts'       => current_user_can( $post_type_obj->cap->publish_posts ),
			'canUserEditPublishedPosts' => current_user_can( $post_type_obj->cap->edit_published_posts ),
			'currentStatus'             => $status,
			'i18n' => array(
				'noChange'  => '&mdash; ' . _x( 'No Change', 'text', 'nelio-content' ) . ' &mdash;',
				'ok'        => _x( 'OK', 'command', 'nelio-content' ),
				'cancel'    => _x( 'Cancel', 'command', 'nelio-content' ),
				'save'      => _x( 'Save', 'command', 'nelio-content' ),
				'published' => _x( 'Published', 'text', 'nelio-content' ),
			),
			'statuses' => array(
				array(
					'name'   => 'private',
					'label'  => _x( 'Private', 'post status', 'nelio-content' ),
					'action' => _x( 'Save as Private', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'idea',
					'label'  => _x( 'Idea', 'post status', 'nelio-content' ),
					'action' => _x( 'Save Idea', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'assigned',
					'label'  => _x( 'Assigned', 'post status', 'nelio-content' ),
					'action' => _x( 'Save', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'in-progress',
					'label'  => _x( 'In Progress', 'post status', 'nelio-content' ),
					'action' => _x( 'Save', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'draft',
					'label'  => _x( 'Draft', 'post status', 'nelio-content' ),
					'action' => _x( 'Save Draft', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'pending',
					'label'  => _x( 'Pending', 'post status', 'nelio-content' ),
					'action' => _x( 'Save as Pending', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'future',
					'label'  => _x( 'Scheduled', 'post status', 'nelio-content' ),
					'action' => _x( 'Save', 'command', 'nelio-content' ),
				),
				array(
					'name'   => 'publish',
					'label'  => _x( 'Published', 'post status', 'nelio-content' ),
					'action' => _x( 'Save', 'command', 'nelio-content' ),
				),
			),
		);

	}//end get_params_for_admin_script()

}//end class
