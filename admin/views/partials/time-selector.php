<?php
/**
 * This partial shows a time selector.
 *
 * Available times can either be:
 *
 * * X hours after a post is published.
 * * X hours after now.
 * * A random time in a specified time interval of a given day.
 * * A concrete time.
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

$input_tag = '<input type="number" min="2" value="<%= timeValue %>" data-time-type="<%= timeType %>" class="nc-value" />';
?>

<script type="text/template" id="_nc-time-selector">

	<label><?php echo esc_html_x( 'Time', 'text', 'nelio-content' ); ?></label>

	<div class="nc-selector">

		<%
		if ( 'exact' === timeType ) { %>

			<div class="nc-exact">

				<input type="time" value="<%= timeValue %>" class="nc-value" data-time-type="exact"></input>
				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-exact -->

		<%
		} else if ( 'negative-hours' === timeType ) { %>

			<div class="nc-negative-hours">

				<?php
				$input = _x( '%s hours before publication.', 'text (input)', 'nelio-content' );
				printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
				?>
				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-negative-hours -->

		<%
		} else if ( 'positive-hours' === timeType ) { %>

			<div class="nc-positive-hours">

				<%
				if ( 'after-publication' === timeOffsetMode ) { %>
					<?php
					$input = _x( '%s hours after publication.', 'text (input)', 'nelio-content' );
					printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
					?>
				<%
				} else { // after-now %>
					<?php
					$input = _x( 'In %s hours.', 'text (input)', 'nelio-content' );
					printf( esc_html( $input ), $input_tag ); // @codingStandardsIgnoreLine
					?>
					<%
				} %>

				<a href="#" class="nc-change-type"><?php
					echo esc_html_x( 'Change', 'command', 'nelio-content' );
				?></a>

			</div><!-- .nc-positive-hours -->

		<%
		} else if ( 'time-interval' === timeType ) { %>

			<div class="nc-time-interval">

				<select class="nc-value" data-time-type="time-interval">
					<option value="morning"><?php echo esc_html_x( 'Morning (8am &ndash; 11am)', 'text (option)', 'nelio-content' ); ?></option>
					<option value="noon"><?php echo esc_html_x( 'Noon (11am &ndash; 3pm)', 'text (option)', 'nelio-content' ); ?></option>
					<option value="afternoon"><?php echo esc_html_x( 'Afternoon (3pm &ndash; 7pm)', 'text (option)', 'nelio-content' ); ?></option>
					<option value="night"><?php echo esc_html_x( 'Night (7pm &ndash; 11pm)', 'text (option)', 'nelio-content' ); ?></option>
					<option value="exact"><?php echo esc_html_x( 'Choose a custom time&hellip;', 'text (option)', 'nelio-content' ); ?></option>
				</select><!-- .nc-value -->

			</div><!-- .nc-time-interval -->

		<%
		} else if ( 'predefined-offset' === timeType ) { %>

			<div class="nc-predefined-offset">

				<select class="nc-value" data-time-type="predefined-offset">

					<%
					if ( 'before-publication' === timeOffsetMode ) { %>
						<option value="0"><?php echo esc_html_x( 'Same time as publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="-1"><?php echo esc_html_x( 'One hour before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="-3"><?php echo esc_html_x( 'Three hours before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="-5"><?php echo esc_html_x( 'Five hours before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="negative-hours"><?php echo esc_html_x( '__ hours before publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-hours"><?php echo esc_html_x( '__ hours after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="exact"><?php echo esc_html_x( 'Choose a custom time&hellip;', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} else if ( 'after-publication' === timeOffsetMode ) { %>
						<option value="0"><?php echo esc_html_x( 'Same time as publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="1"><?php echo esc_html_x( 'One hour after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="3"><?php echo esc_html_x( 'Three hours after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="5"><?php echo esc_html_x( 'Five hours after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-hours"><?php echo esc_html_x( '__ hours after publication', 'text (option)', 'nelio-content' ); ?></option>
						<option value="exact"><?php echo esc_html_x( 'Choose a custom time&hellip;', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} else { // after-now %>
						<option value="0"><?php echo esc_html_x( 'Now', 'text (option)', 'nelio-content' ); ?></option>
						<option value="1"><?php echo esc_html_x( 'In one hour', 'text (option)', 'nelio-content' ); ?></option>
						<option value="3"><?php echo esc_html_x( 'In three hours', 'text (option)', 'nelio-content' ); ?></option>
						<option value="5"><?php echo esc_html_x( 'In five hours', 'text (option)', 'nelio-content' ); ?></option>
						<option value="positive-hours"><?php echo esc_html_x( 'In __ hours', 'text (option)', 'nelio-content' ); ?></option>
						<option value="exact"><?php echo esc_html_x( 'Choose a custom time&hellip;', 'text (option)', 'nelio-content' ); ?></option>
					<%
					} %>

				</select><!-- .nc-value -->

			</div><!-- .nc-predefined-offset -->

		<%
		}  %>

	</div><!-- .nc-selector -->

</script><!-- #_nc-time-selector -->
