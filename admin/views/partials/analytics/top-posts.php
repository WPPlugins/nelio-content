<?php
/**
 * The underscore template for rendering a list of top posts.
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

<script type="text/template" id="_nc-top-posts">

	<h1>
		<?php include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-notice.php' ); ?>
		<?php echo esc_html_x( 'Analytics', 'title', 'nelio-content' ); ?>
		<span class="spinner is-active" style="float:none; margin-top:-5px; display:inline-block;"></span>
	</h1>

	<?php
	$settings = Nelio_Content_Settings::instance();
	$ga_view = $settings->get( 'google_analytics_view' );
	if ( ! empty( $ga_view ) ) { ?>
		<p class="nc-metric-sorter">
			<span class="nc-current-metric"></span>
			<a href="#" class="nc-new-metric"></a>
		</p>
	<?php
	} ?>

	<div class="nc-post-filters">

		<span class="nc-date-filter"></span>

		<?php
		if ( ! nc_is_current_user( 'author', 'exactly' ) ) { ?>
			<span class="nc-author-filter"></span>
		<?php
		} ?>

		<?php include NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/post-filter.php'; ?>

		<button class="button nc-apply-filters"><?php echo esc_html_x( 'Apply', 'command', 'nelio-content' ); ?></button>

	</div><!-- .nc-post-filters -->

	<div class="nc-top-posts-none" style="display:none;">
		<span class="nc-dashicons nc-dashicons-info"></span>
		<?php _e( 'No post matched your criteria', 'text', 'nelio-content'); ?>
	</div>
	<div class="nc-top-posts"></div>

</script><!-- #_nc-top-posts -->
