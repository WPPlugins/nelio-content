<?php
/**
 * Template for filtering post types.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/filters
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

/**
 * This template uses the following variables:
 *
 * @var array $post_types Array of Post Type objects.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<select class="nc-post-filter">

	<option value="all"><?php
		echo esc_html_x( 'Show All Post Types', 'command (post filter)', 'nelio-content' );
	?></option>
	<option value="none"><?php
		echo esc_html_x( 'Hide All Post Types', 'command (post filter)', 'nelio-content' );
	?></option>

	<optgroup label="<?php
		echo esc_attr_x( 'Filter by Post Type', 'user (post filter, group name)', 'nelio-content' );
	?>">

		<?php
		foreach ( $post_types as $post_type ) { ?>

			<option value="<?php echo esc_attr( $post_type->name ); ?>"><?php
				echo esc_html( $post_type->labels->name );
			?></option>

		<?php
		} ?>

	</optgroup>

</select><!-- .nc-type-filter -->
