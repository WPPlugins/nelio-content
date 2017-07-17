<?php
/**
 * The underscore template for rendering a single editorial comment.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-comments
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
<script type="text/template" id="_nc-editorial-comment">

	<div class="nc-editorial-comment<%
		if ( isAuthorMe ) {
			%> nc-author-me<%
		} else {
			%> nc-author-other<%
		}

		if ( isBeingSaved ) {
			%> nc-saving<%
		}
		%>">

		<% if ( ! isAuthorMe ) { %>

			<div class="nc-profile"
				title="<%= _.escape( displayName ) %>">

				<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
					<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
				</div><!-- .nc-picture -->

			</div><!-- .nc-profile -->

		<% } %>

		<div class="nc-message-author-and-date">

			<div class="nc-pointer"></div>

			<div class="nc-message">
				<%= _.escape( comment ) %>
			</div><!-- .nc-message -->

			<% if ( isBeingSaved ) { %>

				<div class="nc-author-and-date"><?php
					echo esc_html_x( 'Sending&hellip;', 'text (saving editorial comment)', 'nelio-content' );
				?></div><!-- .nc-author-and-date -->

			<% } else if ( 'deleting' === deletionStatus ) { %>

				<div class="nc-author-and-date"><?php
					echo esc_html_x( 'Deleting&hellip;', 'text (comment)', 'nelio-content' );
				?></div>

			<% } else if ( 'awaiting-confirmation' === deletionStatus ) { %>

				<div class="nc-author-and-date">

					<span class="nc-delete-confirmation-label"><?php
						esc_html_e( 'Are you sure?', 'nelio-content' );
					?></span>

					<span class="nc-dashicons nc-dashicons-yes nc-action nc-do-delete" title="<?php
						echo esc_attr_x( 'Yes, Delete It', 'command (comment)', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-no-alt nc-action nc-cancel-deletion" title="<?php
						echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
					?>"></span>

				</div><!-- .nc-author-and-date -->

			<% } else { %>

				<div class="nc-author-and-date">

					<% if ( ! isAuthorMe ) { %>

						<span class="nc-author"><%= _.escape( displayName ) %></span> •
						<span class="nc-date"><%= dateFormatted %></span>

					<% } else { %>

						<% if ( canBeDeleted ) { %>
							<span class="nc-delete-wrapper"><span class="nc-action nc-delete"><?php
								echo esc_html_x( 'Delete', 'command', 'nelio-content' );
							?></span>&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;</span>
						<% } %>

						<span class="nc-date"><%= dateFormatted %></span>

					<% } %>

				</div><!-- .nc-author-and-date -->

			<% } %>

		</div><!-- .nc-message-author-and-date -->

	</div><!-- .nc-editorial-comment -->

</script><!-- #_nc-editorial-comment -->

