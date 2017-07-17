<?php
/**
 * This template is used for rendering a broken link.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/broken-links
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

<script type="text/template" id="_nc-broken-link">

	<div class="nc-broken-link nc-<%= status %>">

		<% if ( 'checking' === status ) { %>

			<div class="nc-status">
				<span class="spinner is-active" title="<?php
					echo esc_attr_x( 'Checking link&hellip;', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } else { %>

			<div class="nc-status">
				<span class="nc-dashicons nc-dashicons-warning" title="<?php
					echo esc_attr_x( 'Broken Link', 'text', 'nelio-content' );
				?>"></span>
			</div><!-- .nc-status -->

		<% } %>

		<div class="nc-information">

			<div class="nc-url nc-no-title">
				<a href="<%= urlEscaped %>" target="_blank"><%= urlEscaped %></a>
			</div><!-- .nc-url -->

			<% if ( 'checking' === status ) { %>

				<div class="nc-explanation"><?php
					echo esc_html_x( 'Checking link&hellip;', 'text', 'nelio-content' );
				?></div>

			<% } %>

			<div class="nc-actions">
				<span class="nc-dashicons nc-dashicons-edit" title="<?php
					echo esc_attr_x( 'Edit', 'command', 'nelio-content' );
				?>"></span>
				<% if ( isInPostContent ) { %>
					<span class="nc-dashicons nc-dashicons-trash" title="<?php
						echo esc_attr_x( 'Delete from this post', 'command', 'nelio-content' );
					?>"></span>
				<% } else { %>
					<span class="nc-dashicons nc-dashicons-trash" title="<?php
						echo esc_attr_x( 'Discard', 'command', 'nelio-content' );
					?>"></span>
				<% } %>
			</div><!-- .nc-actions -->

		</div><!-- .nc-information -->

	</div><!-- .nc-broken-link -->

</script><!-- #_nc-broken-link -->
