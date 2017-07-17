<?php
/**
 * This file contains the template for rendering a user option in a ncselect2 user selector.
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

<script type="text/template" id="_nc-user-selector-option">

	<div class="ncselect2-result nc-single-user">

		<div class="nc-profile">

			<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
				<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
			</div><!-- .nc-picture -->

		</div><!-- .nc-profile -->

		<div class="nc-name-and-email">

			<span class="nc-name"><%= name %></span>

			<span class="nc-email"><%= email %></span>

		</div><!-- .nc-name-and-email -->

	</div><!-- .ncselect2-result.single-profile -->

</script><!-- #_nc-user-selector-option -->

