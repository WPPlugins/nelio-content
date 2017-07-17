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

<script type="text/template" id="_nc-single-profile-selector-target">

	<span class="nc-single-profile-selector-target">

		<span class="nc-target-picture">

			<span class="nc-target-placeholder">
				<span class="nc-actual-target-picture" style="background-image: url( <%= _.escape( image ) %> );"></span>
			</span><!-- .nc-target-placeholder -->

		</span><!-- .nc-target-picture -->

		<%= displayNameEscaped %>

	</span><!-- .nc-single-profile-selector-target -->

</script><!-- #_nc-single-profile-selector-target -->

