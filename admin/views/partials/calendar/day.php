<?php
/**
 * Underscore template for rendering a day in the calendar grid view.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/calendar
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
<script type="text/template" id="_nc-day">

	<div class="nc-day">

		<div class="nc-head">

			<div class="nc-actions">

				<?php
				if ( nc_is_current_user( 'author' ) ) { ?>

					<div class="nc-action nc-add-task<?php if ( ! nc_is_subscribed() ) { echo ' nc-disabled'; } ?>" title="<?php
							echo esc_attr_x( 'Add Task', 'command', 'nelio-content' );
							if ( ! nc_is_subscribed() ) {
								echo ' ';
								echo esc_attr_x( '(only available to subscribers)', 'text', 'nelio-content' );
							}//end if
						?>">
						<div class="nc-dashicons nc-dashicons-flag"></div>
					</div>

					<div class="nc-action nc-add-social<?php if ( ! nc_is_subscribed() ) { echo ' nc-disabled'; } ?>" title="<?php
							echo esc_attr_x( 'Add Social Message', 'command', 'nelio-content' );
							if ( ! nc_is_subscribed() ) {
								echo ' ';
								echo esc_attr_x( '(only available to subscribers)', 'text', 'nelio-content' );
							}//end if
						?>">
						<div class="nc-dashicons nc-dashicons-share"></div>
					</div>

				<?php
				} ?>

				<div class="nc-action nc-add-post" title="<?php
						echo esc_attr_x( 'Add Post', 'command', 'nelio-content' );
					?>">
					<div class="nc-dashicons nc-dashicons-plus"></div>
					<div class="nc-dashicons nc-dashicons-admin-post"></div>
				</div>

			</div><!-- .nc-actions -->

			<div class="nc-day-number"></div>

		</div><!-- .nc-head -->

		<div class="nc-items"></div>

	</div><!-- .nc-day -->

</script><!-- #_nc-day -->
