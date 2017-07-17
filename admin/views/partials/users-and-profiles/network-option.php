<?php
/**
 * This partial is used by a Select2 component and determines how a single
 * social profile has to be displayed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
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
}

?>

<script type="text/template" id="_nc-network-option">

	<span class="nc-network-option">

		<div class="nc-network nc-<%= network %> nc-single"></div>

		<%= _.escape( displayName ) %>

	</div><!-- .nc-network-option -->

</script><!-- #_nc-network-option -->

