<?php
/**
 * This file contains the backbone template that renders the Cleaner dialog.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/cleaner
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
?>

<script type="text/template" id="_nc-cleaner-dialog">

	<p><?php
		echo esc_html_x( 'Please, select one action:', 'user', 'nelio-content' );
	?></p>

	<table>

		<tr class="nc-deactivate-action">
			<td><input type="radio" name="nc-deactivate-action" value="deactivate" /></td>
			<td><?php
				echo esc_html_x( 'Deactivate the plugin temporarily', 'command', 'nelio-content' );
			?></td>
		</tr><!-- .nc-deactivate-action -->

		<?php
		if ( nc_is_free_trial() ) { ?>

			<tr class="nc-deactivate-action">
				<td><input type="radio" name="nc-deactivate-action" value="cancel-subscription-and-deactivate" /></td>
				<td><?php
					echo esc_html_x( 'Stop premium trial, clean database, and deactivate the plugin', 'command', 'nelio-content' );
				?></td>
			</tr><!-- .nc-deactivate-action -->

		<?php
		} else if ( nc_is_subscribed() ) { ?>

			<tr class="nc-deactivate-action">
				<td><input type="radio" name="nc-deactivate-action" value="cancel-subscription-and-deactivate" /></td>
				<td><?php
					echo esc_html_x( 'Cancel my subscription, clean database, and deactivate the plugin', 'command', 'nelio-content' );
				?></td>
			</tr><!-- .nc-deactivate-action -->

		<?php
		} else { ?>

			<tr class="nc-deactivate-action">
				<td><input type="radio" name="nc-deactivate-action" value="clean-and-deactivate" /></td>
				<td><?php
					echo esc_html_x( 'Clean database and deactivate the plugin', 'command', 'nelio-content' );
				?></td>
			</tr><!-- .nc-deactivate-action -->

		<?php
		} ?>

	</table>

</script><!-- #_nc-cleaner-dialog -->

