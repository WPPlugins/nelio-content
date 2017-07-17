<?php
/**
 * This partial shows a date range selector.
 *
 * Available date ranges can either be:
 *
 * * Month to date.
 * * Last 30 days.
 * * Last month.
 * * Year to date.
 * * Last 12 months.
 * * Last year.
 * * Custom...
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.1
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

<script type="text/template" id="_nc-date-filter">

	<select class="nc-publication-date-selector" data-time-type="time-interval">
		<option value="all"><?php echo esc_html_x( 'All Time', 'text (option)', 'nelio-content' ); ?></option>
		<optgroup label="<?php echo esc_attr_x( 'Filter by Publication Date', 'text (option)', 'nelio-content' ); ?>">
			<option value="month-to-date"><?php echo esc_html_x( 'Month to Date', 'text (option)', 'nelio-content' ); ?></option>
			<option value="last-30-days"><?php echo esc_html_x( 'Last 30 Days', 'text (option)', 'nelio-content' ); ?></option>
			<option value="last-month"><?php echo esc_html_x( 'Last Month', 'text (option)', 'nelio-content' ); ?></option>
			<option value="year-to-date"><?php echo esc_html_x( 'Year to Date', 'text (option)', 'nelio-content' ); ?></option>
			<option value="last-12-months"><?php echo esc_html_x( 'Last 12 Months', 'text (option)', 'nelio-content' ); ?></option>
			<option value="last-year"><?php echo esc_html_x( 'Last Year', 'text (option)', 'nelio-content' ); ?></option>
			<option value="custom"><?php echo esc_html_x( 'Custom&hellip;', 'text (option)', 'nelio-content' ); ?></option>
		</optgroup>
	</select><!-- .nc-publication-date-selector -->

</script>

<script type="text/template" id="_nc-date-range">

	<span class="nc-date-range">
		<input type="date" value="<%= from %>" class="nc-datepicker nc-value-from" data-date-type="exact" max="<%= today %>"></input>
		<span>&mdash;</span>
		<input type="date" value="<%= to %>" class="nc-datepicker nc-value-to" data-date-type="exact" min="<%= from %>" max="<%= today %>"></input>
		<a class="nc-change-mode"><span class="nc-dashicons nc-dashicons-no" title="<?php
			echo esc_attr_x( 'Back to predefined date intervals', 'command', 'nelio-content' );
		?>"></span></a>
	</span>

</script>
