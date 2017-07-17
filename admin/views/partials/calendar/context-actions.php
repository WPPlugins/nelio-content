<?php
/**
 * This file contains the set of context actions that are visible in the calendar view.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/calendar
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var string $side Either `left` or `right`. It specifies which side has to be used.
 */

?>

<div class="nc-context-actions nc-<?php echo esc_attr( $side ); ?>">

	<div class="nc-regular-actions">

		<p class="nc-action nc-add-post" data-date="<%= newItemDate %>" title="<?php echo esc_attr_x( 'Add Post', 'command', 'nelio-content' ); ?>">
			<span class="nc-dashicons nc-dashicons-plus"></span>
			<span class="nc-dashicons nc-dashicons-admin-post"></span>
		</p><!-- .nc-action.add-post -->

		<?php
		if ( nc_is_current_user( 'author' ) ) { ?>

			<p class="nc-action nc-add-social<?php if ( ! nc_is_subscribed() ) { echo ' nc-disabled'; } ?>" data-date="<%= newItemDate %>" title="<?php
					echo esc_attr_x( 'Add Social Message', 'command', 'nelio-content' );
					if ( ! nc_is_subscribed() ) {
						echo ' ';
						echo esc_attr_x( '(only available to subscribers)', 'text', 'nelio-content' );
					}//end if
				?>">
				<span class="nc-dashicons nc-dashicons-share"></span>
			</p><!-- .nc-action.add-social -->

			<p class="nc-action nc-add-task<?php if ( ! nc_is_subscribed() ) { echo ' nc-disabled'; } ?>" data-date="<%= newItemDate %>" title="<?php
					echo esc_attr_x( 'Add Task', 'command', 'nelio-content' );
					if ( ! nc_is_subscribed() ) {
						echo ' ';
						echo esc_attr_x( '(only available to subscribers)', 'text', 'nelio-content' );
					}//end if
				?>">
				<span class="nc-dashicons nc-dashicons-flag"></span>
			</p><!-- .nc-action.add-task -->

		<?php
		} ?>

	</div><!-- .nc-regular-actions -->

	<div class="nc-dragging-actions">

		<p class="nc-action nc-trash"><span class="nc-dashicons nc-dashicons-trash"></span></p>

	</div><!-- .nc-dragging-actions -->

	<div class="nc-permanent-actions">

		<?php
		if ( 'left' === $side ) { ?>
			<p class="nc-area nc-prev nc-month">
				<button class="nc-action nc-prev nc-month" title="<?php esc_attr_e( 'Previous Month', 'nelio-content' ); ?>">
					<span class="nc-dashicons nc-dashicons-arrow-left-alt2"></span>
				</button>
			</p>
		<?php
		} else { ?>
			<p class="nc-area nc-next nc-month">
				<button class="nc-action nc-next nc-month" title="<?php esc_attr_e( 'Next Month', 'nelio-content' ); ?>">
					<span class="nc-dashicons nc-dashicons-arrow-right-alt2"></span>
				</button>
			</p>
		<?php
		} ?>

	</div><!-- .nc-permanent-actions -->

</div><!-- .nc-context-actions -->

