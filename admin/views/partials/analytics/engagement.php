<?php
/**
 * The template of engagement for a post.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/analytics
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$img = NELIO_CONTENT_INCLUDES_URL . '/lib/settings/assets/images/help.png';
$label = sprintf(
	'<img title="%s" class="nc-help-with-html-tooltip" style="margin-right:-15px;cursor:pointer;vertical-align:bottom;" src="%s" height="16" width="16" />',
	esc_attr_x( 'Number of interactions (likes, shares, &hellip;) on social networks and comments in WordPress.', 'text', 'nelio-content' ),
	$img
);

?>

<div class="nc-top-post-statistics nc-top-post-cell">

	<div class="nc-top-post-metric"><?php echo esc_html_x( 'Engagement', 'text', 'nelio-content'); ?> <?php echo $label; ?></div>
	<div class="nc-analytics-count"><%= statistics.engagement.totalHuman %></div>

	<div class="nc-analytics-network-values">
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-facebook"></div><%= statistics.engagement.facebookHuman %>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-linkedin"></div><%= statistics.engagement.linkedinHuman %>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-googleplus"></div><%= statistics.engagement.googleplusHuman %>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-pinterest"></div><%= statistics.engagement.pinterestHuman %>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-comment"></div><%= statistics.engagement.commentsHuman %>
		</div>
	</div><!-- .nc-network-values -->

</div><!-- .nc-top-post-statistics -->
