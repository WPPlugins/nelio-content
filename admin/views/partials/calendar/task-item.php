<?php
/**
 * Underscore template for displaying a task message item in the calendar.
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
}

?>
<script type="text/template" id="_nc-task-item">

	<div class="nc-calendar-item nc-task">

		<div class="nc-content">

			<div class="nc-extra">

				<div class="nc-profile"
					title="<%= displayNameEscaped %>">

					<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
						<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
					</div><!-- .nc-picture -->

				</div><!-- .nc-profile -->

			</div><!-- .nc-extra -->

			<div class="nc-message">
				<% if ( completed ) { %>
					<input type="checkbox" checked="checked" /> <span class="nc-task-descr nc-completed"><%= _.escape( task ) %></span>
				<% } else { %>
					<input type="checkbox" /> <span class="nc-task-descr"><%= _.escape( task ) %></span>
				<% } %>
			</div><!-- .nc-message -->

		</div><!-- .nc-content -->

	</div><!-- .nc-task -->

</script><!-- #_nc-task-item -->
