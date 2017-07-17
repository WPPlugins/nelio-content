<?php
/**
 * The underscore template for rendering the external featured image dialog.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/featured-image
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
$click_to_edit = esc_html_x( 'Click the image to edit or update', 'user', 'nelio-content' );

?>

<script type="text/template" id="_nc-external-featured-image-dialog">

	<div class="nc-url">
		<input type="text" value="<%= _.escape( url ) %>" placeholder="http://&hellip;" />
		<span class="spinner"></span>
	</div>

	<div class="nc-image">
		<img src="<%= url %>" />
	</div>

	<div class="nc-alt">
		<label class="setting link-text" style="display: block;">
			<span><?php
				echo esc_html_x( 'Alt Text', 'text', 'nelio-content' );
			?></span>
			<input type="text" value="<%= _.escape( alt ) %>" />
		</label>
	</div>

</script><!-- #_nc-external-featured-image-dialog -->
