<?php
/**
 * Template for filtering posts (by category).
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/filters
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<select class="nc-post-filter">

	<option value="all"><?php
		echo esc_html_x( 'Show All Posts', 'command (post filter)', 'nelio-content' );
	?></option>
	<option value="none"><?php
		echo esc_html_x( 'Hide All Posts', 'command (post filter)', 'nelio-content' );
	?></option>

	<?php
	// Print parent categories only.
	$categories = get_categories( array( 'hide_empty' => false ) );

	$aux = array();
	foreach ( $categories as $cat ) {

		if ( $cat->parent > 0 ) {
			continue;
		}//end if
		array_push( $aux, $cat );

	}//end foreach

	$categories = $aux;

	if ( count( $categories ) > 1 ) { ?>

		<optgroup label="<?php
			echo esc_attr_x( 'Filter by Category', 'user (post filter, group name)', 'nelio-content' );
		?>">

			<?php
			foreach ( $categories as $cat ) { ?>

				<option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php
					echo esc_html( $cat->name );
				?></option>

			<?php
			} ?>

		</optgroup>

	<?php
	} ?>

</select><!-- .nc-type-filter -->
