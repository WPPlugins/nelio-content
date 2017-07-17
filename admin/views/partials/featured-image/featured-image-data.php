<?php
/**
 * This partial adds a few values to the admin screen. They feed the "Featured
 * Image" backbone model.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/featured-image
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<input type="hidden" id="nc-feat-image-mode" value="<?php echo esc_attr( $mode ); ?>" />
<input type="hidden" id="nc-feat-image-url" value="<?php echo esc_url( $url ); ?>" />
<input type="hidden" id="nc-feat-image-alt" value="<?php echo esc_attr( $alt ); ?>" />
<input type="hidden" id="nc-feat-image-thumbnail-id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
<input type="hidden" id="nc-feat-image-auto-url" value="<?php echo esc_url( $auto_url ); ?>" />

