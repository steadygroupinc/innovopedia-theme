<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;
/**
 * PERMANENT LICENSE BYPASS & CUSTOMIZATION FREEDOM
 * This block ensures the theme and core plugin always see a "Registered" status.
 */
add_filter( 'pre_option__Ruby_Activation', '__return_true' );
add_filter( 'pre_option_foxiz_license_id', function() {
	return [
		'is_activated'  => 1,
		'purchase_code' => 'OYLITE00-0000-0000-0000-5199BAEE264D'
	];
} );
add_filter( 'pre_option__licfoxiz_license_id', function() {
	return [ 'licensed' => true ];
} );

add_action( 'after_setup_theme', function() {
	update_option( '_ruby_validated', '' );
	set_site_transient( '_licfoxiz_license_id', true );
	update_option( 'ruby_api_keys', [
		'expiration' => '__foxiz_expiration',
		'activation' => '__foxiz_activation',
	] );
	update_option( '__foxiz_expiration', strtotime( '+1 year' ) );
	update_option( '__foxiz_activation', 'active' );
}, 0 );

/** Hide Update & Registration Nags */
add_action( 'admin_head', function() {
	echo '<style>
		.foxiz-update-notice, 
		.foxiz-register-notice, 
		#foxiz-core-update-notice,
		.notice-warning[data-notice="foxiz-update"],
		#toplevel_page_foxiz-admin .wp-submenu li:has(a[href*="page=foxiz-admin"]),
		.foxiz-admin-nav-item.registration,
		.foxiz-admin-nav-item.register { 
			display: none !important; 
		}
	</style>';
} );

/** Deep Clean: Remove Registration Menu Pages */
add_action( 'admin_menu', function() {
	// Remove the Registration subpage if it exists
	remove_submenu_page( 'foxiz-admin', 'foxiz-admin' );
	// Add a redirect or just hide it
}, 999 );

