<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_adblock' ) ) {
	function foxiz_register_options_adblock() {

		return [
			'id'     => 'foxiz_config_section_adblock',
			'title'  => esc_html__( 'AdBlock Detector', 'foxiz' ),
			'desc'   => esc_html__( 'Detecting most of the AdBlock extensions and show a popup to disable the extension.', 'foxiz' ),
			'icon'   => 'el el-minus-sign',
			'fields' => [
				[
					'id'          => 'adblock_detector',
					'title'       => esc_html__( 'AdBlock Detector', 'foxiz' ),
					'subtitle'    => esc_html__( 'Enable or disable the AdBlock detector.', 'foxiz' ),
					'description' => esc_html__( 'Select a detection method. The "Both Methods" option provides the most reliable detection.', 'foxiz' ),
					'type'        => 'select',
					'options'     => [
						'0' => esc_html__( '- Disable -', 'foxiz' ),
						'1' => esc_html__( 'Bait Element Method', 'foxiz' ),
						'2' => esc_html__( 'External Script Detection', 'foxiz' ),
						'3' => esc_html__( 'Both Methods (Recommended)', 'foxiz' ),
					],
					'default'     => '0',
				],
				[
					'id'       => 'adblock_title',
					'title'    => esc_html__( 'Title', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a title for the adblock popup.', 'foxiz' ),
					'type'     => 'text',
					'required' => [ 'adblock_detector', '!=', 0 ],
					'default'  => esc_html__( 'AdBlock Detected', 'foxiz' ),
				],
				[
					'id'       => 'adblock_description',
					'title'    => esc_html__( 'Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Input a description for the adblock popup.', 'foxiz' ),
					'type'     => 'textarea',
					'rows'     => 2,
					'required' => [ 'adblock_detector', '!=', 0 ],
					'default'  => esc_html__( 'Our site is an advertising supported site. Please whitelist to support our site.', 'foxiz' ),
				],
			],
		];
	}
}
