<?php
/**
 * This partial renders the social timeline box when the post hasn't been saved yet.
 *
 * In other words, it shows an empty box with with a message asking the user to
 * save the post.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/social-timeline
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-no-social-timeline-on-auto-draft">

<div class="nc-no-social-timeline-on-auto-draft"><?php

	echo esc_html_x( 'Save post to access social timeline.', 'user', 'nelio-content' );

	?></div><!-- .nc-no-social-timeline-on-auto-draft -->

</script><!-- #_nc-no-social-timeline-on-auto-draft -->
