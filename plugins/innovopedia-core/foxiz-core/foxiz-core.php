<?php
/**
 * Plugin Name:    Innovopedia Core
 * Plugin URI:     https://innovopedia.com
 * Author URI:     https://innovopedia.com
 * Description:    Core features for the Innovopedia Platform. This is a required plugin for the theme.
 * Version:        2.7.4
 * Requires at least: 6.0
 * Requires PHP:   7.4
 * Text Domain:    innovopedia-core
 * Domain Path:    /languages/
 * Author:         Innovopedia Team
 *
 * @package        innovopedia-core
 * License:        GNU General Public License v2 or later
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( 'ABSPATH' ) || exit;

define( 'INNOVOPEDIA_CORE_VERSION', '2.7.4' );
define( 'INNOVOPEDIA_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'INNOVOPEDIA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'INNOVOPEDIA_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
defined( 'INNOVOPEDIA_TOS_ID' ) || define( 'INNOVOPEDIA_TOS_ID', 'innovopedia_theme_options' );
defined( 'RB_META_ID' ) || define( 'RB_META_ID', 'rb_global_meta' );

/** LOAD FILES */
include_once INNOVOPEDIA_CORE_PATH . 'includes/file.php';

if ( ! class_exists( 'INNOVOPEDIA_CORE', false ) ) {
	class INNOVOPEDIA_CORE {

		private static $instance;

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		public function __construct() {

			self::$instance = $this;
			register_activation_hook( __FILE__, [ $this, 'activation' ] );
			add_action( 'plugins_loaded', [ $this, 'translation' ], 100 );
			add_action( 'wp_enqueue_scripts', [ $this, 'core_enqueue' ], 1 );
			add_action( 'widgets_init', [ $this, 'register_widgets' ] );
		}

		public function translation() {

			$loaded = load_plugin_textdomain( 'innovopedia-core', false, INNOVOPEDIA_CORE_PATH . 'languages/' );
			if ( ! $loaded ) {
				$locale = apply_filters( 'plugin_locale', get_locale(), 'innovopedia-core' );
				$mofile = INNOVOPEDIA_CORE_PATH . 'languages/innovopedia-core-' . $locale . '.mo';
				load_textdomain( 'innovopedia-core', $mofile );
			}
		}

		public function core_enqueue() {

			if ( is_admin() || foxiz_is_amp() ) {
				return;
			}

			$deps = [ 'jquery' ];
			wp_register_style( 'innovopedia-core', INNOVOPEDIA_CORE_URL . 'assets/core.js', $deps, INNOVOPEDIA_CORE_VERSION, true );

			$fonts = get_option( 'rb_adobe_fonts', [] );
			if ( ! empty( $fonts['project_id'] ) ) {
				wp_enqueue_style( 'adobe-fonts', esc_url_raw( 'https://use.typekit.net/' . esc_html( $fonts['project_id'] ) . '.css' ), [], false, 'all' );
			}

			wp_register_style( 'innovopedia-admin-bar', INNOVOPEDIA_CORE_URL . 'assets/admin-bar.css', [], INNOVOPEDIA_CORE_VERSION );
			wp_register_script( 'innovopedia-core', INNOVOPEDIA_CORE_URL . 'assets/core.js', $deps, INNOVOPEDIA_CORE_VERSION, true );

			$js_params     = [
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'darkModeID'   => $this->get_dark_mode_id(),
				'cookieDomain' => defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '',
				'cookiePath'   => defined( 'COOKIEPATH' ) ? COOKIEPATH : '/',
			];
			$multi_site_id = $this->get_multisite_subfolder();
			if ( $multi_site_id ) {
				$js_params['mSiteID'] = $multi_site_id;
			}
			wp_localize_script( 'innovopedia-core', 'innovopediaCoreParams', $js_params );
			wp_enqueue_script( 'innovopedia-core' );

			if ( is_admin_bar_showing() ) {
				wp_enqueue_style( 'innovopedia-admin-bar' );
			}
		}

		public function get_dark_mode_id() {

			if ( is_multisite() ) {
				return 'D_' . trim( str_replace( '/', '_', preg_replace( '/https?:\/\/(www\.)?/', '', get_site_url() ) ) );
			}

			return 'RubyDarkMode';
		}

		public function get_multisite_subfolder() {

			if ( is_multisite() ) {
				$site_info = get_blog_details( get_current_blog_id() );
				$path      = $site_info->path;

				if ( ! empty( $path ) && '/' !== $path ) {
					return trim( str_replace( '/', '', $path ) );
				} else {
					return false;
				}
			}

			return false;
		}

		/**
		 * @return false
		 */
		public function register_widgets() {

			$widgets = [
				'Innovopedia_W_Post',
				'Innovopedia_W_Follower',
				'Innovopedia_W_Weather',
				'Innovopedia_Fw_Instagram',
				'Innovopedia_W_Social_Icon',
				'Innovopedia_W_Youtube_Subscribe',
				'Innovopedia_W_Flickr',
				'Innovopedia_W_Address',
				'Innovopedia_W_Instagram',
				'Innovopedia_Fw_Mc',
				'Innovopedia_Ad_Image',
				'Innovopedia_FW_Banner',
				'Innovopedia_W_Facebook',
				'Innovopedia_Ad_Script',
				'Innovopedia_W_Ruby_Template',
			];

			foreach ( $widgets as $widget ) {
				$widget = str_replace('Innovopedia', 'Foxiz', $widget); // Maintain compatibility with original widget classes
				if ( class_exists( $widget ) ) {
					register_widget( $widget );
				}
			}

			return false;
		}

		/**
		 * @param $network
		 */
		public function activation( $network ) {
			if ( is_multisite() && $network ) {
				global $wpdb;
				$blogs_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
				foreach ( $blogs_ids as $blog_id ) {
					switch_to_blog( (int) $blog_id );
					$this->create_db();
					restore_current_blog();
				}
			} else {
				$this->create_db();
			}
		}

		public function create_db() {
			if ( class_exists('Foxiz_Personalize_Db') ) {
				new Foxiz_Personalize_Db();
			}
		}

	}
}

/** LOAD */
INNOVOPEDIA_CORE::get_instance();