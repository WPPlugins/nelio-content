<?php
/**
 * This partial represents the social timeline of shcheduled messages.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-timeline
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

<script type="text/template" id="_nc-social-timeline">

	<% if ( isPostPublished ) { %>

		<h3><?php echo esc_html_x( 'Analytics', 'title', 'nelio-content' ); ?></h3>
		<div class="nc-analytics-container">
			<?php
				$settings = Nelio_Content_Settings::instance();
				$ga_view = $settings->get( 'google_analytics_view' );
				$is_ga_configured = ! empty( $ga_view );

				if ( $is_ga_configured ) {
					include NELIO_CONTENT_ADMIN_DIR . '/views/partials/analytics/pageviews.php';
				} else {
					include NELIO_CONTENT_ADMIN_DIR . '/views/partials/analytics/pageviews-not-configured.php';
				}
				include NELIO_CONTENT_ADMIN_DIR . '/views/partials/analytics/engagement.php';
			?>
		</div>

	<% } %>

	<?php
	if ( nc_is_free_trial() ) { ?>
		<div style="text-align: center; padding: 1em 0;">
			<?php include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/free-trial-notice.php'; ?>
		</div>
	<?php
	} ?>

	<h3><?php echo esc_html_x( 'Scheduled Messages', 'title', 'nelio-content' ); ?></h3>

	<div id="timeline-start"></div>

	<div class="nc-timeline-overview-wrapper">
		<div class="nc-timeline-overview">

			<div class="nc-sections">

				<div class="nc-section nc-day nc-<%= dayStatus %>">
					<div class="nc-status-container">
						<div class="nc-status"></div>
					</div><!-- .nc-status-container -->
					<div class="nc-label">
						<% if ( isPostPublished ) { %>
							<?php echo esc_html_x( 'Today', 'text (timeline summary)', 'nelio-content' ); ?>
						<% } else { %>
							<?php echo esc_html_x( 'Publication', 'text (timeline summary)', 'nelio-content' ); ?>
						<% } %>
						<span class="nc-visual-clue nc-<%= dayStatus %>"><%
						if ( 'good' === dayStatus ) { %>✓✓<% }
						else if ( 'improvable' === dayStatus ) { %>✓<% }
						%></span><!-- .nc-visual-clue -->
					</div><!-- .nc-label -->
				</div><!-- .nc-day -->

				<div class="nc-section nc-next-day nc-<%= nextDayStatus %>">
					<div class="nc-status-container">
						<div class="nc-status"></div>
					</div><!-- .nc-status-container -->
					<div class="nc-label">
						<% if ( isPostPublished ) { %>
							<?php echo esc_html_x( 'Tomorrow', 'text (timeline summary)', 'nelio-content' ); ?>
						<% } else { %>
							<?php echo esc_html_x( 'Next Day', 'text (timeline summary)', 'nelio-content' ); ?>
						<% } %>
						<span class="nc-visual-clue nc-<%= nextDayStatus %>"><%
						if ( 'good' === nextDayStatus ) { %>✓✓<% }
						else if ( 'improvable' === nextDayStatus ) { %>✓<% }
						%></span><!-- .nc-visual-clue -->
					</div><!-- .nc-label -->
				</div><!-- .nc-next-day -->

				<div class="nc-section nc-week nc-<%= weekStatus %>">
					<div class="nc-status-container">
						<div class="nc-status"></div>
					</div><!-- .nc-status-container -->
					<div class="nc-label">
						<?php echo esc_html_x( 'Week', 'text (timeline summary)', 'nelio-content' ); ?>
						<span class="nc-visual-clue nc-<%= weekStatus %>"><%
						if ( 'good' === weekStatus ) { %>✓✓<% }
						else if ( 'improvable' === weekStatus ) { %>✓<% }
						%></span><!-- .nc-visual-clue -->
					</div><!-- .nc-label -->
				</div><!-- .nc-week -->

				<div class="nc-section nc-month nc-<%= monthStatus %>">
					<div class="nc-status-container">
						<div class="nc-status"></div>
					</div><!-- .nc-status-container -->
					<div class="nc-label">
						<?php echo esc_html_x( 'Month', 'text (timeline summary)', 'nelio-content' ); ?>
						<span class="nc-visual-clue nc-<%= monthStatus %>"><%
						if ( 'good' === monthStatus ) { %>✓✓<% }
						else if ( 'improvable' === monthStatus ) { %>✓<% }
						%></span><!-- .nc-visual-clue -->
					</div><!-- .nc-label -->
				</div><!-- .nc-month -->

				<div class="nc-section nc-later nc-<%= laterStatus %>">
					<div class="nc-status-container">
						<div class="nc-status"></div>
					</div><!-- .nc-status-container -->
					<div class="nc-label">
						<?php echo esc_html_x( 'Other', 'text (timeline summary)', 'nelio-content' ); ?>
						<span class="nc-visual-clue nc-<%= laterStatus %>"><%
						if ( 'good' === laterStatus ) { %>✓✓<% }
						else if ( 'improvable' === laterStatus ) { %>✓<% }
						%></span><!-- .nc-visual-clue -->
					</div><!-- .nc-label -->
				</div><!-- .nc-later -->

			</div><!-- .nc-sections -->

		</div><!-- .nc-timeline-overview -->
	</div><!-- .nc-timeline-overview-wrapper -->

	<div class="nc-timeline-sections">

		<?php
		$block_name = 'day';
		$h4_class = '<% if ( dayFormattedDate.length > 0 ) { %> class="nc-has-date-detail"<% } %>';
		$regular_title = _x( 'Today', 'text (timeline)', 'nelio-content' ) .
			' <span class="nc-date"><%= dayFormattedDate %></span>';
		$publication_title = _x( 'Publication Day', 'text (timeline)', 'nelio-content' ) .
			' <span class="nc-date"><%= dayFormattedDate %></span>';
		$status_var_name = 'dayStatus';
		include 'social-timeline-block.php';
		?>

		<?php
		$block_name = 'next-day';
		$h4_class = '<% if ( nextDayFormattedDate.length > 0 ) { %> class="nc-has-date-detail"<% } %>';
		$regular_title = _x( 'Tomorrow', 'text (timeline)', 'nelio-content' ) .
			' <span class="nc-date"><%= nextDayFormattedDate %></span>';
		$publication_title = _x( 'Day After Publication', 'text (timeline)', 'nelio-content' ) .
			' <span class="nc-date"><%= nextDayFormattedDate %></span>';
		$status_var_name = 'nextDayStatus';
		include 'social-timeline-block.php';
		?>

		<?php
		$block_name = 'week';
		$h4_class = '';
		$regular_title = _x( 'Week', 'text (timeline)', 'nelio-content' );
		$publication_title = _x( 'Week', 'text (timeline)', 'nelio-content' );
		$status_var_name = 'weekStatus';
		include 'social-timeline-block.php';
		?>

		<?php
		$block_name = 'month';
		$h4_class = '';
		$regular_title = _x( 'Month', 'text (timeline)', 'nelio-content' );
		$publication_title = _x( 'Month', 'text (timeline)', 'nelio-content' );
		$status_var_name = 'monthStatus';
		include 'social-timeline-block.php';
		?>

		<?php
		$block_name = 'later';
		$h4_class = '';
		$regular_title = _x( 'Other', 'text (timeline)', 'nelio-content' );
		$publication_title = _x( 'Other', 'text (timeline)', 'nelio-content' );
		$status_var_name = 'laterStatus';
		include 'social-timeline-block.php';
		?>

	<div id="timeline-end"></div>


</script><!-- #_nc-social-timeline -->
