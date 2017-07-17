<?php
/**
 * This file contains the template for rendering the selected user option in a ncselect2 user selector.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-tasks
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
}

?>

<script type="text/template" id="_nc-user-selector-selected-option">

	<span class="nc-selected-user">

		<%= _.escape( name ) %>

		<span class="nc-email">(<%= _.escape( email ) %>)</span>

	</div><!-- .nc-selected-profile -->

</script><!-- #_nc-user-selector-selected-option -->

