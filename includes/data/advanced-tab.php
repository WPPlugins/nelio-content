<?php
/**
 * List of settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

return array(

	array(
		'type'  => 'section',
		'name'  => 'advanced-setups',
		'label' => _x( 'Plugin Setup', 'text', 'nelio-content' ),
	),

	array(
		'type'     => 'custom',
		'name'     => 'calendar_post_types',
		'label'    => _x( 'Managed Post Types', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Calendar_Post_Type_Setting(),
		'default'  => array( 'post' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_custom_post_statuses',
		'label'   => _x( 'Custom Post Statuses', 'text', 'nelio-content' ),
		'desc'    => _x( 'Add custom post statuses (such as "Idea", "Assigned", or "In Progress") to managed post types.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'uses_proxy',
		'label'   => _x( 'API Proxy', 'text', 'nelio-content' ),
		'desc'    => _x( 'My server doesn\'t support SNI. Use Nelio\'s secure proxy to access the API.', 'command', 'nelio-content' ),
		'default' => false,
	),

);
