<?php
/**
 * This partial is the whole account page, with some placeholders for
 * eventual child views.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/account
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$free_plan_trivia = array(
	sprintf(
		_x( 'Did you know&hellip;? With our <a href="%s">Nelio Content Personal</a> you\'ll be able to use more social profiles on each social network.', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
	sprintf(
		_x( 'Did you know&hellip;? If you subscribe to <a href="%s">any of our plans</a>, you\'ll be able to schedule your social messages at any time.', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
	sprintf(
		_x( 'Hey! If you\'re a start-up, <a href="%s">Nelio Content Team</a> is the plan you need. Coordinate your team and make sure everyone is on the same page!', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
);

$monthly_personal_trivia = array(
	sprintf(
		_x( 'Did you know&hellip;? You can pay your subscription on a yearly basis and save a lot of money! Take a look at <a href="%s">our pricing.</a>', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
	sprintf(
		_x( 'Hey! If you\'re a start-up, <a href="%s">Nelio Content Team</a> is the plan you need. Coordinate your team and make sure everyone is on the same page!', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
	sprintf(
		_x( 'Is your business growing? Are you planning on incorporating new members to your team? Then take a look at <a href="%s">Nelio Content Team</a>&dash;the plan you need.', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
);

$yearly_personal_trivia = array(
	sprintf(
		_x( 'Hey! If you\'re a start-up, <a href="%s">Nelio Content Team</a> is the plan you need. Coordinate your team and make sure everyone is on the same page!', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
	sprintf(
		_x( 'Is your business growing? Are you planning on incorporating new members to your team? Then take a look at <a href="%s">Nelio Content Team</a>&dash;the plan you need.', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
);

$monthly_team_trivia = array(
	sprintf(
		_x( 'Did you know&hellip;? You can pay your subscription on a yearly basis and save a lot of money! Take a look at <a href="%s">our pricing.</a>', 'user', 'nelio-content' ),
		esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
	),
);

// Promotion messages for invited people.
$promote_messages = array(
	_x( 'Write better content in #WordPress with %s\'s plugin #NelioContent', 'text (account, share text)', 'nelio-content' ),
	_x( '#NelioContent is an awesome #EditorialCalendar for #WordPress, by %s', 'text (account, share text)', 'nelio-content' ),
	_x( 'I\'m using #NelioContent by %s in my #WordPress site and it\'s great!', 'text (account, share text)', 'nelio-content' ),
	_x( 'Want to promote your content easily? Check this out: #NelioContent by %s', 'text (account, share text)', 'nelio-content' ),
	_x( 'The guys at %s created a great #WordPress plugin. Take a look at #NelioContent', 'text (account, share text)', 'nelio-content' ),
);
$count = count( $promote_messages );
/* translators: We only have two twitter accounts, @NelioSoft in English and @NelioSoft_ES in Spanish. */
$nelio_twitter = _x( '@NelioSoft', 'text (Nelio\'s twitter username)', 'nelio-content' );
$twitter_message = sprintf( $promote_messages[ mt_rand( 0, $count - 1 ) ], $nelio_twitter );
$twitter_message = str_replace( '#', '%23', $twitter_message );

?>

