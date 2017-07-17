<?php
/**
 * This partial renders a single fastspring product.
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

<script type="text/template" id="_nc-fastspring-product">

	<table class="nc-fastspring-product">
		<tr>

			<td><input type="radio" name="nc-fastspring-product" value="<%= id %>" /></td>

			<td>

				<div class="nc-name-and-price">

					<span class="nc-name"><%= _.escape( name ) %></span> &ndash;

					<span class="nc-price" title="USD">$<%= _.escape( price ) %></span>

				</div><!-- .nc-name-and-price -->

				<div class="nc-description"><%= longDescription %></div>

			</td>

		</tr>
	</table><!-- .nc-fastspring-product -->

</script><!-- #_nc-fastspring-product -->
