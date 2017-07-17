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
}

return array(

	array(
		'type'  => 'section',
		'name'  => 'calendar',
		'label' => _x( 'Calendar', 'text', 'nelio-content' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'colorize_calendar',
		'label'   => _x( 'Post Status Colors', 'text', 'nelio-content' ),
		'desc'    => _x( 'Enable colors in calendar based on post status.', 'command', 'nelio-content' ),
		'default' => true,
	),

	array(
		'type'  => 'section',
		'name'  => 'post-quality-settings',
		'label' => _x( 'Post Quality', 'text', 'nelio-content' ),
	),

	array(
		'type'    => 'select',
		'name'    => 'qa_min_word_count',
		/* translators: a single setting's label */
		'label'   => _x( 'Post Length (words)', 'text', 'nelio-content' ),
		'desc'    => _x( 'How many words your blog posts should have. If a post has fewer words, Nelio Content will warn the author and suggest him to write more content.', 'text', 'nelio-content' ),
		'default' => '500',
		'options' => array(
			array(
				'value' => '300',
				'label' => number_format_i18n( 300 ),
			),
			array(
				'value' => '500',
				'label' => number_format_i18n( 500 ),
			),
			array(
				'value' => '800',
				'label' => number_format_i18n( 800 ),
			),
			array(
				'value' => '1000',
				'label' => number_format_i18n( 1000 ),
			),
			array(
				'value' => '1200',
				'label' => number_format_i18n( 1200 ),
			),
			array(
				'value' => '1500',
				'label' => number_format_i18n( 1500 ),
			),
			array(
				'value' => '2000',
				'label' => number_format_i18n( 2000 ),
			),
		),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'qa_is_yoast_seo_integrated',
		'label'   => _x( 'Yoast SEO Integration', 'text', 'nelio-content' ),
		'desc'    => _x( 'Integrate Yoast SEO score in Nelio Content\'s post analysis.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'  => 'section',
		'name'  => 'analytics',
		'label' => _x( 'Analytics', 'text', 'nelio-content' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_analytics',
		'label'   => _x( 'Basic', 'text (analytics)', 'nelio-content' ),
		'desc'    => _x( 'Enable analytics for Nelio Content\'s managed post types.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'     => 'custom',
		'name'     => 'google_analytics_view',
		'label'    => _x( 'Analytics Data', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Google_Analytics_Setting(),
		'default'  => false,
	),

	array(
		'type'  => 'section',
		'name'  => 'nelioefi',
		'label' => _x( 'External Featured Images', 'text', 'nelio-content' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_single_feat_img_meta_box',
		'label'   => _x( 'Featured Image Meta Box', 'text', 'nelio-content' ),
		'desc'    => _x( 'Replace WordPress\' featured image meta box with Nelio\'s.', 'command', 'nelio-content' ),
		'default' => true,
	),

	array(
		'type'    => 'select',
		'name'    => 'efi_mode',
		'label'   => _x( 'Mode', 'text', 'nelio-content' ),
		'desc'    => _x( 'Themes can insert featured images in different ways. For example, some themes use a WordPress function named <code>(get_)the_post_thumbnail</code> whereas others use a combination of <code>wp_get_attachment_image_src</code> and <code>get_post_thumbnail_id</code>. Depending on how your theme operates, Nelio Content may or many not be compatible with it. In order to maximize the number of compatible themes, the plugin implements different <em>modes</em>.', 'html', 'nelio-content' ),
		'default' => 'default',
		'options' => array(
			array(
				'value' => 'default',
				'label' => _x( 'Default Mode', 'text', 'nelio-content' ),
				'desc'  => _x( 'This mode assumes your theme uses the function <code>(get_)the_post_thumbnail</code> for inserting featured images. For example, WordPress default themes should work with this setting.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'double-quotes',
				'label' => _x( 'Double-Quote Mode', 'text', 'nelio-content' ),
				'desc'  => _x( 'If your theme retrieves the URL of the featured image and outputs it within an <code>img</code> tag, this mode might be the one you need. Compatible themes include Newspaper, Newsmag, Enfold, and others.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'single-quotes',
				'label' => _x( 'Single-Quote Mode', 'text', 'nelio-content' ),
				'desc'  => _x( 'Equivalent to «Double-Quote Mode», but using single quotes instead.', 'text', 'nelio-content' ),
			),
		),
	),

	array(
		'type'    => 'select',
		'name'    => 'auto_feat_image',
		'label'   => _x( 'Autoset Featured Image', 'text', 'nelio-content' ),
		'desc'    => _x( 'If a post doesn\'t have a featured image set, Nelio Content can set it automatically for you. To do this, it looks for all the images included in the post and uses one of them as the featured image.', 'text', 'nelio-content' ),
		'default' => 'disabled',
		'options' => array(
			array(
				'value' => 'disabled',
				'label' => _x( 'Disabled', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content doesn\'t set the featured image automatically.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'first',
				'label' => _x( 'Use First Image in Post', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content will use the first image included in the post.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'any',
				'label' => _x( 'Use Any Image In Post', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content will use one of the images included in the post, selecting it randomly. If there are more than two images, Nelio Content will ignore the first and the last image.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'last',
				'label' => _x( 'Use Last Image In Post', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content will use the last image included in the post.', 'text', 'nelio-content' ),
			),
		),
	),

);
