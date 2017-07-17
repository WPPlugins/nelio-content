<?php
/**
 * This partial contains the quality analysis for posts.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
?>

<script type="text/template" id="_nc-post-analysis">

	<div class="nc-post-analysis <%
		if ( 'awesome' === summary ) {
			%>nc-awesome<%
		} else if ( 'good' === summary ) {
			%>nc-good<%
		} else if ( 'improvable' === summary ) {
			%>nc-improvable<%
		} else if ( 'bad' === summary ) {
			%>nc-bad<%
		} else {
			%>nc-pending<%
		}%>">

		<div class="nc-post-analysis-status-summary">

			<% if ( 'awesome' === summary ) { %>

				<span class="nc-dashicons nc-dashicons-awards nc-summary-icon"></span>
				<span class="nc-status-explanation"><?php
					echo _x( 'The post looks <strong>awesome</strong>!', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
				?></span>

			<% } else if ( 'good' === summary ) { %>

				<span class="nc-dashicons nc-dashicons-thumbs-up nc-summary-icon"></span>
				<span class="nc-status-explanation"><?php
					echo _x( 'The post looks <strong>good</strong>', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
				?></span>

			<% } else if ( 'improvable' === summary ) { %>

				<span class="nc-dashicons nc-dashicons-admin-tools nc-summary-icon"></span>
				<% if ( areDetailsVisible ) { %>
					<span class="nc-status-explanation"><?php
						echo _x( 'The post is <strong>improvable</strong>:', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
					?></span>
				<% } else { %>
					<span class="nc-status-explanation"><?php
						echo _x( 'The post is <strong>improvable</strong>', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
					?></span>
				<% } %>

			<% } else if ( 'bad' === summary ) { %>

				<span class="nc-dashicons nc-dashicons-thumbs-down nc-summary-icon"></span>
				<% if ( areDetailsVisible ) { %>
					<span class="nc-status-explanation"><?php
						echo _x( 'This post is <strong>poor</strong>:', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
					?></span>
				<% } else { %>
					<span class="nc-status-explanation"><?php
						echo _x( 'This post is <strong>poor</strong>', 'text (post status, HTML)', 'nelio-content' ); // @codingStandardsIgnoreLine
					?></span>
				<% } %>

			<% } else { %>

				<span class="spinner is-active nc-pa-status"></span>
				<span class="nc-status-explanation"><?php
					echo esc_html_x( 'Analyzing post&hellip;', 'text (post status)', 'nelio-content' ); // @codingStandardsIgnoreLine
				?></span>

			<% } %>

			<button type="button" class="nc-details-control">
				<% if ( areDetailsVisible ) { %>
					<span class="nc-dashicons nc-dashicons-arrow-up"></span>
				<% } else { %>
					<span class="nc-dashicons nc-dashicons-arrow-down"></span>
				<% } %>
			</button>

		</div><!-- .nc-post-analysis-status-summary -->


		<% if ( areDetailsVisible ) { %>

			<div class="nc-post-analysis-details">

				<?php
				// ======================================================================
				// ======================================================================
				if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) && $settings->get( 'qa_is_yoast_seo_integrated' ) ) { ?>

					<% if ( 'unknown' === yoastSeo ) { %>

						<div class="nc-post-analysis-detail nc-yoast-seo nc-unknown">
							<span class="nc-dashicons nc-dashicons-chart-bar nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Yoast SEO is unavailable', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'bad' === yoastSeo ) { %>

						<div class="nc-post-analysis-detail nc-yoast-seo nc-bad">
							<span class="nc-dashicons nc-dashicons-chart-bar nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Address your Yoast SEO score', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'improvable' === yoastSeo ) { %>

						<div class="nc-post-analysis-detail nc-yoast-seo nc-improvable">
							<span class="nc-dashicons nc-dashicons-chart-bar nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Improve your Yoast SEO score', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'good' === yoastSeo ) { %>

						<div class="nc-post-analysis-detail nc-yoast-seo nc-good">
							<span class="nc-dashicons nc-dashicons-chart-bar nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Your Yoast SEO looks good', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else { %>

						<div class="nc-post-analysis-detail nc-yoast-seo nc-pending">
							<span class="nc-dashicons nc-dashicons-chart-bar nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Waiting for Yoast SEO evaluation&hellip;', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

					<% if ( 'unknown' === yoastContent ) { %>

						<div class="nc-post-analysis-detail nc-yoast-content nc-unknown">
							<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Yoast Content Analysis is unavailable', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'bad' === yoastContent ) { %>

						<div class="nc-post-analysis-detail nc-yoast-content nc-bad">
							<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'According to Yoast, content is bad.', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'improvable' === yoastContent ) { %>

						<div class="nc-post-analysis-detail nc-yoast-content nc-improvable">
							<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'According to Yoast, content is improvable.', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'good' === yoastContent ) { %>

						<div class="nc-post-analysis-detail nc-yoast-content nc-good">
							<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'According to Yoast, content is good.', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else { %>

						<div class="nc-post-analysis-detail nc-yoast-content nc-pending">
							<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Waiting for Yoast Content analysis&hellip;', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

				<?php
				} ?>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( ! isPostPublished ) { %>

					<% if ( 'unknown' === social ) { %>

						<div class="nc-post-analysis-detail nc-social nc-unknown">
							<span class="nc-dashicons nc-dashicons-share nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Unable to check social messages', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'bad' === social ) { %>

						<div class="nc-post-analysis-detail nc-social nc-bad">
							<span class="nc-dashicons nc-dashicons-share nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Schedule social messages', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'improvable' === social ) { %>

						<div class="nc-post-analysis-detail nc-social nc-improvable">
							<span class="nc-dashicons nc-dashicons-share nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Schedule another social message', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'good' === social ) { %>

						<div class="nc-post-analysis-detail nc-social nc-good">
							<span class="nc-dashicons nc-dashicons-share nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Social messages look good', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else { %>

						<div class="nc-post-analysis-detail nc-social nc-pending">
							<span class="nc-dashicons nc-dashicons-share nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Checking social messages&hellip;', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<?php
				if ( nc_is_subscribed_to( 'personal-plan', 'or-above' ) ) { ?>

					<% if ( 'unknown' === tasks ) { %>

						<div class="nc-post-analysis-detail nc-tasks nc-unknown">
							<span class="nc-dashicons nc-dashicons-flag nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Unable to check editorial tasks', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'bad' === tasks ) { %>

						<div class="nc-post-analysis-detail nc-tasks nc-bad">
							<span class="nc-dashicons nc-dashicons-flag nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Complete all tasks', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'good' === tasks ) { %>

						<div class="nc-post-analysis-detail nc-tasks nc-good">
							<span class="nc-dashicons nc-dashicons-flag nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'There are no pending tasks', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else { %>

						<div class="nc-post-analysis-detail nc-tasks nc-pending">
							<span class="nc-dashicons nc-dashicons-flag nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Checking editorial tasks&hellip;', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

				<?php
				} ?>

				<?php
				// ======================================================================
				// ======================================================================
				if ( current_theme_supports( 'post-thumbnails' ) ) { ?>

					<% if ( 'unknown' === thumbnail ) { %>

						<div class="nc-post-analysis-detail nc-featured-image nc-unknown">
							<span class="nc-dashicons nc-dashicons-camera nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Unable to check the feat. image', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'bad' === thumbnail ) { %>

						<div class="nc-post-analysis-detail nc-featured-image nc-bad">
							<span class="nc-dashicons nc-dashicons-camera nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Set a featured image', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'improvable' === thumbnail ) { %>

						<div class="nc-post-analysis-detail nc-featured-image nc-improvable">
							<span class="nc-dashicons nc-dashicons-camera nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Set a featured image', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'good' === thumbnail ) { %>

						<div class="nc-post-analysis-detail nc-featured-image nc-good">
							<span class="nc-dashicons nc-dashicons-camera nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'There\'s a featured image', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } else if ( 'pending' === thumbnail ) { %>

						<div class="nc-post-analysis-detail nc-featured-image nc-pending">
							<span class="nc-dashicons nc-dashicons-camera nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Checking featured image&hellip;', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

				<?php
				} ?>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( 'unknown' === excerpt ) { %>

					<div class="nc-post-analysis-detail nc-excerpt nc-unknown">
						<span class="nc-dashicons nc-dashicons-text nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check the excerpt', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'improvable' === excerpt ) { %>

					<div class="nc-post-analysis-detail nc-excerpt nc-improvable">
						<span class="nc-dashicons nc-dashicons-text nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Write an excerpt', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === excerpt ) { %>

					<div class="nc-post-analysis-detail nc-excerpt nc-good">
						<span class="nc-dashicons nc-dashicons-text nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Excerpt looks good', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else { %>

					<div class="nc-post-analysis-detail nc-text-length nc-pending">
						<span class="nc-dashicons nc-dashicons-text nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking excerpt&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( 'unknown' === textLength ) { %>

					<div class="nc-post-analysis-detail nc-text-length nc-unknown">
						<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check post\'s copy', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'bad' === textLength ) { %>

					<div class="nc-post-analysis-detail nc-text-length nc-bad" title="<?php
							echo esc_attr( sprintf(
								_x( 'Your post should be about %s words long', 'text', 'nelio-content' ),
								$settings->get( 'qa_min_word_count' )
							) );
						?>">
						<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Write a longer copy', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'improvable' === textLength ) { %>

					<div class="nc-post-analysis-detail nc-text-length nc-improvable" title="<?php
							echo esc_attr( sprintf(
								_x( 'Your post should be about %s words long', 'text', 'nelio-content' ),
								$settings->get( 'qa_min_word_count' )
							) );
						?>">
						<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Write a longer copy', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === textLength ) { %>

					<div class="nc-post-analysis-detail nc-text-length nc-good" title="<?php
							echo esc_attr( sprintf(
								_x( 'Minimum required post length is set to %s words', 'text', 'nelio-content' ),
								$settings->get( 'qa_min_word_count' )
							) );
						?>">
						<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Copy length looks good', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else { %>

					<div class="nc-post-analysis-detail nc-text-length nc-pending">
						<span class="nc-dashicons nc-dashicons-book nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking post content&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( 'unknown' === imageCount ) { %>

					<div class="nc-post-analysis-detail nc-image nc-unknown">
						<span class="nc-dashicons nc-dashicons-format-image nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check images', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'improvable' === imageCount ) { %>

					<div class="nc-post-analysis-detail nc-image nc-improvable">
						<span class="nc-dashicons nc-dashicons-format-image nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add one image in the copy', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === imageCount ) { %>

					<div class="nc-post-analysis-detail nc-image nc-good">
						<span class="nc-dashicons nc-dashicons-format-image nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'There\'s at least one image', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else { %>

					<div class="nc-post-analysis-detail nc-image nc-pending">
						<span class="nc-dashicons nc-dashicons-format-image nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking images in copy&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( 'unknown' === internalLinks ) { %>

					<div class="nc-post-analysis-detail nc-internal-links nc-unknown">
						<span class="nc-dashicons nc-dashicons-admin-links nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check internal links', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'bad' === internalLinks ) { %>

					<div class="nc-post-analysis-detail nc-internal-links nc-bad">
						<span class="nc-dashicons nc-dashicons-admin-links nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add links to your own site', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'improvable' === internalLinks ) { %>

					<div class="nc-post-analysis-detail nc-internal-links nc-improvable">
						<span class="nc-dashicons nc-dashicons-admin-links nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add links to your own site', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === internalLinks ) { %>

					<div class="nc-post-analysis-detail nc-internal-links nc-good">
						<span class="nc-dashicons nc-dashicons-admin-links nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Internal links look good', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else { %>

					<div class="nc-post-analysis-detail nc-internal-links nc-pending">
						<span class="nc-dashicons nc-dashicons-admin-links nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking usage of internal links&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<% if ( 'unknown' === externalLinks ) { %>

					<div class="nc-post-analysis-detail nc-external-links nc-unknown">
						<span class="nc-dashicons nc-dashicons-external nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check external links', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'bad' === externalLinks ) { %>

					<div class="nc-post-analysis-detail nc-external-links nc-bad">
						<span class="nc-dashicons nc-dashicons-external nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add links to external sources', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'improvable' === externalLinks ) { %>

					<div class="nc-post-analysis-detail nc-external-links nc-improvable">
						<span class="nc-dashicons nc-dashicons-external nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add links to external sources', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === externalLinks ) { %>

					<div class="nc-post-analysis-detail nc-external-links nc-good">
						<span class="nc-dashicons nc-dashicons-external nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'External links look good', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else { %>

					<div class="nc-post-analysis-detail nc-external-links nc-pending">
						<span class="nc-dashicons nc-dashicons-external nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking usage of external links&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<% if ( 'unknown' === tag ) { %>

					<div class="nc-post-analysis-detail nc-tags nc-unknown">
						<span class="nc-dashicons nc-dashicons-tag nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Unable to check tags', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'bad' === tag ) { %>

					<div class="nc-post-analysis-detail nc-tags nc-bad">
						<span class="nc-dashicons nc-dashicons-tag nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Add one or more tags', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'good' === tag ) { %>

					<div class="nc-post-analysis-detail nc-tags nc-good">
						<span class="nc-dashicons nc-dashicons-tag nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Post is properly tagged', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } else if ( 'pending' === tag ) { %>

					<div class="nc-post-analysis-detail nc-tags nc-pending">
						<span class="nc-dashicons nc-dashicons-tag nc-pa-status"></span>
						<span class="nc-status-explanation"><?php
							echo esc_html_x( 'Checking tags&hellip;', 'user (post status)', 'nelio-content' );
						?></span>
					</div><!-- .nc-post-analysis-detail -->

				<% } %>

				<?php
				// ======================================================================
				// ======================================================================
				?>

				<?php
				$wp_users = get_users( array(
					'blog_id' => $GLOBALS['blog_id'],
					'exclude' => get_current_user_id(),
					'who'     => 'authors',
				) );
				if ( count( $wp_users ) > 1 ) { ?>

					<% if ( 'improvable' === author ) { %>

						<div class="nc-post-analysis-detail nc-author nc-improvable">
							<span class="nc-dashicons nc-dashicons-admin-users nc-pa-status"></span>
							<span class="nc-status-explanation"><?php
								echo esc_html_x( 'Select a non-admin author', 'user (post status)', 'nelio-content' );
							?></span>
						</div><!-- .nc-post-analysis-detail -->

					<% } %>

				<?php
				} ?>

			</div><!-- .nc-post-analysis-details -->

		<% } %>

	</div><!-- .nc-post-analysis -->

</script><!-- #_nc-post-analysis -->
