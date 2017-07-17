<?php
/**
 * This partial is an empty container with a `Loading` message.
 *
 * It's useful in some meta boxes (and maybe other components) that load their
 * information using AJAX requests, such as, for instance, the Links or the
 * Social Messages meta boxes.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 *  * $container_name string The name of the container.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<?php
if ( isset( $container_name ) ) { ?>
	<div id="<?php
		echo esc_attr( $container_name );
	?>">
<?php
} else { ?>
	<div>
<?php
} ?>

	<div class="nc-loading-container">
		<span class="spinner is-active"></span>
		<p><?php
			echo esc_html_x( 'Loading&hellip;', 'text', 'nelio-content' );
		?></p>
	</div><!-- .nc-loading-container -->

</div><!-- actual-container -->

