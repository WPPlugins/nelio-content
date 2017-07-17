<?php
/**
 * This partial defines the content of the subscribe dialog.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/account
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<script type="text/template" id="_nc-subscribe-dialog">

		<% if ( ! areProductsReady ) { %>

			<div class="nc-loading-container">
				<span class="spinner is-active"></span>
				<p><?php
					echo esc_html_x( 'Loading products&hellip;', 'text', 'nelio-content' );
				?></p>
			</div><!-- .nc-loading-container -->

		<% } else if ( noProductsAvailable ) { %>

			<p><?php
				echo esc_html_x( 'There was an error whilst loading the product list. Please, try again later.', 'user', 'nelio-content' );
			?></p>

		<% } else { %>

			<p style="margin-top:0;"><?php
				echo esc_html_x( 'Please, select the plan you want to subscribe to:', 'user', 'nelio-content' );
			?></p>

			<div class="nc-plans">
			</div>

		<% } %>

</script><!-- #_nc-subscribe-dialog -->

