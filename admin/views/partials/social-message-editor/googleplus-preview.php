<?php
/**
 * This partial previews a social message in Google+.
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
<script type="text/template" id="_nc-googleplus-preview">

	<div class="nc-googleplus-preview">

		<div class="nc-profile-and-date">

			<div class="nc-profile-picture nc-first-letter-<%= profile.firstLetter %>">
				<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( profile.photo ) %> );"></div>
			</div><!-- .nc-picture -->

			<div class="nc-name-and-date">

				<div class="nc-display-name"><%= _.escape( profile.displayName ) %></div>
				<div class="nc-date"><%= dateFormatted %></div>

			</div><!-- .nc-name-and-date -->

		</div><!-- .nc-profile-and-date -->

		<% if ( textFormatted.length > 0 ) { %>
			<div class="nc-message<% if ( sharedLink.status === 'ready' ) { %> nc-ready-shared-link<% } %>"><%= textFormatted %></div>
		<% } else { %>
			<div class="nc-message nc-empty-message"><?php esc_html_e( 'Your status update&hellip;', 'nelio-content' ); ?></div>
		<% } %>

		<% if ( 'image' === type ) { %>

			<div class="nc-shared-image">
				<img src="<%= image %>" />
			</div><!-- .nc-shared-image -->

		<% } else { %>

			<% if ( sharedLink.status === 'ready' ) { %>

				<div class="nc-post">

					<% if ( sharedLink.image.length > 0 ) { %>
						<div class="nc-feat-image">
							<img src="<%= sharedLink.image %>" />
						</div><!-- .nc-feat-image -->
					<% } else { %>
						<br>
					<% } %>

					<div class="nc-content">

						<p class="nc-title"><%= _.escape( sharedLink.title ) %></p>
						<p class="nc-link"><%= _.escape( sharedLink.domain ) %></p>

						<p class="nc-excerpt"><%= _.escape( sharedLink.excerpt ) %></p>

					</div><!-- .nc-content -->

				</div><!-- .nc-post -->

			<% } else if ( sharedLink.status === 'loading' ) { %>

				<div class="nc-loading-shared-link">
					<span class="spinner is-active"></span>
				</div><!-- .nc-loading-shared-link -->

			<% } %>

		<% } %>

	</div><!-- .nc-googleplus-preview -->

</script><!-- #_nc-googleplus-preview -->
