<?php
/**
 * The underscore template for rendering a list of editorial tasks.
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

<script type="text/template" id="_nc-editorial-tasks">

	<% if ( 0 === taskCount ) { %>
		<div class="nc-no-tasks"><?php
			echo esc_html_x( 'Keep track of the things that need to get done with tasks.', 'text', 'nelio-content' );
		?></div>
	<% } else { %>
		<div class="nc-task-list-progress">

			<div class="nc-progress">

				<div class="nc-bar-container">
					<div class="nc-bar"></div>
				</div><!-- .nc-bar-container" -->

			</div><!-- .nc-progress -->

			<div class="nc-percentage"></div>

		</div><!-- .nc-task-list-progress -->
	<% } %>

	<div class="nc-tasks"></div>

	<div class="nc-new-task-form-opener">
		<input type="button" class="button" value="<?php
			echo esc_attr_x( 'Add Task', 'command', 'nelio-content' );
		?>" />
	</div><!-- .nc-new-task-form-opener -->
	<div class="nc-new-task-form-container"></div>

</script><!-- #_nc-editorial-tasks -->
