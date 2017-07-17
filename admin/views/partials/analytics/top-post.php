<?php
/**
 * The underscore template of a top post.
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

<script type="text/template" id="_nc-top-post">

	<div class="nc-top-post nc-box">

		<div class="nc-top-post-data">

			<div class="nc-top-post-image nc-top-post-cell">
				<a href="<%= _.escape( permalink ) %>" target="_blank">
					<div style="background-image:url(<%= thumbnail %>);"></div>
				</a>
			</div><!-- .nc-top-post-image -->

			<div class="nc-top-post-summary nc-top-post-cell">

				<div class="nc-post-title">
					<a href="<%= _.escape( permalink ) %>" target="_blank"><%= _.escape( titleFormatted ) %></a>
				</div><!-- .nc-post-title -->

				<div class="nc-post-excerpt">
					<%= _.escape( excerpt ) %>
				</div><!-- .nc-post-excerpt -->

				<div class="nc-profile"
					title="<%= displayNameEscaped %>">

					<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
						<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
					</div><!-- .nc-picture -->

					<div class="nc-profile-name"><span><%= displayNameEscaped %></span></div>

				</div><!-- .nc-profile -->

				<div class="nc-post-date">
					<span class="nc-post-date"><%= _.escape( date ) %> • <%= _.escape( since ) %></span>
				</div>
			</div><!-- .nc-top-post-summary -->

		</div><!-- .nc-top-post-data -->

		<div class="nc-top-post-values">

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

			$img = NELIO_CONTENT_INCLUDES_URL . '/lib/settings/assets/images/help.png';
			$label = ' ' . sprintf(
				'<img title="%s" class="nc-help-with-html-tooltip" style="margin-right:-15px;cursor:pointer;vertical-align:bottom;" src="%s" height="16" width="16" />',
				esc_attr_x( 'Total number of scheduled social messages.', 'text', 'nelio-content' ),
				$img
			);
			?>

			<div class="nc-top-post-social nc-top-post-cell">

				<div class="nc-top-post-metric"><?php
					echo esc_html_x( 'Social Queue', 'text', 'nelio-content');
					echo $label;
				?></div>

				<% if ( socialQueueError ) { %>

					<p><span class="nc-dashicons nc-dashicons-warning"></span> <%= socialQueueError %></p>

				<% } else { %>

					<div class="nc-analytics-count">
						<% if ( ! isSocialQueueReady ) { %>
							<span class="spinner is-active"></span>
						<% } else { %>
							<%= scheduleCount %>
						<% } %>
					</div>
					<div class="nc-analytics-published">
						<% if ( 0 < publishCount + scheduleCount ) { %>
						<?php echo esc_html_x( 'Sent:', 'text (social message)', 'nelio-content'); ?>
						<%= publishCount %><% if ( scheduleCount > 0 ) { %>/<%= publishCount + scheduleCount %><% } %>
						<% } %>
					</div>
					<% if ( isSocialQueueReady ) { %>
						<div class="nc-share">
							<?php if ( nc_is_subscribed() ) { ?>
								<button class="button button-primary"><?php
									echo esc_html_x( 'Add Message', 'command', 'nelio-content');
								?></button>
							<?php } else { ?>
								<button class="button" disabled="disabled" title="<?php
									echo esc_attr_x( 'Only available to subscribers', 'text', 'nelio-content');
								?>"><?php
									echo esc_html_x( 'Add Message', 'command', 'nelio-content');
								?></button>
							<?php } ?>
						</div>
					<% } %>

				<% } %>

			</div><!-- .nc-top-post-social -->

		</div><!-- .nc-top-post-values -->

	</div><!-- .nc-top-post -->

</script><!-- #_nc-top-post -->
