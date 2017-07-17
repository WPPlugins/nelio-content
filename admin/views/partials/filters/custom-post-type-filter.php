<?php
/**
 * Template for showing/hiding a single post type.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/filters
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This template uses the following variables:
 *
 * @var array $post_types Array of Post Type objects, which contains one element only.
 */

$post_type = $post_types[0];
?>

<select class="nc-post-filter">

	<option value="all"><?php
		printf(
			/* translators: e.g. Show Movies, Show Articles... */
			esc_html_x( 'Show %s', 'command (post filter)', 'nelio-content' ),
			esc_html( $post_type->labels->name )
		);
	?></option>
	<option value="none"><?php
		printf(
			/* translators: e.g. Hide Movies, Hide Articles... */
			esc_html_x( 'Hide %s', 'command (post filter)', 'nelio-content' ),
			esc_html( $post_type->labels->name )
		);
	?></option>

</select><!-- .nc-type-filter -->
