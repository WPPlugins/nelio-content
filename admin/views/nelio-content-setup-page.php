<?php
/**
 * Displays the UI for initializing the plugin.
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

switch ( get_user_option( 'admin_color' ) ) {

	case 'light':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/light.png' );
		break;

	case 'blue':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/blue.png' );
		break;

	case 'coffee':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/coffee.png' );
		break;

	case 'ectoplasm':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/ectoplasm.png' );
		break;

	case 'midnight':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/midnight.png' );
		break;

	case 'ocean':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/ocean.png' );
		break;

	case 'sunrise':
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/sunrise.png' );
		break;

	default:
		$image_url = esc_url( NELIO_CONTENT_ADMIN_URL . '/images/colored-logo/default.png' );

}//end switch

?>

<div class="wrap">

	<div class="nc-setup-message">

		<img src="<?php
			echo $image_url; // @codingStandardsIgnoreLine
		?>" title="<?php
			echo esc_attr_x( 'Nelio Content Logo', 'text', 'nelio-content' );
		?>" />

		<div class="nc-plugin-moto"><?php
			echo esc_html_x( 'Auto-post, schedule, and share your posts on social media. Save time with useful automations.', 'user', 'nelio-content' );
		?></div>

		<div class="nc-thanks-message"><?php
			echo _x( '<strong>Nelio Content</strong> is almost ready!', 'user', 'nelio-content' ); // @codingStandardsIgnoreLine
		?></div>

		<div class="nc-terms"><?php
			printf( // @codingStandardsIgnoreLine
				_x( 'Just click on <em>Continue</em> to accept <a target="_blank" href="%s">Nelio terms</a> and access your calendar.', 'user', 'nelio-content' ),
				esc_url( _x( 'https://neliosoftware.com/nelio-content-terms-conditions/', 'text', 'nelio-content' ) )
			);
		?></div>

		<div class="nc-setup-form">

			<input type="text" class="nc-license-field" style="display:none;" disabled="disabled" placeholder="<?php
				echo esc_attr_x( 'License Key', 'text', 'nelio-content' );
			?>" />

			<p class="nc-actions">

				<div class="nc-regular-actions">

					<button class="button button-primary button-hero nc-use-free-version"><?php
						echo esc_html_x( 'Continue »', 'command', 'nelio-content' );
					?></button>

				</div><!-- .nc-regular-actions -->

			</p><!-- .nc-actions -->

		</div><!-- .nc-setup-form -->

		<div class="nc-error-message"></div>

	</div><!-- .nc-setup-message -->

	<script type="text/javascript">
	(function( $ ) {

		var $continueButton = $( '.nc-use-free-version' );
		var $errorMessage = $( '.nc-error-message' );

		// Use Free Version.
		$continueButton.on( 'click', function() {

			$continueButton.prop( 'disabled', true );
			$errorMessage.html( '' );
			$continueButton.html( <?php
				$spinner = '<span class="nc-dashicons nc-dashicons-update nc-animate-spinner"></span> ';
				echo wp_json_encode( $spinner . _x( 'Preparing your calendar&hellip;', 'user', 'nelio-content' ) );
			?> );

			$.ajax({

				url: ajaxurl,
				data: { action: 'nelio_content_start_free_version' },

				success: function() {
					window.location.href = <?php
						echo wp_json_encode( esc_url( admin_url( 'admin.php?page=nelio-content' ) ) );
					?>;
				},//end success

				error: function( xhr ) {
					$continueButton.html( <?php
						echo wp_json_encode( _x( 'Continue »', 'command', 'nelio-content' ) );
					?> );
					$errorMessage.html( '<span class="nc-dashicons nc-dashicons-warning"></span> ' + xhr.responseJSON.replace( /</g, '&lt;' ) );
					$continueButton.prop( 'disabled', false );
				}//end error()

			});

		});

	})( jQuery );
	</script>

</div><!-- .wrap -->

