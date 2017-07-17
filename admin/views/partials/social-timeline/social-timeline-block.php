<?php
/**
 * This partial is used for rendering a block of scheduled social messages in the timeline.
 *
 * There are 5 blocks:
 *
 *  * Today
 *  * Tomorrow
 *  * Week
 *  * Month
 *  * Later
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-timeline
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 *  * $block_name. The name of the current block. Can be `day`, `next-day`,
 *               `week`, `month`, or `later`.
 *  * $regular_title. The title of the block, assuming it's based on today.
 *  * $publication_title. The title of the block, assuming it's based on the
 *               post's publication date.
 *
 *  * $status_var_name. The underscore variable that contains the block's status.
 */

?>
<div class="nc-timeline-section nc-<?php echo esc_attr( $block_name ); ?>">

	<div class="nc-timeline">
		<div class="nc-indicator nc-<?php echo esc_attr( $block_name ); ?> nc-<%= <?php echo esc_attr( $status_var_name ); ?> %>"></div>
	</div><!-- .nc-timeline -->

	<div class="nc-information">

		<% if ( isPostPublished ) { %>
			<h4 name="<?php echo esc_attr( $block_name ); ?>"<?php
					echo $h4_class; // @codingStandardsIgnoreLine
				?>><?php
				echo $regular_title; // @codingStandardsIgnoreLine
			?></h4>
		<% } else { %>
			<h4 name="<?php echo esc_attr( $block_name ); ?>"<?php
					echo $h4_class; // @codingStandardsIgnoreLine
				?>><?php
				echo $publication_title; // @codingStandardsIgnoreLine
			?></h4>
		<% } %>

		<div class="nc-social-messages"></div>

		<% if ( 'bad' !== <?php echo esc_html( $status_var_name ); ?> ) { %>

			<?php
			if ( nc_is_subscribed() ) { ?>

				<div class="nc-social-timeline nc-new-social-message nc-extra-message">
					<span class="nc-action nc-new-social-message button secondary-button button-large nc-add-message"><?php
						echo esc_html_x( 'Add Another', 'command (social message)', 'nelio-content' );
					?></span>
				</div><!-- .nc-new-social-message.extra-message -->

			<?php
			} else { ?>

				<% if ( areMoreMessagesAllowed ) { %>

					<div class="nc-social-timeline nc-new-social-message nc-extra-message">
						<span class="nc-action nc-new-social-message button secondary-button button-large nc-add-message"><?php
							echo esc_html_x( 'Add Another', 'command (social message)', 'nelio-content' );
						?></span>
					</div><!-- .nc-new-social-message.extra-message -->

				<% } else { %>

					<div class="nc-social-timeline nc-new-social-message nc-extra-message">
						<span class="nc-action nc-new-social-message button secondary-button button-large nc-upgrade"><?php
							echo esc_html_x( 'Want more? Subscribe!', 'user (social message)', 'nelio-content' );
						?></span>
					</div><!-- .nc-new-social-message.extra-message -->

				<% } %>

			<?php
			} ?>

		<% } else { %>

			<?php if ( nc_is_subscribed() ) { ?>
			<div class="nc-social-timeline nc-new-social-message nc-first-message nc-add-message">
			<?php } elseif ( 'day' === $block_name ) { ?>
			<div class="nc-social-timeline nc-new-social-message nc-first-message <% if ( areMoreMessagesAllowed ) { %>nc-add-message<% } else { %>nc-upgrade<% } %>">
			<?php } else { ?>
			<div class="nc-social-timeline nc-new-social-message nc-first-message nc-upgrade">
			<?php } ?>

				<div class="nc-message-pointer"></div>

				<div class="nc-action new-social-message">

					<div class="nc-profile">
						<div class="nc-fake-profile"></div>
					</div><!-- .nc-profile -->

					<div class="nc-actual-message">
						<span class="nc-fake-message"><?php

						if ( nc_is_subscribed() ) {
							echo esc_html_x( 'Add Social Message', 'command', 'nelio-content' );
						} elseif ( 'day' === $block_name ) { ?>
							<% if ( areMoreMessagesAllowed ) { %>
								<?php echo esc_html_x( 'Add Social Message', 'command', 'nelio-content' ); ?>
							<% } else { %>
								<?php echo esc_html_x( 'Subscribe to Nelio Content and schedule more messages', 'user', 'nelio-content' ); ?>
							<% } %>
						<?php
						} else {
							echo esc_html_x( 'Subscribe to Nelio Content and schedule more messages', 'user', 'nelio-content' );
						}//end if

						?></span>
					</div><!-- .nc-actual-message -->

				</div><!-- .nc-action.new-social-message -->

			</div><!-- .nc-new-social-message.first-message -->

		<% } %>

	</div><!-- .nc-information -->

</div><!-- .nc-timeline-section.<?php echo esc_attr( $block_name ); ?> -->

