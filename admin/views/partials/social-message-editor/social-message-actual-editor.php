<?php
/**
 * This partial is the skeletton of the social message editor.
 *
 * It allows users to create social messages, select the social profiles where
 * those messages will be shared, and preview them using the look and feel of
 * all social networks.
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

<script type="text/template" id="_nc-social-message-actual-editor">

	<div class="nc-the-editor">

		<textarea autofocus="autofocus" placeholder="<?php
			echo esc_attr_x( 'Your status update&hellip;', 'user', 'nelio-content' );
		?>"><%= text %></textarea>

		<div class="nc-char-count">0</div>

	</div><!-- .nc-the-editor -->

	<% if ( 'hide-related-post' === relatedPostMode ) { %>

		<div class="nc-information">

	<% } else { %>

		<div class="nc-information nc-related-post-stuff-visible">

	<% } %>

		<?php
		// Related post mode is only available in the calendar page.
		$screen = get_current_screen();
		if ( 'toplevel_page_nelio-content' === $screen->id ) { ?>

			<% if ( 'show-post-selector-action' === relatedPostMode ) { %>

				<div class="nc-post-selector">
					<a href="#"><?php
						echo esc_html_x( 'Share old post&hellip;', 'user', 'nelio-content' );
					?></a>
				</div><!-- .nc-post-selector -->

			<% } else if ( 'show-post-selector' === relatedPostMode ) { %>

				<div class="nc-post-selector-container"></div>

			<% } else if ( 'show-related-post' === relatedPostMode ) { %>

				<div class="nc-related-post">

					<strong><?php echo esc_html_x( 'Related Post:', 'text', 'nelio-content' ); ?></strong>
					<% if ( postEditLink.length > 0 ) { %>
						<a href="<%= postEditLink %>" target="_blank"><%= postTitleFormatted %></a>
					<% } else { %>
						<%= postTitleFormatted %>
					<% } %>

				</div><!-- .nc-related-post -->

			<% } else if ( 'no-related-post' === relatedPostMode ) { %>

				<div class="nc-related-post nc-no-related-post"><?php
					echo esc_html_x( 'This message doesn\'t have any related post.', 'text', 'nelio-content' );
				?></div><!-- .nc-related-post -->

			<% } %>

		<?php
		} ?>

		<div class="nc-actions">

			<% if ( awaitingDeletionConfirmation ) { %>

				<span class="nc-delete-confirmation-label"><?php
					echo esc_html_x( 'Are you sure?', 'user', 'nelio-content' );
				?></span>
				<span class="nc-dashicons nc-dashicons-yes nc-do-remove" title="<?php
					echo esc_attr_x( 'Yes, Remove Image', 'command (shared image)', 'nelio-content' );
				?>"></span>
				<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-remove" title="<?php
					echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
				?>"></span>

			<% } else { %>

				<% if ( 'image' === type ) { %>

					<span class="nc-action nc-remove nc-image nc-active" title="<?php
						echo esc_attr_x( 'Remove Image', 'command', 'nelio-content' );
					?>"></span>

				<% } else { %>

					<span class="nc-action nc-add nc-image" title="<?php
						echo esc_attr_x( 'Add Image', 'command', 'nelio-content' );
					?>"></span>

				<% } %>

			<% } %>

			<%
			if ( isPreviewVisible ) { %>
				<span class="nc-action nc-preview nc-hide" title="<?php
					echo esc_attr_x( 'Hide Preview', 'command', 'nelio-content' );
				?>"></span>
			<% } else { %>
				<span class="nc-action nc-preview nc-show" title="<?php
					echo esc_attr_x( 'Show Preview', 'command', 'nelio-content' );
				?>"></span>
			<% } %>


		</div><!-- .nc-actions -->

	</div><!-- .nc-information -->

</script>

