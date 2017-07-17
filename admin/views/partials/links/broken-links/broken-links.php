<?php
/**
 * This template is used for rendering the collection of broken links.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/broken-links
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

<script type="text/template" id="_nc-broken-links">

	<div class="nc-broken-links">

		<h3><?php echo esc_html_x( 'Broken', 'text (links)', 'nelio-content' ); ?></h3>

		<div class="nc-list"></div>

	</div><!-- .nc-broken-links -->

</script><!-- #_nc-links -->

