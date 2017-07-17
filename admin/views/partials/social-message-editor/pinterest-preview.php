<?php
/**
 * This partial previews a social message in Twitter.
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
<script type="text/template" id="_nc-pinterest-preview">

	<div class="nc-pinterest-preview">

		<div class="nc-shared-image">

			<% if ( 'image' === type ) { %>

				<div class="nc-shared-image" style="background-image: url( <%= _.escape( image ) %> )"></div>

			<% } else { %>

				<div class="nc-fake-image">
					<div class="nc-missing-image-notice"><?php
						echo esc_html_x( 'Select an image to pin using the social message controls above', 'text (pinterest)', 'nelio-content' );
					?></div>
				</div>

			<% } %>

		</div><!-- .nc-shared-image -->


		<div class="nc-meta-information">

			<div class="nc-profile-picture nc-first-letter-<%= profile.firstLetter %>">
				<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( profile.photo ) %> );"></div>
			</div><!-- .nc-picture -->

			<div class="nc-name-date-and-message">

				<div class="nc-display-name"><?php
					echo esc_html_x( 'Saved by', 'text (pinterest)', 'nelio-content' );
				?><br><strong><%= _.escape( profile.displayName ) %></strong></div>

				<div class="nc-date"><%= dateFormatted %></div>

				<% if ( textFormatted.length > 0 ) { %>

					<div class="nc-message<% if ( sharedLink.status === 'ready' ) { %> nc-ready-shared-link<% } %>"><%= textFormatted %></div>

				<% } else { %>

					<div class="nc-message nc-empty-message"><?php esc_html_e( 'Your status update&hellip;', 'nelio-content' ); ?></div>

				<% } %>

			</div><!-- .nc-name-date-and-message -->

		</div><!-- .nc-meta-information -->

	</div><!-- .nc-pinterest-preview -->

</script><!-- #_nc-pinterest-preview -->
