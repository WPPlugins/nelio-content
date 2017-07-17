<?php
/**
 * This partial tells the user that no social profiles are available.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-no-profile-available">

<div class="nc-no-social-profile-available"><?php

		$url = admin_url( 'admin.php' );
		$url = add_query_arg( 'page', 'nelio-content-settings', $url );
		printf( // @codingStandardsIgnoreLine
			_x( 'Nelio Content helps you to engage your audience and promote your WordPress contents in social media. <a href="%s">Connect one or more social profiles</a> and expand your limits!', 'user', 'nelio-content' ),
			esc_url( $url )
		);

	?></div><!-- .nc-no-social-profile-available -->

</script><!-- #_nc-no-profile-available -->
