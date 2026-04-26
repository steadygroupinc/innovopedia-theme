<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Updater' ) ) {
	class Foxiz_Updater {

		protected static $instance = null;

		static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;

			add_action( 'upgrader_process_complete', [ $this, 'async_upgrade' ], 10, 2 );
		}

		function async_upgrade( $upgrader, $options ) {

			if ( isset( $options['type'], $options['plugins'] ) && $options['type'] === 'plugin' && in_array( 'foxiz-core/foxiz-core.php', $options['plugins'], true ) ) {

				$this->clear_update_flags();
				$this->update_submenu_colors();
			}
		}

		public function clear_update_flags() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			delete_option( '_rb_flag_update_logo' );
			delete_option( '_rb_flag_update_tax_meta' );
		}

		public function update_submenu_colors() {

			$flag = get_option( '_rb_flag_update', false );

			if ( $flag || ! current_user_can( 'manage_options' ) || is_network_admin() ) {
				return;
			}

			$settings     = get_option( FOXIZ_TOS_ID, [] );
			$header_style = ! empty( $settings['header_style'] ) ? (int) $settings['header_style'] : 1;

			if ( $header_style === 4 || $header_style === 5 ) {

				set_transient( FOXIZ_TOS_ID, $settings, 2592000 );
				set_transient( '_RB_UPGRADER_BACKUP_TOPS', $settings, 2592000 );

				$prefix = 'hd' . $header_style . '_sub_';

				if ( ! empty( $settings[ $prefix . 'background' ] ) ) {
					$settings['hd1_sub_background'] = $settings[ $prefix . 'background' ];
				}
				if ( ! empty( $settings[ 'dark_' . $prefix . 'background' ] ) ) {
					$settings['dark_hd1_sub_background'] = $settings[ 'dark_' . $prefix . 'background' ];
				}
				if ( ! empty( $settings[ $prefix . 'color_hover' ] ) ) {
					$settings['hd1_sub_color_hover'] = $settings[ $prefix . 'color_hover' ];
				}
				if ( ! empty( $settings[ $prefix . 'bg_hover ' ] ) ) {
					$settings['hd1_sub_bg_hover'] = $settings[ $prefix . 'bg_hover ' ];
				}
				if ( ! empty( $settings[ 'dark_' . $prefix . 'color' ] ) ) {
					$settings['dark_hd1_sub_color'] = $settings[ 'dark_' . $prefix . 'color' ];
				}
				if ( ! empty( $settings[ 'dark_' . $prefix . 'color' ] ) ) {
					$settings['dark_hd1_sub_color'] = $settings[ 'dark_' . $prefix . 'color' ];
				}
				if ( ! empty( $settings[ 'dark_' . $prefix . 'color_hover' ] ) ) {
					$settings['dark_hd1_sub_color_hover'] = $settings[ 'dark_' . $prefix . 'color_hover' ];
				}
				if ( ! empty( $settings[ 'dark_' . $prefix . 'bg_hover' ] ) ) {
					$settings['dark_hd1_sub_bg_hover'] = $settings[ 'dark_' . $prefix . 'bg_hover' ];
				}

				update_option( FOXIZ_TOS_ID, $settings );
			}

			/** set flag */
			update_option( '_rb_flag_update', 'ver_2_5_6' );
		}
	}
}

/** init */
Foxiz_Updater::get_instance();
