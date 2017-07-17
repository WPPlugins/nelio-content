<?php
/**
 * This partial contains a free trial notice.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.3
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

if ( ! nc_is_free_trial() ) {
	return;
}//end if

$subscription = nc_get_subscription();
if ( ! isset( $subscription['endDate'] ) ) {
	return;
}//end if

$ft_end_date = $subscription['endDate'];
$ft_days_left = floor( ( strtotime( $ft_end_date ) - time() ) / 3600 / 24 ) + 1;
if ( 0 > $ft_days_left ) {
	return;
}//end if

if ( 5 >= $ft_days_left ) {
	$ft_class = 'nc-last-period';
} else if ( 15 >= $ft_days_left ) {
	$ft_class = 'nc-second-period';
} else {
	$ft_class = 'nc-first-period';
}//end if

$ft_sentence = sprintf(
	esc_html( _nx( '%d day left', '%d days left', 'text', 'nelio-content' ) ),
	$ft_days_left
);
if ( 1 >= $ft_days_left ) {
	$ft_sentence = '<span class="nc-dashicons nc-dashicons-warning"></span> ' . _x( 'Last day', 'text (free-trial)', 'nelio-content' );
}//end if

printf(
	'<a class="nc-free-trial-counter-notice %s" href="%s" title="%s"><span class="nc-text">%s</span><span class="nc-days-left">%s</span></a>',
	$ft_class,
	admin_url( 'admin.php?page=nelio-content-account' ),
	esc_attr_x( 'View Details&hellip;', 'command', 'nelio-content' ),
	esc_html_x( 'Premium Trial', 'text', 'nelio-content' ),
	$ft_sentence
);