add_action( 'init', function() {
    add_filter( 'pre_http_request', function( $pre, $post_args, $url ) {
        if ( strpos( $url, 'https://api.themeruby.com/' ) !== false ) {
            $query_args = [];
            parse_str( parse_url( $url, PHP_URL_QUERY ), $query_args );
            $url_path = parse_url( $url, PHP_URL_PATH );

            // Handle Registration Mock
            if ( ( $url_path == '/wp-json/market/validate' ) || ( $url_path == '/market/cvalid' ) ) {
                $response = [
                    'code'    => 200,
                    'message' => 'Success',
                    'data'    => [
                        'purchase_info' => [
                            'is_activated'  => 1,
                            'purchase_code' => 'OYLITE00-0000-0000-0000-5199BAEE264D'
                        ],
                        'import' => []
                    ]
                ];
                return [
                    'response' => [ 'code' => 200, 'message' => 'OK' ],
                    'body'     => json_encode( $response )
                ];
            }

            // Handle Demos & Imports Mock
            if ( ( $url_path == '/wp-json/market/validate' ) && isset( $query_args['action'] ) && $query_args['action'] == 'demos' ) {
                $response = wp_remote_get(
                    "http://wordpressnull.org/foxiz/demos.json",
                    [ 'sslverify' => false, 'timeout' => 60 ]
                );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            } elseif ( ( $url_path == '/import/' ) && isset( $query_args['demo'] ) && isset( $query_args['data'] ) ) {
                $ext = in_array( $query_args['data'], ['content', 'pages'] ) ? '.xml' : '.json';
                $response = wp_remote_get(
                    "http://wordpressnull.org/foxiz/demos/{$query_args['demo']}/{$query_args['data']}{$ext}",
                    [ 'sslverify' => false, 'timeout' => 300 ]
                );
                if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
                    return $response;
                }
            }
        }
        return $pre;
    }, 10, 3 );
} );
define( 'INNOVOPEDIA_THEME_VERSION', '2.7.6' );
define( 'INNOVOPEDIA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'INNOVOPEDIA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'INNOVOPEDIA_CHILD_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'INNOVOPEDIA_CHILD_THEME_URI', trailingslashit( esc_url( get_stylesheet_directory_uri() ) ) );
defined( 'INNOVOPEDIA_TOS_ID' ) || define( 'INNOVOPEDIA_TOS_ID', 'innovopedia_theme_options' );

/** Backward Compatibility for Foxiz Core */
defined( 'FOXIZ_THEME_VERSION' ) || define( 'FOXIZ_THEME_VERSION', INNOVOPEDIA_THEME_VERSION );
defined( 'FOXIZ_THEME_DIR' ) || define( 'FOXIZ_THEME_DIR', INNOVOPEDIA_THEME_DIR );
defined( 'FOXIZ_THEME_URI' ) || define( 'FOXIZ_THEME_URI', INNOVOPEDIA_THEME_URI );
defined( 'FOXIZ_CHILD_THEME_DIR' ) || define( 'FOXIZ_CHILD_THEME_DIR', INNOVOPEDIA_CHILD_THEME_DIR );
defined( 'FOXIZ_CHILD_THEME_URI' ) || define( 'FOXIZ_CHILD_THEME_URI', INNOVOPEDIA_CHILD_THEME_URI );
defined( 'FOXIZ_TOS_ID' ) || define( 'FOXIZ_TOS_ID', INNOVOPEDIA_TOS_ID );

require_once INNOVOPEDIA_THEME_DIR . 'includes/core-functions.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/file.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/ai-briefing.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/ai-search.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/personalization.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/newsletter.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/toolkit.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/charts.php';
require_once INNOVOPEDIA_THEME_DIR . 'includes/briefing-player.php';

add_action( 'after_setup_theme', 'innovopedia_theme_setup', 10 );
add_action( 'wp_enqueue_scripts', 'innovopedia_register_script_frontend', 990 );

/** setup */
if ( ! function_exists( 'innovopedia_theme_setup' ) ) {
	function innovopedia_theme_setup() {

		// load_theme_textdomain
		$locale          = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
		$loco_path       = WP_LANG_DIR . '/loco/themes/innovopedia-' . $locale . '.mo';
		$theme_lang_path = WP_LANG_DIR . '/themes/innovopedia-' . $locale . '.mo';

		if ( is_readable( $loco_path ) ) {
			load_textdomain( 'innovopedia', $loco_path );
		} elseif ( file_exists( $theme_lang_path ) ) {
			load_textdomain( 'innovopedia', $theme_lang_path );
		} else {
			load_theme_textdomain( 'innovopedia', get_theme_file_path( 'languages' ) );
		}

		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 1240;
		}

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);
		add_theme_support( 'post-formats', [ 'gallery', 'video', 'audio' ] );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support(
			'woocommerce',
			[
				'gallery_thumbnail_image_width' => 110,
				'thumbnail_image_width'         => 300,
				'single_image_width'            => 760,
			]
		);
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		if ( ! foxiz_get_option( 'widget_block_editor' ) ) {
			remove_theme_support( 'widgets-block-editor' );
		}
		register_nav_menus(
			[
				'innovopedia_main'         => esc_html__( 'Main Menu', 'innovopedia' ),
				'innovopedia_mobile'       => esc_html__( 'Mobile Menu', 'innovopedia' ),
				'innovopedia_mobile_quick' => esc_html__( 'Mobile Quick Access', 'innovopedia' ),
			]
		);

		$sizes = foxiz_calc_crop_sizes();
		foreach ( $sizes as $crop_id => $size ) {
			add_image_size( $crop_id, $size[0], $size[1], $size[2] );
		}
	}
}

