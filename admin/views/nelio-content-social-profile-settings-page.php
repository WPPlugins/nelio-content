<?php
/**
 * Displays only the tab for configuring social profiles.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

?>

<div class="wrap">

	<h2><?php esc_html_e( 'Nelio Content - Social Profile Settings', 'nelio-content' ); ?></h2>

	<?php include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-settings-tab.php' ); ?>

</div><!-- .wrap -->

