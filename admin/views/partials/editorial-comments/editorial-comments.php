<?php
/**
 * The underscore template for rendering a list of editorial comments, as well
 * as the form for adding new comments.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-comments
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-editorial-comments">

	<div class="nc-editorial-comments"></div>

	<div class="nc-new-editorial-comment">

		<textarea placeholder="<?php esc_attr_e( 'Write a comment and press enter to send&hellip;', 'nelio-content' ); ?>"></textarea>

	</div><!-- .nc-new-editorial-comment -->

</script><!-- #_nc-editorial-comments -->
