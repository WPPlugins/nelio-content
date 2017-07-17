<?php
/**
 * This partial is the skeletton of the social message editor.
 *
 * It allows users to create social messages, select the social profiles where
 * those messages will be shared, and preview them using the look and feel of
 * all social networks.
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
}//end if

?>
<script type="text/template" id="_nc-social-message-editor">
	<div class="nc-social-message-editor">

		<div class="nc-profile-selector"></div>

		<div class="nc-editor"></div>

		<div class="nc-social-preview"></div>

		<div class="nc-scheduling nc-date-and-time">

			<div class="nc-date"></div>

			<div class="nc-time"></div>

		</div><!-- .nc-scheduling.date-and-time -->

	</div><!--.social-message-editor -->
</script><!-- #_nc-social-message-editor -->

