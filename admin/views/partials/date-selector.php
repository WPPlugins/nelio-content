<?php
/**
 * This partial shows a date selector.
 *
 * Available dates can either be:
 *
 *  * X days after/before a post is published.
 *  * X days after now.
 *  * A concrete date.
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

$input_tag = '<input type="number" min="2" value="<%= dateValue %>" data-date-type="<%= dateType %>" class="nc-value" />';
?>

<script type="text/template" id="_nc-date-selector">

	<label><?php echo esc_html_x( 'Date', 'text', 'nelio-content' ); ?></label>

	<div class="nc-selector">

		<%
		if ( 'exact' === dateType ) { %>

			<div class="nc-exact">

				<% if ( NelioContent.helpers.isDate( dateValue ) ) { %>
					<input type="date" value="<%= dateValue %>" class="nc-datepicker nc-value" data-date-type="exact" min="<%= moment().format( 'YYYY-MM-DD' ) %>"></input>
				<% } else { %>
					<input type="date" class="nc-datepicker nc-value" data-date-type="exact" min="<%= moment().format( 'YYYY-MM-DD' ) %>"></input>
				<% } %>

				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-exact -->

		<%
		} else if ( 'negative-days' === dateType ) { %>

			<div class="nc-negative-days">

				<?php
				$input = _x( '%s days before publication.', 'text (input)', 'nelio-content' );
				printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
				?>
				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-negative-days -->

		<%
		} else if ( 'positive-days' === dateType ) { %>

			<div class="nc-positive-days">

				<%
				if ( 'after-publication' === dateOffsetMode ) { %>
					<?php
					$input = _x( '%s days after publication.', 'text (input)', 'nelio-content' );
					printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
					?>
				<%
				} else { // after-now %>
					<?php
					$input = _x( 'In %s days.', 'text (input)', 'nelio-content' );
					printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
					?>
				<%
				} %>
				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-positive-days -->

		<%
		} else if ( 'predefined-offset' === dateType ) { %>

			<div class="nc-predefined-offset">

				<select class="nc-value" data-date-type="predefined-offset">

					<%
					if ( 'before-publication' === dateOffsetMode ) { %>
						<option value="0"><?php echo esc_html_x( 'Same day as publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="-1"><?php echo esc_html_x( 'The day before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="-7"><?php echo esc_html_x( 'A week before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="negative-days"><?php echo esc_html_x( '__ days before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-days"><?php echo esc_html_x( '__ days after publication', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} else if ( 'after-publication' === dateOffsetMode ) { %>
						<option value="0"><?php echo esc_html_x( 'Same day as publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="1"><?php echo esc_html_x( 'The day after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="7"><?php echo esc_html_x( 'A week after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="28"><?php echo esc_html_x( 'A month after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-days"><?php echo esc_html_x( '__ days after publication', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} else { // after-now %>
						<option value="0"><?php echo esc_html_x( 'Today', 'text (option)', 'nelio-content' ); ?></option>
						<option value="1"><?php echo esc_html_x( 'Tomorrow', 'text (option)', 'nelio-content' ); ?></option>
						<option value="7"><?php echo esc_html_x( 'Next week', 'text (option)', 'nelio-content' ); ?></option>
						<option value="28"><?php echo esc_html_x( 'Next month', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-days"><?php echo esc_html_x( 'In __ days', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} %>

					<?php
					if ( nc_is_current_user( 'author' ) ) { ?>
						<option value="exact"><?php echo esc_html_x( 'Choose a custom date&hellip;', 'text (option)', 'nelio-content' ); ?></option>
					<?php
					} ?>

				</select><!-- .nc-value -->

			<div><!-- .nc-predefined-offset -->

		<%
		} %>

	</div><!-- .nc-selector -->

</script><!-- #_nc-date-selector -->

