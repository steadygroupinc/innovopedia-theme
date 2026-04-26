<?php

/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Admin_Hooks' ) ) {
	class Foxiz_Admin_Hooks {
		protected static $instance = null;

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			self::$instance = $this;

			add_action( 'after_switch_theme', [ $this, 'set_defaults' ], 9 );
			add_action( 'switch_theme', [ $this, 'set_defaults' ], 9 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
			add_action( 'enqueue_block_assets', [ $this, 'enqueue_editor' ], 90 );

			/** add settings to theme options panel */
			add_filter( 'ruby_post_types_config', [ $this, 'ctp_supported' ], 0 );
			add_filter( 'ruby_taxonomies_config', [ $this, 'ctax_supported' ], 0 );

			add_action( 'save_post', [ $this, 'update_metaboxes' ], 10, 1 );
			add_action( 'save_post', [ $this, 'content_word_count' ], 100, 1 );
			add_action( 'update_option_' . FOXIZ_TOS_ID, [ $this, 'sync_comment_settings' ], 10, 2 );

			/** clear notification cache when posts change */
			add_action( 'save_post', [ $this, 'clear_notification_cache' ] );
		}

		public function set_defaults() {
			/** disable default elementor schemes */
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );

			$this->flush_rewrite_rules_on_activation();

			$current = get_option( FOXIZ_TOS_ID, [] );
			if ( is_array( $current ) && count( $current ) ) {
				return;
			}

			include foxiz_get_file_path( 'backend/panels/default-options.php' );
			set_transient( '_ruby_old_settings', $current, 30 * 86400 );
			update_option( FOXIZ_TOS_ID, foxiz_theme_options_default_values() );
		}

		/**
		 * Register endpoints and flush rewrite rules on theme activation.
		 */
		public function flush_rewrite_rules_on_activation() {
			if ( ! class_exists( 'Foxiz_Ajax' ) ) {
				include_once foxiz_get_file_path( 'templates/ajax.php' );
			}

			Foxiz_Ajax::get_instance()->register_endpoint();

			flush_rewrite_rules();
		}

		public function enqueue( $hook ) {
			wp_enqueue_style( 'foxiz-admin-style', foxiz_get_file_uri( 'backend/assets/admin.css' ), [], FOXIZ_THEME_VERSION );

			$allowed_hooks = [ 'foxiz_page_ruby-options', 'post.php', 'post-new.php', 'widgets.php', 'nav-menus.php', 'term.php', 'profile.php', 'user-edit.php' ];

			if ( in_array( $hook, [ 'profile.php', 'user-edit.php' ], true ) ) {
				wp_enqueue_media();
			}

			if ( 'nav-menus.php' === $hook ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'foxiz-admin', foxiz_get_file_uri( 'backend/assets/admin.js' ), [ 'jquery', 'wp-color-picker' ], FOXIZ_THEME_VERSION, true );
			} elseif ( in_array( $hook, $allowed_hooks, true ) ) {
				wp_enqueue_script( 'foxiz-admin', foxiz_get_file_uri( 'backend/assets/admin.js' ), [ 'jquery' ], FOXIZ_THEME_VERSION, true );
			}
		}

		/**
		 * Enqueues the necessary scripts and styles for the WordPress editor.
		 *
		 * @return void
		 */
		public function enqueue_editor() {

			if ( ! is_admin() ) {
				return;
			}

			$deps = [];

			$uri       = ! is_rtl() ? 'backend/assets/editor.css' : 'backend/assets/editor-rtl.css';
			$gfont_url = Foxiz_Font::get_font_url();
			if ( ! empty( $gfont_url ) ) {
				wp_register_style( 'foxiz-gfonts-editor', esc_url_raw( $gfont_url ), $deps, FOXIZ_THEME_VERSION, 'all' );
				$deps[] = 'foxiz-gfonts-editor';
			}
			wp_register_style( 'foxiz-editor-style', foxiz_get_file_uri( $uri ), $deps, FOXIZ_THEME_VERSION, 'all' );
			wp_enqueue_style( 'foxiz-editor-style' );
		}

		/**
		 * supported custom post types.
		 *
		 * @return mixed|void Returns filtered custom post type data or void if no data is found.
		 */
		public function ctp_supported() {
			$post_types = apply_filters( 'cptui_get_post_type_data', get_option( 'cptui_post_types', [] ), get_current_blog_id() );

			if ( function_exists( 'acf_maybe_unserialize' ) ) {
				$acf_query = new WP_Query(
					[
						'posts_per_page'         => -1,
						'post_type'              => 'acf-post-type',
						'orderby'                => 'menu_order title',
						'order'                  => 'ASC',
						'suppress_filters'       => false,
						'cache_results'          => true,
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
						'post_status'            => [ 'publish', 'acf-disabled' ],
					]
				);

				if ( $acf_query->have_posts() ) {
					while ( $acf_query->have_posts() ) {
						$acf_query->the_post();
						global $post;
						$data = (array) acf_maybe_unserialize( $post->post_content );
						if ( empty( $data['post_type'] ) ) {
							continue;
						}
						$key                = $data['post_type'];
						$label              = ! empty( $data['labels']['singular_name'] ) ? $data['labels']['singular_name'] : $data['post_type'];
						$post_types[ $key ] = [ 'label' => $label ];
					}

					wp_reset_postdata();
				}
			}

			return $post_types;
		}

		/**
		 * Supported custom taxonomies.
		 *
		 * @return mixed|void Returns filtered custom taxonomy data or void if no data is found.
		 */
		public function ctax_supported() {
			$taxonomies = apply_filters( 'cptui_get_taxonomy_data', get_option( 'cptui_taxonomies', [] ), get_current_blog_id() );

			if ( function_exists( 'acf_maybe_unserialize' ) ) {
				$acf_query = new WP_Query(
					[
						'posts_per_page'         => -1,
						'post_type'              => 'acf-taxonomy',
						'orderby'                => 'menu_order title',
						'order'                  => 'ASC',
						'suppress_filters'       => false,
						'cache_results'          => true,
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
						'post_status'            => [ 'publish', 'acf-disabled' ],
					]
				);

				if ( $acf_query->have_posts() ) {
					while ( $acf_query->have_posts() ) {
						$acf_query->the_post();
						global $post;
						$data = (array) acf_maybe_unserialize( $post->post_content );

						if ( empty( $data['taxonomy'] ) ) {
							continue;
						}
						$key                = $data['taxonomy'];
						$label              = ! empty( $data['labels']['singular_name'] ) ? $data['labels']['singular_name'] : $data['taxonomy'];
						$taxonomies[ $key ] = [ 'label' => $label ];
					}

					wp_reset_postdata();
				}
			}

			return $taxonomies;
		}


		public function update_metaboxes( $post_id ) {

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
				return;
			}

			if ( foxiz_is_sponsored_post( $post_id ) ) {
				update_post_meta( $post_id, 'foxiz_sponsored', 1 );
			} else {
				delete_post_meta( $post_id, 'foxiz_sponsored' );
			}

			$review = foxiz_get_review_settings( $post_id );

			if ( ! empty( $review['average'] ) ) {
				if ( empty( $review['type'] ) || 'score' === $review['type'] ) {
					update_post_meta( $post_id, 'foxiz_review_average', floatval( $review['average'] ) );
				} else {
					update_post_meta( $post_id, 'foxiz_review_average', floatval( $review['average'] ) * 2 );
				}
			} else {
				delete_post_meta( $post_id, 'foxiz_review_average' );
			}

			delete_post_meta( $post_id, 'rb_content_images' );
		}

		/**
		 * @param string $post_id
		 */
		public function content_word_count( $post_id = '' ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
				return;
			}

			delete_post_meta( $post_id, 'foxiz_content_total_word' );
			foxiz_update_word_count( $post_id );
		}


		public function sync_comment_settings( $old_value, $new_value ) {
			if ( ! is_array( $new_value ) ) {
				return;
			}

			if ( empty( $new_value['single_post_comment_sync'] ) ) {
				return;
			}

			$disabled = ! empty( $new_value['single_post_comment'] );

			update_option( 'default_comment_status', $disabled ? 'closed' : 'open' );
			update_option( 'close_comments_for_old_posts', $disabled ? '1' : '' );
		}

		/**
		 * Clear notification cache when posts are published, deleted, or trashed.
		 *
		 * @return void
		 */
		public function clear_notification_cache() {

			delete_transient( 'foxiz_notification_cache' );
		}
	}
}

/** load */
Foxiz_Admin_Hooks::get_instance();
