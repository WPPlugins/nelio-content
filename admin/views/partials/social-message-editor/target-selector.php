<?php
/**
 * This partial is used for rendering and selecting targets, when a social
 * profile uses them.
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
<script type="text/template" id="_nc-target-selector">

	<div class="nc-target-selector">

		<% if ( loadingTargets ) { %>

			<div class="nc-loading-targets">

				<div class="nc-loading-container">
					<span class="spinner is-active"></span>
					<p><%= loadingLabel %></p>
				</div><!-- .nc-loading-container -->

			</div><!-- .nc-loading-targets -->

		<% } else { %>

			<%= explanation %>

			<div class="nc-targets"></div>

		<% } %>

	</div><!-- .nc-target-selector -->

</script><!-- #_nc-target-selector -->
