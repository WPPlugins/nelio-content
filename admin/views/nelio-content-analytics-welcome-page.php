<?php
/**
 * This file contains the analytics welcome page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

switch ( get_user_option( 'admin_color' ) ) {

	case 'light':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/light.png' );
		break;

	case 'blue':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/blue.png' );
		break;

	case 'coffee':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/coffee.png' );
		break;

	case 'ectoplasm':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/ectoplasm.png' );
		break;

	case 'midnight':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/midnight.png' );
		break;

	case 'ocean':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/ocean.png' );
		break;

	case 'sunrise':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/sunrise.png' );
		break;

	default:
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/default.png' );

}//end switch

$spinner = '<span class="dashicons dashicons-update nc-animate-spinner"></span> ';

$settings = Nelio_Content_Settings::instance();
$is_analytics_enabled = $settings->get( 'use_analytics' );
$google_analytics_view = $settings->get( 'google_analytics_view' );
$is_ga_configured = ! empty( $google_analytics_view );

$analytics = Nelio_Content_Analytics_Helper::instance();
$posts = $analytics->get_posts( array( 'posts_per_page' => 101 ) );
$ids = array();
foreach ( $posts as $post ) {
	array_push( $ids, $post['id'] );
}//end foreach

if ( count( $posts ) === 101 ) {
	array_pop( $ids );
}//end if

?>

<div class="wrap nc-analytics-welcome">

	<div class="nc-analytics-welcome-message">

		<img src="<?php
			echo $image_url; // @codingStandardsIgnoreLine
		?>" title="<?php
			echo esc_attr_x( 'Nelio Content Logo', 'text', 'nelio-content' );
		?>" />

		<div class="nc-plugin-moto"><?php
			echo _x( 'Analytics offers useful information about traffic&nbsp;acquisition and content&nbsp;engagement.', 'user', 'nelio-content' ); // @codingStandardsIgnoreLine
		?></div>


		<div class="nc-enable-analytics"<?php if ( $is_analytics_enabled ) echo ' style="display:none;"'; ?>>

			<br>
			<button class="button button-primary button-hero nc-enable-analytics"><?php
				echo esc_html_x( 'Enable Analytics', 'command', 'nelio-content' );
			?></button>

		</div><!-- .nc-enable-analytics -->


		<div class="nc-maybe-use-ga"<?php if ( ! $is_analytics_enabled || $is_ga_configured ) echo ' style="display:none;"'; ?>>

			<p class="nc-message"><?php
				echo _x( 'Get better insights with <strong>Google Analytics</strong> data.', 'user', 'nelio-content' ); // @codingStandardsIgnoreLine
			?></p>

			<button class="button button-hero nc-skip-ga"><?php
				echo esc_html_x( 'Skip', 'command', 'nelio-content' );
			?></button>

			<button class="button button-primary button-hero nc-enable-ga"><?php
				echo esc_html_x( 'Connect Google Analytics', 'command', 'nelio-content' );
			?></button>

		</div><!-- .nc-maybe-use-ga -->


		<div class="nc-compute-analytics"<?php if ( ! $is_analytics_enabled || ! $is_ga_configured ) echo ' style="display:none;"'; ?>>

			<p class="nc-message"><?php
				echo _x( '<strong>Analytics</strong> is almost ready!', 'user', 'nelio-content' ); // @codingStandardsIgnoreLine
			?></p>

			<button class="button button-primary button-hero nc-compute-analytics"><?php
				echo esc_html_x( 'Compute Analytics Now!', 'command', 'nelio-content' );
			?></button>

		</div><!-- .nc-compute-analytics -->


		<div class="nc-computing-analytics" style="display:none;">

			<p class="nc-message"><?php
				if ( count( $posts ) <= 100 ) {
					echo $spinner . _x( 'Computing analytics of all your posts&hellip;', 'user', 'nelio-content' );
				} else {
					$count = count( $ids );
					echo $spinner . sprintf(
						_nx( 'Computing analytics of your latest post&hellip;', 'Computing analytics of your %d latest posts&hellip;', 'user', 'nelio-content' ),
						$count
					);
				}//end if
			?></p>

			<?php include( NELIO_CONTENT_ADMIN_DIR . '/views/partials/progress-bar.php' ); ?>

		</div><!-- .nc-computing-analytics -->

	</div><!-- .nc-analytics-welcome-message -->

	<script type="text/javascript">
	(function( $ ) {

		var posts = <?php echo wp_json_encode( $ids ) ?>;
		var totalItems = posts.length;
		var processedItems = 1;

		var $enableAnalyticsSection = $( 'div.nc-enable-analytics' );
		var $gaSection = $( 'div.nc-maybe-use-ga' );
		var $computeSection = $( 'div.nc-compute-analytics' );
		var $computingSection = $( 'div.nc-computing-analytics' );

		var $enableAnalyticsButton = $( '.button.nc-enable-analytics' );
		var $skipGAButton = $( '.button.nc-skip-ga' );
		var $enableGAButton = $( '.button.nc-enable-ga' );
		var $computeAnalyticsButton = $( '.button.nc-compute-analytics' );
		var $progress = $( '.nc-progress-bar' );

		function computeAnalytics() {

			if ( 0 === posts.length ) {
				commitChangesAndRefresh();
				return;
			}//end if

			var requests = [];
			var count = Math.min( posts.length, 10 );
			for ( var i = 0; i < count; ++i ) {
				requests.push( $.ajax({
					url: ajaxurl,
					data: {
						action: 'nelio_content_update_analytics',
						post: posts.shift()
					},
					complete: function() {
						++processedItems;
						var perc = Math.round( 100 * processedItems / totalItems );
						$progress.css( 'width', Math.min( 100, perc ) + '%' );
					}//end complete()
				}) );
			}//end for

			$.when.apply( this, requests ).always( computeAnalytics );

		}//end computeAnalytics()

		function commitChangesAndRefresh() {
			$.ajax({
				url: ajaxurl,
				data: {
					action: 'nelio_content_update_analytics_last_global_update'
				},
				complete: function() {
					window.location.reload();
				}
			});
		}//end commitChangesAndRefresh()

		$enableAnalyticsButton.on( 'click', function() {

			$enableAnalyticsButton.html( <?php echo wp_json_encode( $spinner . _x( 'Enabling&hellip;', 'text', 'nelio-content' ) ); ?> );
			$enableAnalyticsButton.prop( 'disabled', true );

			$.ajax({
				url: ajaxurl,
				data: {
					action: 'nelio_content_enable_analytics'
				},
				success: function() {
					$enableAnalyticsSection.hide();
					$gaSection.show();
				},
				error: function() {
					NelioContent.helpers.openErrorDialog( <?php echo wp_json_encode( sprintf(
						_x( 'An error occurred while enabling Nelio Content Analytics. If the error persists, please <a href="%s">contact our support team</a>.', 'command', 'nelio-content' ),
						'mailto:support.content@neliosoftware.com'
					) ); ?> );
					$enableAnalyticsButton.html( <?php echo wp_json_encode( _x( 'Enable Analytics', 'command', 'nelio-content' ) ); ?> );
					$enableAnalyticsButton.prop( 'disabled', false );
				},
			});

		});

		$skipGAButton.on( 'click', function() {
			$skipGAButton.prop( 'disabled', true );
			$enableGAButton.prop( 'disabled', true );
			setTimeout( function() {
				$gaSection.hide();
				$computeSection.show();
			}, 500 );
		});

		$enableGAButton.on( 'click', function() {
			$skipGAButton.prop( 'disabled', true );
			$enableGAButton.prop( 'disabled', true );
			window.location.href = <?php echo wp_json_encode( admin_url( '/admin.php?page=nelio-content-settings&tab=content' ) ); ?>;
		});

		$computeAnalyticsButton.on( 'click', function() {
			$computeAnalyticsButton.prop( 'disabled', true );
			setTimeout( function() {
				$computeSection.hide();
				$computingSection.show();
				computeAnalytics();
			}, 200 );
		});

		$( document ).ready( function() {
			$( '#wpwrap' ).addClass( 'nc-welcome' );
		});

	})( jQuery );
	</script>

</div><!-- .wrap -->
