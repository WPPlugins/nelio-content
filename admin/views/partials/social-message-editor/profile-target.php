<?php
/**
 * This partial is used wihtin the target selector and renders an individual
 * target.
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
<script type="text/template" id="_nc-profile-target">

	<div class="nc-profile-target nc-selectable <% if ( selected ) { %> nc-selected<% } %>" data-target-name="<%= _.escape( name ) %>">

		<div class="nc-target-picture">

			<div class="nc-target-placeholder">
				<div class="nc-actual-target-picture" style="background-image: url( <%= _.escape( image ) %> );"></div>
			</div><!-- .nc-target-placeholder -->

			<div class="nc-selection-mark"></div>

		</div><!-- .nc-target-picture -->

		<div class="nc-information">

			<div class="nc-target-name"><%= _.escape( displayName ) %></div>

		</div><!-- .nc-information -->

	</div><!-- .nc-target -->

</script><!-- #_nc-profile-target -->
