<?php
/**
 * Template for filtering posts.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/filters
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
$post_types = array();

$aux = $settings->get( 'calendar_post_types' );
foreach ( $aux as $post_type_name ) {
	$post_type = get_post_type_object( $post_type_name );
	if ( ! $post_type || is_wp_error( $post_type ) ) {
		continue;
	}//end if
	array_push( $post_types, $post_type );
}//end foreach

if ( 1 === count( $post_types ) && 'post' === $post_types[0]->name ) {
	include NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/post-and-category-filter.php';
} elseif ( 1 === count( $post_types ) ) {
	include NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/custom-post-type-filter.php';
} else {
	include NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/multiple-post-types-filter.php';
}//end if
