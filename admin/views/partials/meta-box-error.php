<?php
/**
 * This partial shows an error message.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<script type="text/template" id="_nc-meta-box-error">

	<div class="nc-meta-box-error">
		<span class="nc-dashicons nc-dashicons-warning"></span>
		<span><%= message %>
	</div><!-- .nc-meta-box-error -->

</script><!-- #_nc-meta-box-error -->

