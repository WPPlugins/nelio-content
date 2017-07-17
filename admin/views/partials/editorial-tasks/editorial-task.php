<?php
/**
 * The underscore template of an editorial task.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-tasks
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

<script type="text/template" id="_nc-editorial-task">

	<div class="nc-task<%
		if ( isAssignerMe ) {
			%> nc-assigner-me<%
		} else {
			%> nc-assigner-other<%
		}

		if ( isAssigneeMe ) {
			%> nc-assignee-me<%
		} else {
			%> nc-assignee-other<%
		}

		if ( completed ) {
			%> nc-completed<%
		}

		if ( isBeingSaved ) {
			%> nc-saving<%
		}

		if ( 'deleting' === deletionStatus ) {
			%> nc-deleting<%
		}

		if ( isOverdue && ! completed && ! isBeingSaved ) {
			%> nc-overdue<%
		}

	%>">


		<div class="nc-checkbox-and-actual-task">

			<?php
			// Actions for removing a task are only available if the
			// current user is an editor or the creator of the task.
			if ( ! nc_is_current_user( 'editor' ) ) { ?>
			<% if ( isAssignerMe ) { %>
			<?php
			} ?>

				<% if ( ! isBeingSaved ) { %>

					<div class="nc-actions">

						<% if ( 'deleting' === deletionStatus ) { %>

							<span class="spinner is-active" title="<?php
								esc_attr_e( 'Deleting&hellip;', 'nelio-content' );
							?>"></span>

						<% } else if ( 'awaiting-confirmation' === deletionStatus ) { %>

							<span class="nc-delete-confirmation-label"><?php
								esc_html_e( 'Are you sure?', 'nelio-content' );
							?></span>
							<span class="nc-dashicons nc-dashicons-yes nc-do-delete" title="<?php
								echo esc_attr_x( 'Yes, Delete It', 'command (social message)', 'nelio-content' );
							?>"></span>
							<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-deletion" title="<?php
								echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
							?>"></span>

						<% } else { %>

							<span class="nc-dashicons nc-dashicons-trash nc-delete" title="<?php
								echo esc_attr_x( 'Delete', 'command', 'nelio-content' );
							?>"></span>

						<% } %>

					</div><!-- .nc-actions -->

				<% } %>

			<?php
			if ( ! nc_is_current_user( 'editor' ) ) { ?>
			<% } %>
			<?php
			} ?>

			<div class="nc-checkbox"><input type="checkbox" <%
				if ( completed ) {
					%> checked="checked"<%
				}

				<?php
				// The checkbox for completing an action is only available to
				// editors and the person to whom is assigned.
				if ( nc_is_current_user( 'editor' ) ) { ?>

					if ( 'deleting' === deletionStatus || isBeingSaved ) {
						%> disabled="disabled" class="disabled"<%
					}

				<?php
				} else { ?>

					if ( ! isAssigneeMe ) {
						%> disabled="disabled" class="disabled"<%
					} else if ( 'deleting' === deletionStatus || isBeingSaved ) {
						%> disabled="disabled" class="disabled"<%
					}

				<?php
				} ?>

			%> /></div>

			<span class="nc-actual-task">
				<%= _.escape( task ) %>
			</span>

		</div><!-- .nc-checkbox-and-actual-task -->

		<% if ( isBeingSaved ) { %>

			<div class="nc-assignee-and-date"><?php
				esc_html_e( 'Saving&hellip;', 'nelio-content' );
			?></div>

		<% } else { %>

			<div class="nc-assignee-and-date">

				<% if ( isAssigneeMe ) { %>
					<span class="nc-assignee"><strong><?php
						echo esc_html_x( 'You', 'text (assignee)', 'nelio-content' );
					?></strong></span> •
				<% } else { %>
					<span class="nc-assignee"><%= _.escape( assigneeName ) %></span> •
				<% } %>

				<span class="nc-date"><%= dateFormatted %></span>
				<% if ( isOverdue && ! isBeingSaved && ! completed ) { %>
					<span class="nc-dashicons nc-dashicons-warning" title="<?php
						echo esc_html_x( 'Overdue', 'text (editorial task)', 'nelio-content' );
					?>"></span>
				<% } %>

			</div><!-- .nc-assignee-and-date -->

		<% } %>

	</div><!-- .nc-task -->

</script><!-- #_nc-editorial-task -->
