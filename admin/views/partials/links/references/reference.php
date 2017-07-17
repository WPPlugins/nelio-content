<?php
/**
 * This template is used for rendering a single reference.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/references
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
}//end if

?>

<script type="text/template" id="_nc-reference">

	<div class="nc-reference nc-<%= status %><% if ( isInPostContent ) { %> nc-included<% } %>">

		<% if ( 'pending' === status ) { %>

			<div class="nc-status">
				<span class="spinner is-active" title="<?php
					echo esc_attr_x( 'Automatically retrieving data&hellip;', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } else if ( 'check' === status ) { %>

			<div class="nc-status">
				<span class="spinner is-active" title="<?php
					echo esc_attr_x( 'Checking link&hellip;', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } else if ( '_discarding' === status ) { %>

			<div class="nc-status">
				<span class="spinner is-active" title="<?php
					echo esc_attr_x( 'Discarding link&hellip;', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } else if ( 'broken' === status ) { %>

			<div class="nc-status">
				<span class="nc-dashicons nc-dashicons-warning" title="<?php
					echo esc_attr_x( 'Broken Link', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } else { %>

			<% if ( isInPostContent ) { %>

				<div class="nc-status">
					<span class="nc-bullet-filled" title="<?php
						echo esc_attr_x( 'Reference included in post', 'text', 'nelio-content' );
					?>"></span>
				</div><!-- .nc-status -->

			<% } else { %>

				<div class="nc-status">
					<span class="nc-bullet-empty" title="<?php
						echo esc_attr_x( 'Post doesn\'t contain this reference', 'text', 'nelio-content' );
					?>"></span>
				</div><!-- .nc-status -->

			<% } %>

		<% } %>

		<div class="nc-information">

			<% if ( titleEscaped.length > 0 ) { %>

				<div class="nc-title"><%= titleEscaped %></div>
				<div class="nc-url">
					<a href="<%= urlEscaped %>" target="_blank"><%= urlEscaped %></a>
				</div><!-- .nc-url -->

			<% } else { %>

				<div class="nc-url nc-no-title">
					<a href="<%= urlEscaped %>" target="_blank"><%= urlEscaped %></a>
				</div><!-- .nc-url -->

			<% } %>

			<% if ( 'complete' === status || 'improvable' === status ) { %>

				<div class="nc-author-and-date">

					<% if ( authorEscaped.length > 0 ) { %>
						<span class="nc-author"><%= authorEscaped %></span>
					<% } %>

					<% if ( ( authorEscaped.length > 0 ) &&
					        ( twitter.length > 0 || email.length > 0 ) ) { %>
						•
					<% } %>

					<% if ( twitter.length > 0 ) { %>
						<a class="nc-twitter" title="<%= twitter %>" href="https://twitter.com/<%= _.escape( twitter ) %>" target="_blank">
							<span class="nc-dashicons nc-dashicons-twitter"></span></a>
					<% } %>

					<% if ( email.length > 0 ) { %>
						<a class="nc-email" href="mailto:<%= email %>">
							<span email="<%= email %>" class="nc-email nc-dashicons nc-dashicons-email"></span></a>
					<% } %>

				</div><!-- .nc-author-and-date -->

			<% } %>

			<% if ( 'pending' === status ) { %>

				<div class="nc-explanation"><?php
					echo esc_html_x( 'Automatically retrieving data&hellip;', 'text', 'nelio-content' );
				?></div>

			<% } else if ( 'checking' === status ) { %>

				<div class="nc-explanation"><?php
					echo esc_html_x( 'Checking link&hellip;', 'text', 'nelio-content' );
				?></div>

			<% } else if ( '_discarding' === status ) { %>

				<div class="nc-explanation"><?php
					echo esc_html_x( 'Discarding link&hellip;', 'text', 'nelio-content' );
				?></div>

			<% } else { %>

				<% if ( wasSuggestedByMe ) { %>

					<div class="nc-explanation"><?php
						echo esc_html_x( 'Suggested by you', 'text (reference)', 'nelio-content' );
					?></div>

				<% } else { %>

					<div class="nc-explanation"><?php
						printf( // @codingStandardsIgnoreLine
							_x( 'Suggested by %s', 'text (reference)', 'nelio-content' ),
							'<%= suggestionAdvisorDisplayName %>'
						);
					?></div>

				<% } %>

			<% } %>

			<% if ( awaitingDiscardConfirmation ) { %>

				<div class="nc-actions">

					<span class="nc-delete-confirmation-label"><?php
						echo esc_html_x( 'Are you sure?', 'user', 'nelio-content' );
					?></span>
					<span class="nc-dashicons nc-dashicons-yes nc-do-discard" title="<?php
						echo esc_attr_x( 'Yes, Discard It', 'command (reference)', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-discard" title="<?php
						echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
					?>"></span>

				</div><!-- .nc-actions -->

			<%
			} else if ( 'pending' !== status && 'check' !== status && '_discarding' !== status ) { %>

				<div class="nc-actions">

					<?php
					if ( nc_is_current_user( 'author' ) ) { ?>

						<span class="nc-edit nc-dashicons nc-dashicons-edit" title="<?php
							echo esc_attr_x( 'Edit', 'command (reference)', 'nelio-content' );
						?>"></span>

					<?php
					} ?>

					<?php
					if ( nc_is_current_user( 'editor' ) ) { ?>
						<span class="nc-discard nc-dashicons nc-dashicons-hidden" title="<?php
							echo esc_attr_x( 'Discard', 'command (reference)', 'nelio-content' );
						?>"></span>
					<?php
					} else { ?>
						<% if ( wasSuggestedByMe || wasSuggestedByNelio ) { %>
							<span class="nc-discard nc-dashicons nc-dashicons-hidden" title="<?php
								echo esc_attr_x( 'Discard', 'command (reference)', 'nelio-content' );
							?>"></span>
						<% } %>
					<?php
					} ?>

				</div><!-- .nc-actions -->

			<%
			} %>

		</div><!-- .nc-information -->

	</div><!-- .nc-reference -->

</script><!-- #_nc-reference -->

