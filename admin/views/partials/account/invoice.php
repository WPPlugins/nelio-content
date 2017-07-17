<?php
/**
 * This partial renders a single invoice in the account page.
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

<script type="text/template" id="_nc-invoice">

	<tr>

		<td>
			<a href="<%= _.escape( invoiceUrl ) %>" target="_blank"><%= _.escape( reference ) %></a>
		</td>

		<td><%= moment( chargeDate ).calendar() %></td>

		<% if ( isRefunded ) { %>
			<td>
				<span class="nc-refunded"><?php echo esc_html_x( '(Refunded)', 'text (invoice)', 'nelio-content' ); ?></span>
				<strike><%= subtotalDisplay %></strike>
			</td>
		<% } else { %>
			<td><%= subtotalDisplay %></td>
		<% } %>

	</tr>

</script><!-- #_nc-invoice -->
