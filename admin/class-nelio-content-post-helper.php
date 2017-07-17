<?php
/**
 * This file contains a class with some post-related helper functions.
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
 * This class implements post-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Post_Helper {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Nelio_Content_Post_Helper
	 */
	protected static $_instance;

	/**
	 * List of WordPress categories. If the variable hasn't been initialized, `false`.
	 *
	 * @var array|boolean
	 */
	private $categories = false;

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
	 * @return Nelio_Content_Post_Helper the single instance of this class.
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
	 * Helper function that, given a certain category, finds its highest ancestor.
	 *
	 * @param integer $term_id the term whose highest ancestor has to be found.
	 *
	 * @return object|boolean the highest ancestor of the given term, or false if the given term was not found.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function find_root_category( $term_id ) {

		if ( false === $this->categories ) {
			$this->categories = get_categories( array( 'hide_empty' => false ) );
		}//end if

		foreach ( $this->categories as $cat ) {
			if ( $cat->term_id === $term_id ) {
				if ( 0 === $cat->parent ) {
					return $cat;
				} else {
					return $this->find_root_category( $cat->parent );
				}//end if
			}//end if
		}//end if

		return false;

	}//end find_root_category()

	/**
	 * This function creates a ncselect2-ready object with (a) the current post
	 * in the loop or (b) the post specified in `$post_id`.
	 *
	 * @param integer $post_id Optional. The ID of the post we want to stringify.
	 *
	 * @return array a ncselect2-ready object with (a) the current post * in the
	 *               loop or (b) the post specified in `$post_id`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function post_to_json( $post_id = 0 ) {

		global $post;

		// Load some settings.
		$settings = Nelio_Content_Settings::instance();

		if ( $post_id > 0 ) {

			$my_query = new WP_Query( array(
				'p'         => $post_id,
				'post_type' => $settings->get( 'calendar_post_types' ),
			) );
			if ( ! $my_query->have_posts() ) {
				return false;
			}//end if

			$my_query->the_post();

		}//end if

		$aux = get_the_category();
		$processed_categories = array();
		$categories = array();
		foreach ( $aux as $cat ) {

			$root_cat = $this->find_root_category( $cat->term_id );
			if ( $root_cat && ! in_array( $root_cat->term_id, $processed_categories, true ) ) {
				array_push( $processed_categories, $root_cat->term_id );
				array_push( $categories, array(
					'id'   => $root_cat->term_id,
					'name' => $root_cat->name,
				) );
			}//end if

		}//end foreach

		// Recover featured image.
		$aux = Nelio_Content_External_Featured_Image_Helper::instance();
		$featured_image = $aux->get_external_featured_image( get_the_ID() );
		$featured_thumb = $featured_image;

		if ( empty( $featured_image ) && get_post_thumbnail_id() ) {
			$featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
			$featured_thumb = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
		}//end if

		$position = $settings->get( 'auto_feat_image' );
		if ( empty( $featured_image ) && 'disabled' !== $position ) {
			$featured_image = $aux->get_auto_featured_image( get_the_ID(), $position );
			$featured_thumb = $featured_image;
		}//end if

		if ( empty( $featured_thumb ) ) {
			$featured_thumb = NELIO_CONTENT_ADMIN_URL . '/images/default-featured-image-thumbnail.png';
		}//end if

		$post_type_name = _x( 'Post', 'text (default post type name)', 'nelio-content' );
		$post_type = get_post_type_object( get_post_type() );
		if ( ! empty( $post_type ) && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
			$post_type_name = $post_type->labels->singular_name;
		}//end if

		// Filter the variables that might be internationalized (such as post title or excerpt).
		;

		/**
		 * Modifies the title of the post.
		 *
		 * @param string $title   the title.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$title = apply_filters( 'nelio_content_post_title', get_the_title(), get_the_ID() );

		if ( has_excerpt() ) {
			$excerpt = get_the_excerpt();
		} else {
			$excerpt = '';
		}//end if

		/**
		 * Modifies the excerpt of the post.
		 *
		 * @param string $excerpt the excerpt.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$excerpt = apply_filters( 'nelio_content_post_excerpt', $excerpt, get_the_ID() );

		// Check if there's a valid date set.
		global $post;
		$date = ' ' . $post->post_date_gmt;
		if ( strpos( $date, '0000-00-00' ) ) {
			$date = false;
		} else {
			$date = get_post_time( 'c', true );
		}//end if

		$aux = Nelio_Content_Analytics_Helper::instance();
		$statistics = $aux->get_post_stats( get_the_ID() );

		$permalink = get_permalink();

		if ( 'publish' !== get_post_status() ) {
			$aux = clone $post;
			$aux->post_status = 'publish';
			if ( empty( $aux->post_name ) ) {
				$aux->post_name = sanitize_title( $aux->post_title, $aux->ID );
			}//end if
			$aux->post_name = wp_unique_post_slug( $aux->post_name, $aux->ID, 'publish', $aux->post_type, $aux->post_parent );
			$permalink = get_permalink( $aux );
		}//end if

		$result = array(
			'id'               => get_the_ID(),
			'author'           => get_the_author_meta( 'ID' ),
			'authorName'       => get_the_author(),
			'calendarKind'     => 'post',
			'categories'       => $categories,
			'date'             => $date,
			'editLink'         => get_edit_post_link( get_the_ID(), 'default' ),
			'excerptFormatted' => $excerpt,
			'image'            => $featured_image,
			'imageId'          => get_post_thumbnail_id(),
			'permalink'        => $permalink,
			'statistics'       => $statistics,
			'status'           => get_post_status(),
			'thumbnail'        => $featured_thumb,
			'titleFormatted'   => $title,
			'type'             => get_post_type(),
			'typeName'         => $post_type_name,
		);

		if ( $post_id > 0 ) {
			wp_reset_postdata();
		}//end if

		return $result;

	}//end post_to_json()

}//end class
