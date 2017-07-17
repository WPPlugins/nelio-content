<?php
/**
 * Nelio Content subscription-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This function returns information about the current subscription.
 *
 * @return array information about the current subscription.
 *
 * @since 1.0.0
 */
function nc_get_subscription() {

	return get_option( 'nc_subscription', array(
		'creationDate' => '',
		'endDate'      => '',
		'email'        => '',
		'firstname'    => '',
		'lastname'     => '',
		'license'      => '',
		'mode'         => 'regular',
		'period'       => 'none',
		'plan'         => 'none',
		'state'        => 'active',
	) );

}//end nc_get_subscription()

/**
 * This function returns the plan the user is subscribed to. If he's using the
 * Community Edition, `none` is returned.
 *
 * @return string the plan the user is subscribed to.
 *
 * @since 1.0.0
 */
function nc_get_subscription_plan() {

	$subscription = nc_get_subscription();
	if ( 'none' === $subscription['plan'] ) {
		return 'none';
	} else {
		return $subscription['plan'] . '-plan';
	}//end if

}//end nc_get_subscription_plan()

/**
 * Returns whether the current user is using the free trial version of the plugin.
 *
 * @return boolean whether the current user is using the free trial version of the plugin.
 *
 * @since 1.2.3
 */
function nc_is_free_trial() {

	$subscription = nc_get_subscription();
	return isset( $subscription['mode'] ) && 'free-trial' === $subscription['mode'];

}//end nc_is_free_trial()

/**
 * Returns whether the current user is a paying customer or not.
 *
 * @return boolean whether the current user is a paying customer or not.
 *
 * @since 1.0.0
 */
function nc_is_subscribed() {

	return nc_get_subscription_plan() !== 'none';

}//end nc_is_subscribed()

/**
 * Returns whether the user is subscribed to the specified plan or not.
 *
 * @param string $req_plan The plan the user should be subscribed to.
 * @param string $mode     Optional. If `exactly`, the current user must be
 *                         subscribed to the required plan. If `or-above`, the
 *                         required plan or an superior plan would return true.
 *                         Default: `exactly`.
 *
 * @return boolean whether the user is subscribed to the specified plan or not.
 *
 * @since 1.0.0
 */
function nc_is_subscribed_to( $req_plan, $mode = 'exactly' ) {

	if ( ! nc_is_subscribed() ) {
		return false;
	}//end if

	// Check if the plan the user is subscribed to is exactly the required plan.
	$plan = nc_get_subscription_plan();
	if ( $req_plan === $plan ) {
		return true;
	}//end if

	// If it isn't, let's check if the plan he's subscribed to is, at least, the
	// required plan.
	if ( 'or-above' === $mode ) {

		// Reminder: this switch should contain all possible plans. Right now,
		// though, there's only two plans (personal and team), so we only need
		// the lowest plan.
		switch ( $req_plan ) {

			case 'personal-plan':
				return true;

		}//end switch

	}//end if

	return false;

}//end nc_is_subscribed_to()

/**
 * Returns the plan associated to the given product.
 *
 * @param string $product A FastSpring product.
 *
 * @return string the plan associated to the given product.
 *
 * @since 1.2.0
 */
function nc_get_product_plan( $product ) {

	// TODO. This should be removed at some point.
	$product = str_replace( 'nelio-content-', 'nc-', $product );

	switch ( $product ) {

		case 'nc-auto-team-yearly':
		case 'nc-auto-team-monthly':
		case 'nc-team-yearly':
		case 'nc-team-monthly':
			return 'team';

		case 'nc-auto-personal-yearly':
		case 'nc-auto-personal-monthly':
		case 'nc-personal-yearly':
		case 'nc-personal-monthly':
			return 'personal';

		default:
			return 'none';

	}//end switch

}//end nc_get_product_plan()

/**
 * Returns the period associated to the given product.
 *
 * @param string $product A FastSpring product.
 *
 * @return string the period associated to the given product.
 *
 * @since 1.2.0
 */
function nc_get_product_period( $product ) {

	// TODO. This should be removed at some point.
	$product = str_replace( 'nelio-content-', 'nc-', $product );

	switch ( $product ) {

		case 'nc-auto-team-yearly':
		case 'nc-auto-personal-yearly':
		case 'nc-team-yearly':
		case 'nc-personal-yearly':
			return 'yearly';

		case 'nc-auto-team-monthly':
		case 'nc-auto-personal-monthly':
		case 'nc-team-monthly':
		case 'nc-personal-monthly':
			return 'monthly';

		default:
			return 'none';

	}//end switch

}//end nc_get_product_period()

/**
 * This helper function checks whether the user can start the free trial or not
 * and, if they can, which plan should they ask for.
 *
 * @return mixed false if the user can't start free trial. Otherwise, the plan
 *               they can start.
 *
 * @since  1.2.3
 */
function nc_get_possible_free_trial_plan() {

	// TODO. Reimplement this function.
	return false;

}//end nc_get_possible_free_trial_plan()

/**
 * This helper function updates the subscription information (as stored in
 * the `nc_subscription` option) using the site information received from
 * AWS.
 *
 * @param array $site_info site information received from AWS.
 *
 * @since  1.2.0
 */
function nc_update_subscription_information_with_site_object( $site_info ) {

	// If the site info we obtained is incomplete or is not related to this
	// site, just leave.
	if ( ! isset( $site_info['id'] ) || nc_get_site_id() !== $site_info['id'] ) {
		return;
	}//end if

	update_option( 'nc_site_limits', array(
		'maxProfiles'           => $site_info['maxProfiles'],
		'maxProfilesPerNetwork' => $site_info['maxProfilesPerNetwork'],
	) );

	// If site info doesn't contain any information about a subscription, save
	// a "free version" subscription object.
	if ( ! isset( $site_info['subscription'] ) ) {

		update_option( 'nc_subscription', array(
			'creationDate' => $site_info['creation'],
			'endDate'      => '',
			'email'        => '',
			'firstname'    => '',
			'mode'         => 'regular',
			'lastname'     => '',
			'license'      => '',
			'period'       => 'none',
			'plan'         => 'none',
			'state'        => 'active',
		) );

		return;

	}//end if

	$license = '';
	if ( isset( $site_info['subscription']['license'] ) ) {
		$license = $site_info['subscription']['license'];
	}//end if

	// If we do have information about the subscription, update it.
	$subscription = array(
		'creationDate' => $site_info['creation'],
		'endDate'      => '',
		'email'        => $site_info['subscription']['account']['email'],
		'firstname'    => $site_info['subscription']['account']['firstname'],
		'lastname'     => $site_info['subscription']['account']['lastname'],
		'license'      => $license,
		'mode'         => $site_info['subscription']['mode'],
		'period'       => false, // To be initialized.
		'plan'         => false, // To be initialized.
		'state'        => false, // To be initialized.
	);

	// Initialize plan and period.
	$product = $site_info['subscription']['product'];
	$subscription['period'] = nc_get_product_period( $product );
	$subscription['plan'] = nc_get_product_plan( $product );

	if ( isset( $site_info['subscription']['endDate'] ) ) {
		$subscription['endDate'] = $site_info['subscription']['endDate'];
	}//end if

	// Update status.
	if ( 'canceled' === $site_info['subscription']['state'] ) {
		$subscription['state'] = 'canceled';
	} else {
		$subscription['state'] = 'active';
	}//end if

	update_option( 'nc_subscription', $subscription );

}//end nc_update_subscription_information_with_site_object()
