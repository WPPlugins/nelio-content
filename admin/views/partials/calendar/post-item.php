<?php
/**
 * Underscore template for displaying a post item in the calendar.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
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
<script type="text/template" id="_nc-post-item">

	<div class="nc-calendar-item nc-post">

		<div class="nc-content nc-status-<%= status %><% if ( 'publish' === status ) { %> nc-content-blurred<% } %><?php
			$settings = Nelio_Content_Settings::instance();
			if ( $settings->get( 'colorize_calendar' ) ) {
				if ( $settings->get( 'use_custom_post_statuses' ) ) {
					echo ' nc-use-full-colors';
				} else {
					echo ' nc-use-three-colors';
				}//end if
			}//end if
		?>">

			<div class="nc-message">

				<span class="nc-status">
				<% if ( 'idea' === status ) { %>
				<span class="nc-dashicons nc-dashicons-lightbulb" title="<?php
						echo esc_attr_x( 'Idea', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } else if ( 'assigned' === status ) { %>
					<span class="nc-dashicons nc-dashicons-admin-users" title="<?php
						echo esc_attr_x( 'Assigned', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } else if ( 'in-progress' === status ) { %>
					<span class="nc-dashicons nc-dashicons-admin-generic" title="<?php
						echo esc_attr_x( 'In Progress', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } else if ( 'draft' === status ) { %>
					<span class="nc-dashicons nc-dashicons-edit" title="<?php
						echo esc_attr_x( 'Draft', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } else if ( 'pending' === status ) { %>
					<span class="nc-dashicons nc-dashicons-visibility" title="<?php
						echo esc_attr_x( 'Pending Review', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } else if ( 'future' === status ) { %>
					<span class="nc-dashicons nc-dashicons-clock" title="<?php
						echo esc_attr_x( 'Scheduled', 'text (post)', 'nelio-content' );
					?>"></span>
				<% } %>
				</span>

				<strong><%= date.format( NelioContent.i18n.time.calendar ).replace( /((:00)|(:..))(.)m/g, '$3$4' ) %></strong>

				<span class="nc-actual-message"><%= title %></span>

			</div><!-- .nc-message -->

			<div class="nc-extra">

				<div class="nc-profile"
					title="<%= displayNameEscaped %>">

					<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
						<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
					</div><!-- .nc-picture -->

				</div><!-- .nc-profile -->

				<div class="nc-summary">

					<?php
					$settings = Nelio_Content_Settings::instance();
					$post_types = $settings->get( 'calendar_post_types' );
					?>

					<?php
					if ( 1 === count( $post_types ) && 'post' === $post_types[0] ) { ?>

						<% if ( typeof categories === 'string' && categories.length > 0 ) { %>
							<span class="nc-category-list">
								<span class="nc-dashicons nc-dashicons-category"></span>
								<%= categories %>
							</span>
						<% } %>

					<?php
					} else { ?>

						<span class="nc-category-list">
							<span class="nc-dashicons nc-dashicons-sticky"></span>
							<%= typeLabel %>
						</span>

					<?php
					} ?>

				</div><!-- .nc-summary -->

			</div><!-- .nc-extra -->

		</div><!-- .nc-content -->

	</div><!-- .nc-post -->

</script><!-- #_nc-post-item -->
