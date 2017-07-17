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

?>

<div class="nc-top-post-views nc-top-post-cell nc-not-available">

	<div class="nc-top-post-metric"><?php echo esc_html_x( 'Pageviews', 'text', 'nelio-content'); ?> </div>
	<div class="nc-analytics-count">&ndash;<div class="nc-configure-ga"><a class="button" href="<?php echo admin_url( 'admin.php?page=nelio-content-settings&tab=content' ); ?>"><?php echo esc_html_x( 'Connect Google Analytics', 'user', 'nelio-content'); ?></a></div></div>

	<div class="nc-analytics-network-values">
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-twitter"></div>&ndash;<span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-facebook"></div>&ndash;<span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-linkedin"></div>&ndash;<span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-googleplus"></div>&ndash;<span class="nc-analytics-percent">%</span>
		</div>
		<div class="nc-analytics-network-value">
			<div class="nc-analytics-icon nc-pinterest"></div>&ndash;<span class="nc-analytics-percent">%</span>
		</div>
	</div><!-- .nc-network-values -->

</div><!-- .nc-top-post-views -->
