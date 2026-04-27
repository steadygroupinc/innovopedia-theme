<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use function foxiz_elementor_popup_template;

/**
 * Class Popup_Template
 *
 * @package foxizElementor\Widgets
 */
class Popup_Template extends Widget_Base {

	public function get_name() {

		return 'foxiz-popup-template';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Popup Template', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-lightbox-expand';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'popup', 'action', 'button', 'lightbox', 'menu', 'header', 'trigger' ];
	}

	public function get_categories() {

		return [ 'foxiz_element' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'popup_content',
			[
				'label'       => esc_html__( 'Popup Content Template or RAW HTML', 'foxiz-core' ),
				'description' => esc_html__( 'Enter a template or custom HTML content for the popup.', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 4,
				'placeholder' => '[Ruby_E_Template id="1"]',
			]
		);
		$this->add_control(
			'yes_js_tag',
			[
				'label'       => esc_html__( 'Has Script Tags?', 'foxiz-core' ),
				'description' => esc_html__( 'Enable this option if you have embedded the script tag in your content template.', 'elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'foxiz-core' ),
				'label_off'   => esc_html__( 'No', 'foxiz-core' ),
				'default'     => '',
			]
		);
		$this->add_control(
			'btn_label',
			[
				'label'   => esc_html__( 'Trigger Button Label', 'foxiz-core' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => esc_html__( 'Menu', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_icon',
			[
				'label'       => esc_html__( 'Trigger Button Icon (SVG Attachment)', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'rows'        => 2,
				'description' => esc_html__( 'Enter the attachment link for your SVG icon for the trigger button.', 'foxiz-core' ),
				'placeholder' => esc_html__( 'https://yourdomain.com/wp-content/uploads/....filename.svg', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .popup-trigger-svg' => 'mask-image: url({{VALUE}}); -webkit-mask-image: url({{VALUE}}); background-image: none;',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Trigger Button', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'       => esc_html__( 'Button Font', 'foxiz-core' ),
				'description' => esc_html__( 'Choose the font style and size for the button text label.', 'foxiz-core' ),
				'name'        => 'button_font',
				'selector'    => '{{WRAPPER}} .popup-trigger-btn',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_border',
				'selector' => '{{WRAPPER}} .popup-trigger-btn',
			]
		);
		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .popup-trigger-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_border',
			[
				'label'      => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .popup-trigger-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'btn_effects' );
		$this->start_controls_tab( 'btn_normal',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--popup-trigger-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .popup-trigger-btn',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_normal_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .popup-trigger-btn',
			]
		);
		$this->add_control(
			'btn_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--popup-trigger-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .popup-trigger-btn' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .popup-trigger-btn',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'btn_hover',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'btn_hover_normal_default',
			[
				'label' => esc_html__( 'Default Mode', 'foxiz-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'btn_hover_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => '--popup-trigger-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_border_hover_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_hover_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .popup-trigger-btn:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_hover_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .popup-trigger-btn:hover',
			]
		);
		$this->add_control(
			'btn_hover_normal_dark',
			[
				'label'     => esc_html__( 'Dark Mode', 'foxiz-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_hover_dark_color',
			[
				'label'     => esc_html__( 'Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}}' => '--popup-trigger-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_hover_dark_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'[data-theme="dark"] {{WRAPPER}} .popup-trigger-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'btn_hover_dark_bg',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '[data-theme="dark"] {{WRAPPER}} .popup-trigger-btn:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_control(
			'btn_transition',
			[
				'label'      => esc_html__( 'Transition Duration', 'foxiz-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'default'    => [
					'unit' => 'ms',
				],
				'selectors'  => [
					'{{WRAPPER}} .popup-trigger-btn' => 'transition-duration: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'btn_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'foxiz-core' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Trigger Button Icon', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label'       => esc_html__( 'Icon Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify a custom size for the SVG icon on the button.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}}' => '--wnav-size: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'       => esc_html__( 'Icon Spacing', 'foxiz-core' ),
				'description' => esc_html__( 'Set the custom spacing between the icon and the text label.', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [ '{{WRAPPER}}' => '--popup-trigger-gap: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'icon_align',
			[
				'label'     => esc_html__( 'Icon Position', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'row',
				'options'   => [
					'row'         => [
						'title' => esc_html__( 'Start', 'foxiz-core' ),
						'icon'  => "eicon-h-align-left",
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'foxiz-core' ),
						'icon'  => "eicon-h-align-right",
					],
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-btn' => 'flex-direction: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_popup',
			[
				'label' => esc_html__( 'Popup Position & Animation', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'popup_position',
			[
				'label'       => esc_html__( 'Position & Animation', 'foxiz-core' ),
				'description' => esc_html__( 'Specify the position and animation for the popup content.', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'ai'          => [ 'active' => false ],
				'options'     => [
					'rb-popup-left'   => esc_html__( 'From Left Screen', 'foxiz-core' ),
					'rb-popup-top'    => esc_html__( 'From Top Screen', 'foxiz-core' ),
					'rb-popup-right'  => esc_html__( 'From Right Screen', 'foxiz-core' ),
					'rb-popup-center' => esc_html__( 'From Center', 'foxiz-core' ),
				],
				'default'     => 'rb-popup-left',
			]
		);
		$this->add_control(
			'popup_bg', [
				'label'       => esc_html__( 'Content Background', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background for the popup content.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'.popup-uid_{{ID}}' => '--popup-bg: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'dark_popup_bg', [
				'label'       => esc_html__( 'Dark - Content Background', 'foxiz-core' ),
				'description' => esc_html__( 'Select a background for the popup content in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] .popup-uid_{{ID}}' => '--popup-bg: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'close_btn_color', [
				'label'       => esc_html__( 'Close Button Color', 'foxiz-core' ),
				'description' => esc_html__( 'The close button background has the same color as the content background. Select a color for the close button.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'.popup-uid_{{ID}}' => '--popup-close-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'dark_close_btn_color', [
				'label'       => esc_html__( 'Dark Mode - Close Button Color', 'foxiz-core' ),
				'description' => esc_html__( 'Choose a color for the close button in dark mode.', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'[data-theme="dark"] .popup-uid_{{ID}}' => '--popup-close-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'close_btn_size',
			[
				'label'       => esc_html__( 'Close Button Size', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify a custom size for the close button.', 'foxiz-core' ),
				'placeholder' => '52',
				'selectors'   => [
					'.popup-uid_{{ID}}' => '--popup-close-size: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_popup_bg',
			[
				'label' => esc_html__( 'Popup Screen Background', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'screen_bg',
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '.mfp-bg.popup-uid_{{ID}}',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'dark_screen_bg',
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '[data-theme="dark"] .mfp-bg.popup-uid_{{ID}}',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		$settings         = $this->get_settings();
		$settings['uuid'] = 'uid_' . $this->get_id();

		foxiz_elementor_popup_template( $settings );
	}

}