/* register scripts */
if ( ! function_exists( 'innovopedia_register_script_frontend' ) ) {
	function innovopedia_register_script_frontend() {

		$style_deps  = [];
		$script_deps = [
			'jquery',
			'jquery-waypoints',
			'rbswiper',
			'jquery-magnific-popup',
		];

		$main_filename        = 'main';
		$woocommerce_filename = 'woocommerce';
		$podcast_filename     = 'podcast';

		if ( is_rtl() ) {
			$main_filename        = 'rtl';
			$woocommerce_filename = 'woocommerce-rtl';
			$podcast_filename     = 'podcast-rtl';
		}

		$gfont_url = Foxiz_Font::get_font_url();

		if ( ! empty( $gfont_url ) ) {
			wp_register_style( 'foxiz-font', esc_url_raw( $gfont_url ), [], FOXIZ_THEME_VERSION, 'all' );
			$style_deps[] = 'foxiz-font';
		}

		if ( foxiz_get_option( 'font_awesome' ) ) {
			wp_deregister_style( 'font-awesome' );
			wp_register_style( 'font-awesome', foxiz_get_file_uri( 'assets/css/font-awesome.css' ), [], '6.1.1', 'all' );
			$style_deps[] = 'font-awesome';
		}

		wp_register_style( 'foxiz-main', foxiz_get_file_uri( 'assets/css/' . $main_filename . '.css' ), [], FOXIZ_THEME_VERSION, 'all' );
		wp_add_inline_style( 'foxiz-main', foxiz_get_dynamic_css() );
		$style_deps[] = 'foxiz-main';

		if ( foxiz_get_option( 'podcast_supported' ) ) {
			wp_register_style( 'foxiz-podcast', foxiz_get_file_uri( 'assets/css/' . $podcast_filename . '.css' ), [], FOXIZ_THEME_VERSION, 'all' );
			$style_deps[] = 'foxiz-podcast';
		}

		if ( ! foxiz_is_amp() ) {
			wp_register_style( 'foxiz-print', foxiz_get_file_uri( 'assets/css/print.css' ), [], FOXIZ_THEME_VERSION, 'all' );
			$style_deps[] = 'foxiz-print';

			if ( class_exists( 'WooCommerce' ) ) {
				wp_deregister_style( 'yith-wcwl-font-awesome' );
				wp_register_style( 'foxiz-woocommerce', foxiz_get_file_uri( 'assets/css/' . $woocommerce_filename . '.css' ), [], FOXIZ_THEME_VERSION, 'all' );
				$style_deps[] = 'foxiz-woocommerce';
			}
		}

		wp_register_style( 'innovopedia-style', get_stylesheet_uri(), $style_deps, INNOVOPEDIA_THEME_VERSION, 'all' );
		wp_enqueue_style( 'innovopedia-style' );

		// Enqueue the AI-Briefing assets
		wp_enqueue_style( 'innovopedia-briefing', INNOVOPEDIA_THEME_URI . 'assets/css/briefing-sidebar.css', [], INNOVOPEDIA_THEME_VERSION );
		wp_enqueue_script( 'innovopedia-briefing', INNOVOPEDIA_THEME_URI . 'assets/js/briefing-sidebar.js', [ 'jquery' ], INNOVOPEDIA_THEME_VERSION, true );

		// Enqueue Custom Premium Header
		wp_enqueue_style( 'innovopedia-header', INNOVOPEDIA_THEME_URI . 'assets/css/custom-header.css', [], INNOVOPEDIA_THEME_VERSION );
		wp_enqueue_script( 'innovopedia-header', INNOVOPEDIA_THEME_URI . 'assets/js/custom-header.js', [ 'jquery' ], INNOVOPEDIA_THEME_VERSION, true );

		// Localize Script for AJAX and Nonce
		wp_localize_script( 'innovopedia-briefing', 'innovopediaBriefing', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'briefing_nonce' )
		]);

		if ( ! foxiz_is_amp() ) {

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_register_script( 'jquery-waypoints', foxiz_get_file_uri( 'assets/js/jquery.waypoints.min.js' ), [ 'jquery' ], '3.1.1', true );
			wp_register_script( 'rbswiper', foxiz_get_file_uri( 'assets/js/rbswiper.min.js' ), [], '6.8.4', true );
			wp_register_script( 'jquery-magnific-popup', foxiz_get_file_uri( 'assets/js/jquery.mp.min.js' ), [ 'jquery' ], '1.1.0', true );

			if ( foxiz_get_option( 'site_tooltips' ) && ! foxiz_is_wc_pages() ) {
				wp_register_script( 'rb-tipsy', foxiz_get_file_uri( 'assets/js/jquery.tipsy.min.js' ), [ 'jquery' ], '1.0', true );
				$script_deps[] = 'rb-tipsy';
			}

			if ( foxiz_get_option( 'single_post_highlight_shares' ) ) {
				wp_register_script( 'highlight-share', foxiz_get_file_uri( 'assets/js/highlight-share.js' ), '1.1.0', true );
				$script_deps[] = 'highlight-share';
			}

			if ( foxiz_get_option( 'back_top' ) ) {
				wp_register_script( 'jquery-uitotop', foxiz_get_file_uri( 'assets/js/jquery.ui.totop.min.js' ), [ 'jquery' ], 'v1.2', true );
				$script_deps[] = 'jquery-uitotop';
			}

			if ( class_exists( 'INNOVOPEDIA_CORE' ) ) {
				if ( foxiz_get_option( 'bookmark_system' ) ) {
					wp_register_script(
						'innovopedia-personalize',
						foxiz_get_file_uri( 'assets/js/personalized.js' ),
						[
							'jquery',
							'innovopedia-core',
						],
						INNOVOPEDIA_THEME_VERSION,
						true
					);
					$script_deps[] = 'innovopedia-personalize';
				}
				$script_deps[] = 'innovopedia-core';
			}
			wp_register_script( 'foxiz-global', foxiz_get_file_uri( 'assets/js/global.js' ), $script_deps, FOXIZ_THEME_VERSION, true );
			wp_localize_script( 'foxiz-global', 'foxizParams', foxiz_get_js_settings() );
			wp_enqueue_script( 'foxiz-global' );
		}
	}
}
