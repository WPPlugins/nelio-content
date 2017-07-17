<?php
/**
 * This partial corresponds to the free trial dialog.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.3
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

<script type="text/template" id="_nc-free-trial-dialog">

	<% if ( 'form' === mode ) { %>

		<p><label><?php
			echo esc_html_x( 'First Name', 'text', 'nelio-content' );
		?></label><br>
		<input type="text" class="nc-firstname" /></p>

		<p><label><?php
			echo esc_html_x( 'Last Name', 'text', 'nelio-content' );
		?></label><br>
		<input type="text" class="nc-lastname" /></p>

		<p><label><?php
			echo esc_html_x( 'Email', 'text', 'nelio-content' );
		?></label><br>
		<input type="email" class="nc-email" /></p>

	<% } else { %>

		<p><?php
			echo _x( 'Would you like to try out our <strong>Premium Features</strong> for free? Start a 30-day trial!', 'user', 'nelio-content' );
		?></p>

		<ul>
			<li>Schedule more social messages</li>
			<li>Add more social profiles</li>
			<li>No credit card required</li>
		</ul>

	<% } %>

</script><!-- #_nc-free-trial-dialog -->

