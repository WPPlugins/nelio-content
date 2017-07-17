<?php
/**
 * The underscore template for rendering the featured image meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/featured-image
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
$click_to_edit = esc_html_x( 'Click the image to edit or update', 'user', 'nelio-content' );

?>

<script type="text/template" id="_nc-featured-image">

<?php
if ( $settings->get( 'use_single_feat_img_meta_box' ) ) { ?>

	<input type="hidden" name="_nc-featured-image-meta-box" value="true" />

	<% if ( 'wp' === mode ) { %>

		<input type="hidden" name="_thumbnail_id" value="<%= thumbnailId %>" />
		<div class="nc-image-container">
			<img class="nc-edit-wp" src="<%= _.escape( url ) %>" alt="<%= _.escape( alt ) %>" />
			<div class="nc-source"><?php
				echo esc_html_x( 'Media Library', 'text', 'nelio-content' );
			?></div>
		</div>

		<p class="nc-click-to-edit"><?php echo $click_to_edit; ?></p>

		<p><a href="#" class="nc-remove-featured-image"><?php
				echo esc_html_x( 'Remove featured image', 'text', 'nelio-content' );
		?></a></p>

	<% } else if ( 'efi' === mode ) { %>

<?php
} else { ?>

	<% if ( 'efi' === mode ) { %>

<?php
} ?>

		<input type="hidden" name="_nelioefi_url" value="<%= url %>" />
		<input type="hidden" name="_nelioefi_alt" value="<%= _.escape( alt ) %>" />
		<div class="nc-image-container">
			<img class="nc-edit-efi" src="<%= _.escape( url ) %>" alt="<%= _.escape( alt ) %>" />
			<?php
			if ( $settings->get( 'use_single_feat_img_meta_box' ) ) { ?>
				<div class="nc-source"><?php
					echo esc_html_x( 'External Source', 'text', 'nelio-content' );
				?></div>
			<?php
			} ?>
		</div>

		<p class="nc-click-to-edit"><?php echo $click_to_edit; ?></p>

		<p><a href="#" class="nc-remove-featured-image"><?php
				echo esc_html_x( 'Remove featured image', 'text', 'nelio-content' );
		?></a></p>

	<% } else { %>

		<?php
		if ( $settings->get( 'use_single_feat_img_meta_box' ) ) { ?>

			<p><?php
				/* Translators: after this string, there are two buttons: "Media Library" and "External". Users can set the featured image of a post using an image from the media library or the URL of an external image */
				echo esc_html_x( 'Set featured image from&hellip;', 'command', 'nelio-content' );
			?></p>

			<div class="nc-actions">
				<button class="button nc-set-wp"><span class="nc-dashicons nc-dashicons-admin-media"></span><?php
					echo esc_html_x( 'Media', 'text', 'nelio-content' );
				?></button>
				<button class="button nc-set-efi"><span class="nc-dashicons nc-dashicons-admin-links"></span><?php
					echo esc_html_x( 'External', 'text', 'nelio-content' );
				?></button>
			</div>

		<?php
		} else { ?>

			<p><a href="#" class="nc-set-efi"><?php
				echo esc_html_x( 'Set external featured image', 'action', 'nelio-content' );
			?></a></p>

		<?php
		} ?>

	<% } %>

</script><!-- #_nc-featured-image -->
