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
<script type="text/template" id="_nc-twitter-preview">

	<div class="nc-twitter-preview">

		<div class="nc-profile">

			<div class="nc-profile-picture nc-first-letter-<%= profile.firstLetter %>">
				<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( profile.photo ) %> );"></div>
			</div><!-- .nc-profile-picture -->

			<div class="nc-name">
				<div class="nc-display-name"><%= _.escape( profile.displayName ) %></div>
				<div class="nc-username"><%= _.escape( profile.username ) %></div>
			</div><!-- .nc-name -->

		</div><!-- .nc-profile -->

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
					<% } %>

					<div class="nc-content">

						<p class="nc-title"><%= _.escape( sharedLink.title ) %></p>

						<% if ( sharedLink.excerpt.length > 0 ) { %>
							<p class="nc-excerpt">
								<%= _.escape( sharedLink.excerpt ) %>
							</p><!-- .nc-excerpt -->
						<% } %>

						<p class="nc-signature">
							<%= _.escape( sharedLink.domain ) %>
						</p><!-- .nc-signature -->

					</div><!-- .nc-content -->

				</div><!-- .nc-post -->

			<% } else if ( sharedLink.status === 'loading' ) { %>

				<div class="nc-loading-shared-link">
					<span class="spinner is-active"></span>
				</div><!-- .nc-loading-shared-link -->

			<% } %>

		<% } %>

		<div class="nc-date">
			<%= dateFormatted %>
		</div><!-- .nc-date -->

	</div><!-- .nc-twitter-preview -->

</script><!-- #_nc-twitter-preview -->
