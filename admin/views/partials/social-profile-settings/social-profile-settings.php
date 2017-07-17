<?php
/**
 * This partial contains the list of available networks for which we can connect social profiles.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
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

// URL to which our cloud has to redirect the user once a social profile has been successfully completed.
$redirect_url = add_query_arg( 'nc-page', 'connected-profile-callback', admin_url( 'admin.php' ) );

?>

<script type="text/template" id="_nc-social-profile-settings">

	<h2><?php echo esc_html_x( 'Add New', 'title (social profile)', 'nelio-content' ); ?></h2>

	<p><?php
		echo esc_html_x( 'Connect your social media profiles to Nelio Content.', 'user', 'nelio-content' );
	?></p>

	<div class="nc-networks">

		<?php

		// TWITTER.
		$warning = esc_attr_x( 'The maximum number of Twitter profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'twitter';
		$condition = 'isTwitterEnabled';
		$name = esc_html_x( 'Twitter', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/twitter', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		// FACEBOOK.
		$warning = esc_attr_x( 'The maximum number of Facebook profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'facebook-personal';
		$condition = 'isFacebookEnabled';
		$name = esc_html_x( 'Facebook', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/facebook', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		$network = 'facebook-page';
		$condition = 'isFacebookEnabled';
		$name = esc_html_x( 'Facebook Page', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/facebook/page', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		$network = 'facebook-group';
		$condition = 'isFacebookEnabled';
		$name = esc_html_x( 'Facebook Group', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/facebook/group', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		// GOOGLE PLUS.
		$warning = esc_attr_x( 'The maximum number of Google Plus profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'googleplus';
		$condition = 'isGooglePlusEnabled';
		$name = _x( 'Google+<br>(via Buffer)', 'text', 'nelio-content' );
		$url = nc_get_api_url( '/connect/googleplus', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		$network = 'googleplus-page';
		$condition = 'isGooglePlusEnabled';
		$name = _x( 'Google+ Page<br>(via Buffer)', 'text', 'nelio-content' );
		$url = nc_get_api_url( '/connect/googleplus/page', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		// LINKEDIN.
		$warning = esc_attr_x( 'The maximum number of LinkedIn profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'linkedin-personal';
		$condition = 'isLinkedInEnabled';
		$name = esc_html_x( 'LinkedIn', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/linkedin', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		$network = 'linkedin-company';
		$condition = 'isLinkedInEnabled';
		$name = esc_html_x( 'LinkedIn Company', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/linkedin/company', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		// INSTAGRAM.
		$warning = esc_attr_x( 'The maximum number of Instagram profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'instagram';
		$condition = 'isInstagramEnabled';
		$name = _x( 'Instagram Reminders<br>(via Buffer)', 'text', 'nelio-content' );
		$url = nc_get_api_url( '/connect/instagram', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		// PINTEREST.
		$warning = esc_attr_x( 'The maximum number of Pinterest profiles you may configure has been reached.', 'text', 'nelio-content' );

		$network = 'pinterest';
		$condition = 'isPinterestEnabled';
		$name = esc_html_x( 'Pinterest', 'text', 'nelio-content' ) . '<br>&nbsp;';
		$url = nc_get_api_url( '/connect/pinterest', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

		?>

	</div><!-- .nc-networks -->


	<% if ( isLoadingSocialProfiles ) { %>

		<div class="nc-checking-available-profiles">
			<span class="spinner is-active"></span>
			<?php echo esc_html_x( 'Checking if there are any social profiles connected to Nelio Content&hellip;', 'text (social profile settings)', 'nelio-content' ); ?>
		</div><!-- .nc-checking-available-profiles -->

	<% } else if ( numOfConnectedProfiles > 0 ) { %>

		<h2>
			<?php echo esc_html_x( 'Connected Profiles', 'text', 'nelio-content' ); ?>
			<span class="nc-connected-profile-counter"><span class="nc-count"><%= numOfConnectedProfiles %></span><span class="nc-total">/<%= maxNumOfProfiles %></span></span>
		</h2>

		<p><?php
			echo esc_html_x( 'The following profiles can be managed by any author in your team:', 'text', 'nelio-content' );
		?></p>

		<div class="nc-connected-profiles"></div>

	<% } %>

</script><!-- #_nc-social-profile-settings -->

