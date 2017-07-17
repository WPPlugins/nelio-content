<?php
/**
 * Partial for the calendar post type setting, which is implemented as a
 * select2 multiple selector component.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.0
 */

/**
 * List of vars used in this partial:
 *
 *  * array $relevant_post_types List of active relevant post types.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="calendar_post_types" multiple="multiple">

	<?php

	$options = array();

	// Default option: POST
	$label = esc_html_x( 'Post', 'text (default post type name)', 'nelio-content' );
	$selected = selected( in_array( 'post', $relevant_post_types ), true, false );
	$options[ $label . '0' ] = "<option value=\"post\" $selected>$label</option>";

	// Other options (custom post types)
	$i = 1;
	$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
	array_push( $post_types, get_post_type_object( 'page' ) );
	foreach ( $post_types as $pt ) {
		$label = esc_html( $pt->labels->singular_name );
		$selected = selected( in_array( $pt->name, $relevant_post_types ), true, false );
		$options[ $label . $i ] = "<option value=\"$pt->name\" $selected>$label</option>";
		++$i;
	}//end foreach

	ksort( $options );
	foreach ( $options as $option ) {
		echo $option;
	}//end foreach

	?>

</select>

<script type="text/javascript">
(function( $ ) {

	var $select = $( '#<?php echo $id; ?>' );
	$select.ncselect2({
		width: '100%',
		placeholder: <?php echo json_encode( _x( 'Select which post types can be managed by Nelio Content. Default: Post', 'user', 'nelio-content' ) ); ?>
	});

})( jQuery );
</script>
