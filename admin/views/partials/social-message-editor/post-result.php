<?php
/**
 * This partial is used for drawing a post in a ncselect2 result set.
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

<script type="text/template" id="_nc-post-result">

	<div class="ncselect2-result nc-post-result">

		<div class="nc-featured-image">

			<div class="nc-thumbnail" style="background-image:url( <%= _.escape( thumbnail ) %> );"></div>

		</div><!-- .nc-featured-image -->

		<div class="nc-information">

			<div class="nc-title"><%= titleFormatted %></div>

			<div class="nc-extra">
				<?php
				printf( // @codingStandardsIgnoreLine
					_x( '%1$s by %2$s • %3$s', 'text ({post type} by {author name} • {date})', 'nelio-content' ),
					'<span class="nc-type"><%= _.escape( typeName ) %></span>',
					'<span class="nc-author"><%= authorNameFormatted %></span>',
					'<span class="nc-date"><%= _.escape( ncNewLocalMoment( date ).format( NelioContent.i18n.date.default ) ) %></span>'
				);
				?>
			</div><!-- .nc-extra -->

		</div><!-- .nc-information -->

	</div><!-- .ncselect2-result.post-result -->

</script><!-- #_nc-post-result -->

