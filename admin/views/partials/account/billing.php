<?php
/**
 * This partial defines the basic skeletton for rendering all the invoices
 * related to a subscription.
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

<script type="text/template" id="_nc-billing">

		<table>
			<thead><tr>
				<th><?php echo esc_html_x( 'Invoice Reference', 'text (account, billing table title)', 'nelio-content' ); ?></th>
				<th><?php echo esc_html_x( 'Date', 'text (account, billing table title)', 'nelio-content' ); ?></th>
				<th><?php echo esc_html_x( 'Total', 'text (account, billing table title)', 'nelio-content' ); ?></th>
			</tr></thead>

			<tbody class="nc-invoice-list"></tbody>

		</table>

</script><!-- #_nc-billing -->
