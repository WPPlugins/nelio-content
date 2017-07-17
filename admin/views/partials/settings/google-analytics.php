<?php
/**
 * Partial for the Google Analytics setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

/**
 * List of vars used in this partial:
 *
 *  * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

	<input id="<?php echo esc_html( $id . '-input' ); ?>" type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php
	if ( get_option( 'nc_ga_token_error' ) ) {
		echo ' data-token-error="true"';
	}//end if
?> />
<div id="<?php echo esc_html( $id . '-container' ); ?>"></div>

<script type="text/template" id="_ga-setting-change-profile">

	<% if ( 'view-selector-changing' === mode ) { %>

		<div class="nc-ga-profile"><?php
			echo esc_html_x( 'Deleting&hellip;', 'text (comment)', 'nelio-content' );
		?></div>

	<% } else if ( 'view-selector-awaiting-confirmation' === mode ) { %>

		<div class="nc-ga-profile">

			<span class="nc-change-confirmation-label"><?php
				esc_html_e( 'Are you sure?', 'nelio-content' );
			?></span>

			<span class="nc-dashicons nc-dashicons-yes nc-action nc-do-change" title="<?php
				echo esc_attr_x( 'Yes, change Google Analytics account', 'command', 'nelio-content' );
			?>"></span>
			<span class="nc-dashicons nc-dashicons-no-alt nc-action nc-cancel-change" title="<?php
				echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
			?>"></span>

		</div><!-- .nc-ga-profile -->

	<% } else { %>

		<div class="nc-ga-profile">

			<span class="nc-change-wrapper"><?php
				echo esc_html_x( 'Don\'t you find the view you\'re looking for?', 'user', 'nelio-content' );
			?> <span class="nc-action nc-change"><?php
				echo esc_html_x( 'Use a different Google Analytics account.', 'user', 'nelio-content' );
			?></span></span>

			<button class="button nc-refresh-analytics"><?php echo esc_html_x( 'Refresh Analytics', 'command (analytics)', 'nelio-content' ); ?></button>

		</div><!-- .nc-ga-profile -->

	<% } %>

</script>

<script type="text/template" id="_ga-setting-retrieve-code">

	<input class="nc-ga-code" type="text" size="60" placeholder="<?php echo esc_attr_x( 'Paste code here', 'user', 'nelio-content' ); ?>">
	<button class="nc-ga-code-accept button button-primary" disabled="disabled"><?php echo esc_html_x( 'Load', 'command', 'nelio-content' ); ?></button>
	<button class="nc-ga-code-cancel button"><?php echo esc_html_x( 'Cancel', 'command', 'nelio-content' ); ?></button>

</script>

<script type="text/template" id="_analytics-refresher-dialog">

	<div class="nc-row">

		<div class="nc-column">
			<select class="nc-period">
				<option value="month"><?php echo esc_html_x( 'Posts from last month', 'text (analytics update period)', 'nelio-content' ); ?></option>
				<option value="year"><?php echo esc_html_x( 'Posts from last year', 'text (analytics update period)', 'nelio-content' ); ?></option>
				<option value="all"><?php echo esc_html_x( 'All posts', 'text (analytics update period)', 'nelio-content' ); ?></option>
			</select><!-- .nc-period -->
		</div><!-- .nc-column -->

		<div class="nc-column nc-count">
			<span class="nc-current"></span><span class="nc-total"></span>
		</div><!-- .nc-column.nc-count -->

	</div><!-- .nc-row -->

	<?php include NELIO_CONTENT_ADMIN_DIR . '/views/partials/progress-bar.php'; ?>

</script><!-- .dialog-templ -->
