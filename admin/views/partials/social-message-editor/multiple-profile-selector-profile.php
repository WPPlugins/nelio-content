<?php
/**
 * This partial depicts a social network in the social message editor.
 *
 * It includes some information about that profile:
 *
 * * The picture of the user.
 * * Whether it's selected or not.
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

<script type="text/template" id="_nc-multiple-profile-selector-profile">

	<% if ( disabled ) { %>

	<div class="nc-profile nc-selectable nc-disabled"
		title="<?php echo esc_attr_x( 'Subscribe to Nelio Content and schedule more messages for this profile', 'error', 'nelio-content' ); ?>"
		data-profile="<%= id %>">

	<% } else { %>

	<div class="nc-profile<% if ( selected ) { %> nc-selected<% }  %> nc-selectable"
		title="<%= _.escape( displayName ) %>"
		data-profile="<%= id %>">

	<% } %>

		<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
			<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> );"></div>
		</div><!-- .nc-picture -->

		<div class="nc-selection-mark<%
			if ( allowsMultiTargets ) {
				%> nc-multi-targets nc-targets-<%= targetCount %><%
			}
		%>"></div>

	</div><!-- .nc-profile -->

</script><!-- #_nc-multiple-profile-selector-profile -->

