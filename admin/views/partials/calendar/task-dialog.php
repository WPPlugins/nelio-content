<?php
/**
 * This partial is used for creating a new task dialog in the calendar page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/calendar
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this page:
 *
 * None.
 */

?>

<script type="text/template" id="_nc-task-dialog">

	<div class="nc-task-dialog">

		<textarea class="nc-task-description" placeholder="<?php
			esc_attr_e( 'Enter a task&hellip;', 'nelio-content' );
		?>"></textarea>

		<div class="nc-assignee-and-date">

			<div class="nc-assignee">

				<label><?php echo esc_html_x( 'Assignee', 'text', 'nelio-content' ); ?></label>
				<div class="nc-field"></div>

			</div><!-- .nc-assignee -->

			<div class="nc-date">

				<label><?php echo esc_html_x( 'Due Date', 'text', 'nelio-content' ); ?></label>
				<div class="nc-field">
					<input class="nc-value" type="date" value="<%= _.escape( dateValue ) %>" min="<%= _.escape( today ) %>" placeholder="<?php
						echo esc_attr_x( 'Select a date&hellip;', 'user', 'nelio-content' );
					?>" />
				</div><!-- .nc-field -->

			</div><!-- .nc-date -->

		</div><!-- .nc-assignee-and-date -->

	</div><!-- .nc-task-dialog -->

</script><!-- #_nc-task-dialog -->

