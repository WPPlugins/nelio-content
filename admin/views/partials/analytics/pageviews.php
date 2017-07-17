<?php
/**
 * The template of pageviews for a post.
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
	esc_attr_x( 'Total pageviews and traffic driven by each social network according to Google Analytics.', 'text', 'nelio-content' ),
	$img
);

?>

<div class="nc-top-post-views nc-top-post-cell">

	<div class="nc-top-post-metric"><?php
		echo esc_html_x( 'Pageviews', 'text', 'nelio-content');
		if ( get_option( 'nc_ga_token_error' ) ) {
			printf(
				' <span class="nc-help-with-html-tooltip nc-dashicons nc-dashicons-warning" style="color:#dd3d36;" title="%s"></span>',
				esc_attr_x( 'Google Analytics tokens expired. Please, reconnect your account in Settings.', 'user', 'nelio-content' )
			);
		} else {
			echo ' ' . $label;
		}//end if
	?></div>
	<div class="nc-analytics-count"><%= statistics.pageviews.totalHuman %></div>

	<div class="nc-analytics-network-values">
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-twitter"></div><%= statistics.pageviews.twitterHuman %><span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-facebook"></div><%= statistics.pageviews.facebookHuman %><span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-linkedin"></div><%= statistics.pageviews.linkedinHuman %><span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-googleplus"></div><%= statistics.pageviews.googleplusHuman %><span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-pinterest"></div><%= statistics.pageviews.pinterestHuman %><span class="nc-analytics-percent">%</span>
		</div>
	</div><!-- .nc-network-values -->

</div><!-- .nc-top-post-views -->
