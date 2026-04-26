<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_newsletter' ) ) {
	function foxiz_register_options_newsletter() {

		return [
			'id'     => 'foxiz_theme_ops_section_subscribe',
			'title'  => esc_html__( 'Popup Newsletter', 'foxiz' ),
			'desc'   => esc_html__( 'Customize the popup newsletter.', 'foxiz' ),
			'icon'   => 'el el-envelope',
			'fields' => [
				[
					'id'       => 'newsletter_popup',
					'type'     => 'select',
					'title'    => esc_html__( 'Popup Newsletter', 'foxiz' ),
					'subtitle' => esc_html__( 'Choose whether to enable or disable the popup newsletter.', 'foxiz' ),
					'options'  => [
						'0' => esc_html__( 'Disable', 'foxiz' ),
						'1' => esc_html__( 'Center Popup', 'foxiz' ),
						'2' => esc_html__( 'Fixed Right', 'foxiz' ),
					],
					'default'  => '0',
				],
				[
					'id'       => 'newsletter_title',
					'type'     => 'textarea',
					'rows'     => 2,
					'title'    => esc_html__( 'Title', 'foxiz' ),
					'subtitle' => esc_html__( 'Input title for the popup newsletter form', 'foxiz' ),
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => esc_html__( 'Join Us!', 'foxiz' ),
				],
				[
					'id'       => 'newsletter_description',
					'type'     => 'textarea',
					'rows'     => 2,
					'title'    => esc_html__( 'Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input description for the popup newsletter form', 'foxiz' ),
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => esc_html__( 'Subscribe to our newsletter and never miss our latest news, podcasts etc.', 'foxiz' ),
				],
				[
					'id'       => 'newsletter_shortcode',
					'type'     => 'text',
					'title'    => esc_html__( 'Newsletter Shortcode', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a newsletter shortcode.', 'foxiz' ),
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => '[mc4wp_form]',
				],
				[
					'id'       => 'newsletter_footer',
					'type'     => 'text',
					'title'    => esc_html__( 'Footer Text', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a footer text for the box.', 'foxiz' ),
					'default'  => esc_html__( 'Zero spam, Unsubscribe at any time.', 'foxiz' ),
					'required' => [ 'newsletter_popup', '!=', 0 ],
				],
				[
					'id'       => 'newsletter_footer_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Footer URL', 'foxiz' ),
					'subtitle' => esc_html__( 'Add a link for the footer text (optional).', 'foxiz' ),
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => '',
				],
				[
					'id'          => 'newsletter_cover',
					'title'       => esc_html__( 'Cover Image', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a cover image for the box.', 'foxiz' ),
					'description' => esc_html__( 'It is recommended to use a dark image to ensure sufficient text contrast when selecting the FIXED RIGHT position.', 'foxiz' ),
					'type'        => 'media',
					'url'         => true,
					'preview'     => true,
					'required'    => [ 'newsletter_popup', '!=', 0 ],
				],
				[
					'id'          => 'newsletter_popup_expired',
					'type'        => 'select',
					'title'       => esc_html__( 'Popup Expiration', 'foxiz' ),
					'subtitle'    => esc_html__( 'Set how long to wait before showing the popup again after a visitor closes it.', 'foxiz' ),
					'description' => esc_html__( 'The "Immediately (for Preview)" option should be used only for previewing the settings.', 'foxiz' ),
					'options'     => [
						'1'  => esc_html__( '1 Day', 'foxiz' ),
						'2'  => esc_html__( '2 Days', 'foxiz' ),
						'3'  => esc_html__( '3 Days', 'foxiz' ),
						'7'  => esc_html__( '1 Week', 'foxiz' ),
						'14' => esc_html__( '2 Weeks', 'foxiz' ),
						'21' => esc_html__( '3 Weeks', 'foxiz' ),
						'30' => esc_html__( '1 Month', 'foxiz' ),
						'0'  => esc_html__( 'Immediately (for Preview)', 'foxiz' ),
					],
					'required'    => [ 'newsletter_popup', '!=', 0 ],
					'default'     => '1',
				],
				[
					'id'       => 'newsletter_popup_display',
					'type'     => 'select',
					'title'    => esc_html__( 'Display Mode', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a mode to display the newsletter popup.', 'foxiz' ),
					'options'  => [
						'scroll' => esc_html__( 'Scroll Distance', 'foxiz' ),
						'time'   => esc_html__( 'Time Delay', 'foxiz' ),
					],
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => 'scroll',
				],
				[
					'id'       => 'newsletter_popup_offset',
					'type'     => 'text',
					'title'    => esc_html__( 'Distance of Scroll', 'foxiz' ),
					'subtitle' => esc_html__( 'This option use for "Scroll Distance" mode. Input a distance value (in pixels) when visitor scrolling down to show the popup.', 'foxiz' ),
					'class'    => 'small',
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => '2000',
				],
				[
					'id'       => 'newsletter_popup_delay',
					'type'     => 'text',
					'title'    => esc_html__( 'Delay Time', 'foxiz' ),
					'subtitle' => esc_html__( 'This option use for "Time Delay" mode. Input a delay time (ms) value to show the popup after the site loaded.', 'foxiz' ),
					'class'    => 'small',
					'required' => [ 'newsletter_popup', '!=', 0 ],
					'default'  => '',
				],
			],
		];
	}
}