<script type="text/template" id="_nc-account-page">

	<div class="nc-contact-info-and-plan nc-<%= subscription %>">

		<div class="nc-account-plan-container">
			<div class="nc-account-plan nc-box<% if ( 'none' !== subscription && 'canceled' === state ) { %> nc-subscription-canceled<% } %><% if ( 'free-trial' === mode ) { %> nc-free-trial<% } %>">

				<div class="nc-content">

					<h3>

						<% if ( 'none' === subscription ) { %>
							<?php echo esc_html_x( 'Free Version', 'title (account)', 'nelio-content' ); ?>
						<% } else if ( 'personal-plan' === subscription ) { %>
							<?php echo esc_html_x( 'Personal Plan', 'title (account)', 'nelio-content' ); ?>
						<% } else if ( 'team-plan' === subscription ) { %>
							<?php echo esc_html_x( 'Team Plan', 'title (account)', 'nelio-content' ); ?>
						<% } else { %>
							<?php echo esc_html_x( 'Subscription', 'title (account)', 'nelio-content' ); ?>
						<% } %>

						<% if ( 'none' === subscription ) { %>

							<?php // Nothing to be done. ?>

						<% } else if ( 'free-trial' === mode ) { %>

							<span class="nc-period nc-free-trial"><?php
								echo esc_html_x( 'Premium Trial', 'text (account, subscription period)', 'nelio-content' );
							?></span>

						<% } else if ( 'invitation' === mode ) { %>

							<span class="nc-period"><?php
								echo esc_html_x( 'Invitation', 'text (account, subscription period)', 'nelio-content' );
							?></span>

						<% } else if ( 'monthly' === period ) { %>

							<% if ( 'canceled' === state ) { %>
								<span class="nc-period"><?php
									echo esc_html_x( 'Monthly (canceled)', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							<% } else { %>
								<span class="nc-period"><?php
									echo esc_html_x( 'Monthly', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							<% } %>

						<% } else if ( 'yearly' === period ) { %>

							<% if ( 'canceled' === state ) { %>
								<span class="nc-period"><?php
									echo esc_html_x( 'Yearly (canceled)', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							<% } else { %>
								<span class="nc-period"><?php
									echo esc_html_x( 'Yearly', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							<% } %>

						<% } %>

					</h3>

					<% if ( 'free-trial' === mode ) { %>

						<div class="nc-renewal"><?php
							printf( // @codingStandardsIgnoreLine
								_x( '<strong>This trial will end on %1$s</strong>. Please consider subscribing to our service before this occurs.', 'user', 'nelio-content' ),
								'<span class="nc-date"><%= endDateFormatted %></span>'
							);
						?></div>

					<% } else if ( 'invitation' === mode && 'personal-plan' === subscription ) { %>

						<div class="nc-renewal"><?php
							echo esc_html_x( 'You\'re currently using a Free Pass to Nelio Content\'s Personal Plan. Enjoy the plugin and, please, help us improve it with your feedback!', 'user', 'nelio-content' );
						?></div>

					<% } else if ( 'invitation' === mode && 'team-plan' === subscription ) { %>

						<div class="nc-renewal"><?php
							echo esc_html_x( 'You\'re currently using a Free Pass to Nelio Content\'s Team Plan. Enjoy its features and, please, let us know how we can improve it!', 'user', 'nelio-content' );
						?></div>

					<% } else if ( 'active' === state && 'none' === subscription ) { %>

						<div class="nc-renewal"><?php
							echo esc_html_x( 'You\'re currently using the Free Version of Nelio Content. Enjoy it!', 'user', 'nelio-content' );
						?></div>

					<% } else if ( 'active' === state && 'none' !== subscription ) { %>

						<div class="nc-renewal"><?php
							printf( // @codingStandardsIgnoreLine
							_x( 'Next charge will be %1$s on %2$s.', 'text (e.g. "Next charge will be $99.00 on December 1, 2016.")', 'nelio-content' ),
								'<span class="nc-money"><%= _.escape( nextChargeTotal ) %></span>',
								'<span class="nc-date"><%= nextRenewalDateFormatted  %></span>'
							);
						?></div>

					<% } else { %>

						<div class="nc-renewal"><?php
							printf( // @codingStandardsIgnoreLine
								_x( 'Your subscription will end on %1$s.', 'text (e.g. "Your subscription will end on December 1, 2016.")', 'nelio-content' ),
								'<span class="nc-date"><%= endDateFormatted %></span>'
							);
						?></div>

					<% } %>

					<div class="nc-trivia">

						<% if ( 'canceled' === state ) { %>

							<?php echo esc_html_x( 'On the previous date, your account will be downgraded to the Free Version of Nelio Content.', 'user', 'nelio-content' ); ?>

						<% } else { %>

							<?php // @codingStandardsIgnoreStart ?>

							<% if ( 'invitation' === mode || 'free-trial' === mode ) { %>

								<?php // Nothing to be done ?>

							<% } else if ( 'none' === subscription ) { %>

								<?php
								$key = array_rand( $free_plan_trivia );
								echo $free_plan_trivia[ $key ];
								?>

							<% } else if ( 'personal-plan' === subscription && 'monthly' === period ) { %>

								<?php
								$key = array_rand( $monthly_personal_trivia );
								echo $monthly_personal_trivia [ $key ];
								?>

							<% } else if ( 'personal-plan' === subscription && 'yearly' === period ) { %>

								<?php
								$key = array_rand( $yearly_personal_trivia );
								echo $yearly_personal_trivia [ $key ];
								?>

							<% } else if ( 'team-plan' === subscription && 'monthly' === period ) { %>

								<?php
								$key = array_rand( $monthly_team_trivia );
								echo $monthly_team_trivia [ $key ];
								?>

							<% } %>

							<?php // @codingStandardsIgnoreEnd ?>

						<% } %>

					</div><!-- .nc-trivia -->

				</div><!-- .nc-content -->

				<div class="nc-actions">

					<% if ( 'invitation' === mode ) { %>

						<?php
						$url = 'https://twitter.com/intent/tweet';
						$url = add_query_arg( 'text', $twitter_message, $url );
						?>
						<a target="_blank" class="button button-primary" href="<?php
								echo esc_url( $url );
							?>"><?php
							echo esc_html_x( 'Tweet About Nelio Content', 'user', 'nelio-content' );
						?></a>

					<% } else if ( 'none' !== modifyLicenseKeyStatus ) { %>

						<table>
							<tr>

								<td class="nc-license">
									<input class="nc-license-key" type="text" value="<%= _.escape( newLicenseKey ) %>" placeholder="<?php
										echo esc_attr_x( 'License Key', 'text', 'nelio-content' );
									?>"<% if ( 'validating' === modifyLicenseKeyStatus ) { %> disabled="disabled"<% } %> />
								</div>

								<td>
									<button class="button nc-hide-license-form"<% if ( 'validating' === modifyLicenseKeyStatus ) { %> disabled="disabled"<% } %>><?php
										echo esc_html_x( 'Cancel', 'command', 'nelio-content' );
									?></button>
								</td>

								<% if ( 'validating' === modifyLicenseKeyStatus ) { %>
									<td>
										<button class="button button-primary nc-validate-license" disabled="disabled"><?php
											echo esc_html_x( 'Validating&hellip;', 'command (license key)', 'nelio-content' );
										?></button>
									</td>
								<% } else { %>
									<td>
										<button class="button button-primary nc-validate-license"><?php
											echo esc_html_x( 'Validate', 'command (license key)', 'nelio-content' );
										?></button>
									</td>
								<% } %>

							</tr>
						</table>

					<% } else if ( freeTrialOption ) { %>

						<button class="button nc-use-license-key"><?php
							echo esc_html_x( 'Use License Key', 'command', 'nelio-content' );
						?></button>

						<button class="button button-primary nc-start-free-trial"><?php
							echo esc_html_x( 'Try Out Premium', 'command', 'nelio-content' );
						?></button>

					<% } else if ( 'none' === subscription || 'free-trial' === mode ) { %>

						<button class="button nc-use-license-key"><?php
							echo esc_html_x( 'Use License Key', 'command', 'nelio-content' );
						?></button>

						<button class="button button-primary nc-subscribe"><?php
							echo esc_html_x( 'Subscribe', 'command', 'nelio-content' );
						?></button>

					<% } else if ( 'active' === state ) { %>

						<% if ( 'team-plan' === subscription && 'yearly' === period ) { %>

							<button class="button nc-delete-button nc-cancel-subscription"><?php
								echo esc_html_x( 'Cancel Subscription', 'command', 'nelio-content' );
							?></button>

						<% } else { %>

							<button class="button nc-delete-button nc-cancel-subscription"><?php
								echo esc_html_x( 'Cancel Subscription', 'command', 'nelio-content' );
							?></button>

							<button class="button button-primary nc-upgrade"><?php
								echo esc_html_x( 'Upgrade', 'command', 'nelio-content' );
							?></button>

						<% } %>

					<% } else { %>

						<button class="button nc-use-license-key"><?php
							echo esc_html_x( 'Use New License Key', 'command', 'nelio-content' );
						?></button>

						<button class="button button-primary nc-reactivate"><?php
							echo esc_html_x( 'Reactivate Subscription', 'command', 'nelio-content' );
						?></button>

					<% } %>

				</div><!-- .nc-actions -->

			</div><!-- .nc-account-plan -->
		</div><!-- .nc-account-plan-container -->

		<% if ( 'none' !== subscription ) { %>

			<div class="nc-contact-info-container">
				<div class="nc-contact-info nc-box">

					<h3><?php echo esc_html_x( 'Additional Information', 'title (account)', 'nelio-content' ); ?></h3>

					<div class="nc-picture-and-details">

						<div class="nc-profile-picture-container">

							<div class="nc-profile-picture nc-first-letter-<%= firstLetter %>">
								<div class="nc-actual-profile-picture" style="background-image: url( <%= _.escape( photo ) %> )"></div>
							</div><!-- .nc-profile-picture -->

						</div><!-- .nc-profile-picture-container -->

						<div class="nc-contact-info-details">

							<p class="nc-name"><%= fullnameFormatted %></p>

							<p class="nc-email">
								<span class="nc-dashicons nc-dashicons-email" style="margin-top:-1px;"></span>
								<%= _.escape( email ) %>
							</p><!-- .nc-email -->

							<p class="nc-creation">
								<span class="nc-dashicons nc-dashicons-calendar"></span>
								<?php
								printf( // @codingStandardsIgnoreLine
									_x( 'Member since %s', 'text (account)', 'nelio-content' ),
									'<%= creationDateFormatted %>'
								);
								?>
							</p><!-- .nc-creation -->

							<% if ( 'free-trial' !== mode ) { %>

								<p class="nc-license">
									<span class="nc-dashicons nc-dashicons-admin-network" style="margin-top:-2px;"></span>
									<code title="<?php
										echo esc_attr_x( 'License Key', 'text', 'nelio-content' );
									?>"><%= license %></code>
								</p><!-- .nc-license -->

							<% } %>

						</div><!-- .nc-contact-info-details -->

					</div><!-- .nc-picture-and-info -->

				</div><!-- .nc-contact-info -->
			</div><!-- .nc-contact-info-container -->

		<% } %>

	</div><!-- .nc-contact-info-and-plan -->

	<?php
	$wp_authors = get_users( array(
		'blog_id' => $GLOBALS['blog_id'],
		'who'     => 'authors',
	) );

	if ( count( $wp_authors ) > 1 ) { ?>

		<% if ( 'team-plan' !== subscription ) { %>

			<div class="nc-manager nc-box">

				<h3><?php echo esc_html_x( 'Plugin Manager', 'title', 'nelio-content' ); ?></h3>

				<p><?php
					printf( // @codingStandardsIgnoreLine
						_x( 'You\'re currently using a personal plan, which means that only one user can benefit from the plugin\'s features at a time. If you want to make the plugin available to <strong>all</strong> contributors, authors, and editors in the site, please consider upgrading to our <a href="%s">Team Plan</a>.', 'user', 'nelio-content' ),
						esc_url( __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) )
					);
				?></p>

				<p><?php echo esc_html_x( 'The following user will have full access to Nelio Content:', 'user', 'nelio-content' ); ?></p>

				<div class="nc-manager-placeholder"></div>

			</div><!-- .nc-manager -->

		<% } %>

	<?php
	} ?>

	<% if ( 'none' !== subscription && 'regular' === mode ) { %>

		<div class="nc-billing nc-box">

			<h3><?php echo esc_html_x( 'Billing History', 'title', 'nelio-content' ); ?></h3>

			<?php
			$container_name = 'nc-billing-table';
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';
			?>

		</div><!-- .nc-billing -->

	<% } %>

</script><!-- #_nc-account-page -->
