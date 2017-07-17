<?php
/**
 * HTML template used as a callback after connecting new social profiles.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

if ( isset( $_REQUEST['cancel'] ) ) { // Input var ok.

	if ( 'true' === sanitize_text_field( wp_unslash( $_REQUEST['cancel'] ) ) ) { // Input var ok.
		echo '<html><head><script type="text/javascript">window.close()</script></head><body></body></html>';
		exit;
	}//end if

}//end if

?>
<html>
<head>

	<title><?php
		echo esc_html_x( 'New Social Profile Successfully Added!', 'title (New Profile Added)', 'nelio-content' );
	?></title>
	<meta charset="UTF-8">

	<style type="text/css">

		body {
			background-color: #f1f1f1;
			background-image: url( <?php echo esc_url( NELIO_CONTENT_ADMIN_URL . '/images/nelio-content-logo.png' ); ?> );
			background-position: center 50px;
			background-repeat: no-repeat;
			color: #444;
			font-family: "Open Sans", sans-serif;
			font-size: 16px;
			line-height: 1.4em;
			padding: 100px 20px 20px;
			text-align: center;
		}

		.nc-loader {
			margin-top: 50px;
			color: #808080;
		}

	</style>

</head>

<body>

	<div class="nc-loader">

		<img src="<?php
			echo esc_attr( NELIO_CONTENT_ADMIN_URL . '/images/spinner.gif' );
		?>" title="<?php
			echo esc_attr_x( 'Updating social profiles&hellip;', 'text', 'nelio-content' );
		?>" />

		<p class="nc-explanation">
			<?php echo esc_html_x( 'Your new social profile has been successfully added.', 'user', 'nelio-content' ); ?>
			<br>
			<?php echo esc_html_x( 'Please, wait a moment while we update your list of social profiles&hellip;', 'user', 'nelio-content' ); ?>
		</p>

	</div><!-- .nc-loader -->

<script type="text/javascript">
(function() {

	'use strict';

	// Reload profiles.
	var profiles = window.opener.NelioContent.profiles;
	profiles.listenToOnce( profiles, 'nc:ready', function() {
		window.close();
	});
	profiles.reload();

	// Safe guard, just to make sure that this window is closed at some point.
	setTimeout( function() {
		window.opener.location.reload();
		window.close();
	}, 10000 );

})();
</script>

</body>
</html>
