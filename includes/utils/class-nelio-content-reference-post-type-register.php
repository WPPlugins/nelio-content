<?php
/**
 * This file contains a class for registering the Reference post type.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class registers the Reference post type and its statuses.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Reference_Post_Type_Register {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()


	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_admin_hooks() {

		add_action( 'init', array( $this, 'register_post_types' ), 5 );
		add_action( 'init', array( $this, 'register_post_statuses' ), 9 );

	}//end define_admin_hooks()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_public_hooks() {

		add_action( 'init', array( $this, 'register_post_types' ), 5 );
		add_action( 'init', array( $this, 'register_post_statuses' ), 9 );

	}//end define_public_hooks()

	/**
	 * Callback for registering the Reference post type.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_post_types() {

		if ( post_type_exists( 'nc_reference' ) ) {
			return;
		}//end if

		/**
		 * This action fires right before registering the "Reference" and
		 * "Reference Author" post types.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_register_post_types' );

		register_post_type( 'nc_reference',
			/**
			 * Filters the args of the nc_reference post type.
			 *
			 * @since 1.0.0
			 *
			 * @param array $args The arguments, as defined in WordPress function register_post_type.
			 */
			apply_filters( 'nelio_content_register_reference_post_type',
				array(
					'labels'               => array(
						'name'               => _x( 'External References', 'text', 'nelio-content' ),
						'singular_name'      => _x( 'Reference', 'text', 'nelio-content' ),
						'menu_name'          => _x( 'References', 'text', 'nelio-content' ),
						'all_items'          => _x( 'References', 'text', 'Admin menu name', 'nelio-content' ),
						'add_new'            => _x( 'Add Reference', 'command', 'nelio-content' ),
						'add_new_item'       => _x( 'Add Reference', 'text', 'nelio-content' ),
						'edit_item'          => _x( 'Edit Reference', 'text', 'nelio-content' ),
						'new_item'           => _x( 'New Reference', 'text', 'nelio-content' ),
						'search_items'       => _x( 'Search References', 'command', 'nelio-content' ),
						'not_found'          => _x( 'No references found', 'text', 'nelio-content' ),
						'not_found_in_trash' => _x( 'No references found in trash', 'text', 'nelio-content' ),
					),
					'can_export'      => true,
					'capability_type' => 'nc_reference',
					'hierarchical'    => false,
					'map_meta_cap'    => true,
					'public'          => false,
					'query_var'       => false,
					'rewrite'         => false,
					'show_in_menu'    => 'nelio-content',
					'show_ui'         => false,
					'supports'        => array( 'title', 'author' ),
				)
			)
		);

	}//end register_post_types()

	/**
	 * This function registers all possible reference statuses:
	 *
	 *  * Complete.   The reference contains all the required information.
	 *  * Improvable. Some information is missing.
	 *  * Pending.    Information has never been automatically loaded. In other
	                  words, it's the status in which a reference is created.
	 *  * Broken.     Last time we looked at the link, it returned a 404.
	 *  * Check.      XXX.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_post_statuses() {

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Pending', 'text (reference)', 'nelio-content' ),
			'label_count' => _nx_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_pending', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Improvable', 'text (reference)', 'nelio-content' ),
			'label_count' => _nx_noop( 'Improvable <span class="count">(%s)</span>', 'Improvable <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_improvable', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Complete', 'text (reference)', 'nelio-content' ),
			'label_count' => _nx_noop( 'Complete <span class="count">(%s)</span>', 'Complete <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_complete', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Broken', 'text (reference)', 'nelio-content' ),
			'label_count' => _nx_noop( 'Broken <span class="count">(%s)</span>', 'Broken <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_broken', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Check Required', 'text (reference)', 'nelio-content' ),
			'label_count' => _nx_noop( 'Check Required <span class="count">(%s)</span>', 'Check Required <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_check', $args );

	}//end register_post_statuses()

}//end class

