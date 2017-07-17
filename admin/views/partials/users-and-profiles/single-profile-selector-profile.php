<?php
/**
 * This partial is used by a Select2 component and determines how a single
 * social profile has to be displayed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
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

<script type="text/template" id="_nc-single-profile-selector-profile">

	<div class="ncselect2-result nc-single-profile">

		<div class="nc-profile">

			<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
				<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
			</div><!-- .nc-picture -->
			<div class="nc-network nc-<%= network %> nc-<%= kind %>"></div>

		</div><!-- .nc-profile -->

		<div class="nc-name-and-username">

			<span class="nc-name"><%= displayNameEscaped %></span>

			<span class="nc-username"><%= usernameEscaped %></span>

		</div><!-- .nc-name-and-username -->

	</div><!-- .ncselect2-result.single-profile -->

</script><!-- #_nc-single-profile-selector-profile -->

