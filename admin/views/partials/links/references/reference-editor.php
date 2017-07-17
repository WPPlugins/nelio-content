<?php
/**
 * This template contains the form for editing an refernce.
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

<script type="text/template" id="_nc-reference-editor">

	<div class="nc-reference-editor<% if ( ! isExternal ) { %> nc-local-reference<% } %>">

		<% if ( ! isExternal ) { %>
			<div class="nc-edit-is-locked"><span class="nc-dashicons nc-dashicons-lock"></span> <?php
				echo esc_html_x( 'Internal references cannot be edited.', 'text', 'nelio-content' );
			?></div>
		<% } %>

		<div class="nc-title">
			<input class="nc-title" name="nc-title" type="text" title="<?php
				echo esc_attr_x( 'Reference\'s Title', 'text', 'nelio-content' );
			?>"<% if ( ! isExternal ) { %>disabled="disabled"<% } else { %>placeholder="<?php
				echo esc_attr_x( 'Enter the title&hellip;', 'user', 'nelio-content' );
			?>"<% } %> value="<%= titleEscaped %>" />
		</div><!-- .nc-title -->

		<% if ( 'broken' === status ) { %>
			<div class="nc-url">
				<label for="nc-url"><?php echo esc_html_x( 'URL', 'text', 'nelio-content' ); ?></label>
				<input class="nc-url" name="nc-url" type="url" <% if ( ! isExternal ) { %>disabled="disabled"<% } else { %>placeholder="<?php
					echo esc_attr_x( 'Enter a valid, working URL&hellip;', 'user', 'nelio-content' );
				?>"<% } %> value="<%= urlEscaped %>" />
			</div><!-- .nc-url -->
		<% } else { %>
			<div class="nc-url nc-non-editable">
				<strong><?php
					echo esc_html_x( 'URL:', 'text', 'nelio-content' );
				?></strong> <a href="<%= url %>" target="blank"><%= urlEscaped %></a>
			</div><!-- .nc-url -->
		<% } %>

		<h2><?php echo esc_html_x( 'Author Information', 'text', 'nelio-content' ); ?></h2>

		<div class="nc-author">
			<label for="nc-author"><?php echo esc_html_x( 'Name', 'text', 'nelio-content' ); ?></label>
			<input class="nc-author" name="nc-author" type="text" <% if ( ! isExternal ) { %>disabled="disabled"<% } else { %>placeholder="<?php
				echo esc_attr_x( 'Name Surname', 'text', 'nelio-content' );
			?>"<% } %> value="<%= authorEscaped %>" />
		</div><!-- .nc-author -->

		<div class="nc-email">
			<label for="nc-email"><?php echo esc_html_x( 'Email', 'text', 'nelio-content' ); ?></label>
			<input class="nc-email" name="nc-email" type="text" <% if ( ! isExternal ) { %>disabled="disabled"<% } else { %>placeholder="<?php
				echo esc_attr_x( 'author@example.com', 'text', 'nelio-content' );
			?>"<% } %> value="<%= email %>" />
		</div><!-- .nc-email -->

		<div class="nc-twitter">
			<label for="nc-twitter"><?php echo esc_html_x( 'Twitter', 'text', 'nelio-content' ); ?></label>
			<input class="nc-twitter" name="nc-twitter" type="text" <% if ( ! isExternal ) { %>disabled="disabled"<% } else { %>placeholder="<?php
				echo esc_attr_x( '@username', 'text', 'nelio-content' );
			?>"<% } %> value="<%= twitter %>" />
		</div><!-- .nc-twitter -->

	</div><!-- .nc-reference-editor -->

</script><!-- #_nc-reference-editor -->

