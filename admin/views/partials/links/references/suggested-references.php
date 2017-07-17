<?php
/**
 * This partial contains the list of suggested references.
 *
 * Presumably used in the Links meta box.
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

<script type="text/template" id="_nc-suggested-references">

	<div class="nc-references">

		<h3><?php
			echo esc_html_x( 'Suggested References', 'text (references)', 'nelio-content' );
		?></h3>

		<div class="nc-list">

			<div class="nc-no-references">
				<% if ( amITheAuthor ) { %>
					<?php
						echo esc_html_x( 'Do you have an interesting link for writing this post? Save it here!', 'user', 'nelio-content' );
					?>
				<% } else { %>
					<?php
						echo esc_html_x( 'Suggest one or more links to the author and contribute to the creation of better content.', 'user', 'nelio-content' );
					?>
				<% } %>
			</div><!-- .nc-empty-list -->

		</div><!-- .nc-list -->

	</div><!-- .nc-references -->

	<div class="nc-suggest-reference">

		<div class="nc-reference-url">
			<% if ( amITheAuthor ) { %>
				<input type="url" placeholder="<?php echo esc_attr_x( 'Enter a URL&hellip;', 'user', 'nelio-content' ); ?>" />
			<% } else { %>
				<input type="url" placeholder="<?php echo esc_attr_x( 'Suggest a URL to the author&hellip;', 'user', 'nelio-content' ); ?>" />
			<% } %>
		</div><!-- .nc-reference-url -->

		<div class="nc-actions">
			<% if ( amITheAuthor ) { %>
				<a class="button button-disabled" tabindex="0"><?php echo esc_html_x( 'Save for later', 'command (reference)', 'nelio-content' ); ?></a>
			<% } else { %>
				<a class="button button-disabled" tabindex="0"><?php echo esc_html_x( 'Suggest', 'command (reference)', 'nelio-content' ); ?></a>
			<% } %>
		</div><!-- .nc-actions -->

	</div><!-- .nc-suggest-reference -->

</script><!-- #_nc-suggested-references -->

