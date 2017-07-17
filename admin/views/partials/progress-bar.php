<?php
/**
 * This partial is used for rendering a progress bar.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

switch ( get_user_option( 'admin_color' ) ) {

	case 'light':
		$background = '#999999';
		break;

	case 'blue':
		$background = '#096484';
		break;

	case 'coffee':
		$background = '#59524c';
		break;

	case 'ectoplasm':
		$background = '#a3b746';
		break;

	case 'midnight':
		$background = '#e14d43';
		break;

	case 'ocean':
		$background = '#9ebaa0';
		break;

	case 'sunrise':
		$background = '#dd823b';
		break;

	default:
		$background = '#0073aa';

}//end switch

?>
<div class="nc-progress-holder">
	<div class="nc-progress-bar" style="background:<?php echo $background; ?>"></div>
</div><!-- .nc-progress-holder -->
