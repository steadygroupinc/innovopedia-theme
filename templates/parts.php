<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_get_block_open_tag' ) ) {
	function foxiz_get_block_open_tag( $settings = [], $_query = null ) {

		$uuid    = '';
		$tag     = 'div';
		$classes = [ 'block-wrap' ];

		if ( ! empty( $settings['carousel'] ) && '1' === (string) $settings['carousel'] ) {
			unset( $settings['column_gap'], $settings['columns'], $settings['columns_tablet'], $settings['columns_mobile'] );
		}

		if ( ! empty( $settings['block_tag'] ) ) {
			$tag = $settings['block_tag'];
		}

		if ( ! empty( $settings['uuid'] ) ) {
			$uuid = $settings['uuid'];
		}

		if ( ! empty( $settings['classes'] ) ) {
			$classes[] = $settings['classes'];
		}

		if ( ! empty( $settings['horizontal_scroll'] ) ) {
			switch ( $settings['horizontal_scroll'] ) {
				case 'tablet':
					$classes[] = 'is-thoz-scroll';
					unset( $settings['columns_tablet'] );
					break;
				case 'mobile':
					$classes[] = 'is-mhoz-scroll';
					unset( $settings['columns_mobile'] );
					break;
				default:
					$classes[] = 'is-hoz-scroll';
					unset( $settings['columns_tablet'] );
					unset( $settings['columns_mobile'] );
			}
		}

		if ( ! empty( $settings['columns'] ) ) {
			$classes[] = 'rb-columns rb-col-' . $settings['columns'];
		}

		if ( ! empty( $settings['columns_tablet'] ) ) {
			$classes[] = 'rb-tcol-' . $settings['columns_tablet'];
		}

		if ( ! empty( $settings['columns_mobile'] ) ) {
			$classes[] = 'rb-mcol-' . $settings['columns_mobile'];
		}

		if ( ! empty( $settings['column_gap'] ) ) {
			$classes[] = 'is-gap-' . $settings['column_gap'];
		}

		if ( ! empty( $settings['color_scheme'] ) ) {
			$classes[] = 'light-scheme';
		}

		if ( ! empty( $settings['column_border'] ) ) {
			$classes[] = 'col-border is-border-' . $settings['column_border'];
		}
		if ( ! empty( $settings['feat_hover'] ) ) {
			$classes[] = 'hovering-' . $settings['feat_hover'];
		}
		if ( ! empty( $settings['bottom_border'] ) ) {
			$classes[] = 'bottom-border is-b-border-' . $settings['bottom_border'];
			if ( ! empty( $settings['last_bottom_border'] ) && '-1' === (string) $settings['last_bottom_border'] ) {
				$classes[] = 'no-last-bb';
			}
		}

		if ( ! empty( $settings['center_mode'] ) ) {
			$classes[] = 'p-center';
		}

		if ( ! empty( $settings['pagination_style'] ) ) {
			$classes[] = 'is-pagi-' . $settings['pagination_style'];
		}

		if ( ! empty( $settings['middle_mode'] ) ) {
			switch ( $settings['middle_mode'] ) {
				case '1':
					$classes[] = 'p-middle';
					break;
				case '2':
					$classes[] = 'p-vtop';
					break;
				case '-1':
					$classes[] = 'p-vbottom';
					break;
			}
		}

		if ( ! empty( $settings['entry_category'] ) ) {
			$parse = explode( ',', $settings['entry_category'] );
			if ( ! empty( $parse[0] ) ) {
				$classes[] = 'ecat-' . $parse[0];
			}
			if ( ! empty( $parse[1] ) ) {
				$classes[] = 'ecat-size-' . $parse[1];
			}
		}
		if ( ! empty( $settings['featured_position'] ) ) {
			$classes[] = 'is-feat-' . $settings['featured_position'];
		}

		$tablet_layout = ! empty( $settings['tablet_layout'] ) ? $settings['tablet_layout'] : false;
		$mobile_layout = ! empty( $settings[' mobile_layout'] ) ? $settings[' mobile_layout'] : false;

		if ( $mobile_layout ) {
			$classes[] = 'is-m-' . $mobile_layout;
		}

		if ( $tablet_layout ) {
			$classes[] = 'is-t-' . $tablet_layout;
		}

		if ( 'list' === $tablet_layout || 'list' === $mobile_layout ) {
			if ( ! empty( $settings['featured_list_position'] ) ) {
				$classes[] = 'res-feat-' . $settings['featured_list_position'];
			} elseif ( ! empty( $settings['featured_position'] ) ) {
				$classes[] = 'res-feat-' . $settings['featured_position'];
			}
		}
		if ( ! empty( $settings['counter'] ) ) {
			$classes[] = 'ict-' . $settings['counter'];
		}
		$settings['meta_divider'] = ! empty( $settings['meta_divider'] ) ? $settings['meta_divider'] : foxiz_get_option( 'meta_divider', 'default' );

		$classes[] = 'meta-s-' . $settings['meta_divider'];

		$allowed_tags = [ 'div', 'section', 'aside', 'article', 'main', 'header', 'footer', 'nav' ];
		$safe_tag     = in_array( $tag, $allowed_tags, true ) ? $tag : 'div';
		return '<' . $safe_tag . ' id="' . esc_attr( $uuid ) . '" class="' . esc_attr( join( ' ', $classes ) ) . '">';
	}
}

if ( ! function_exists( 'foxiz_get_block_close_tag' ) ) {
	function foxiz_get_block_close_tag( $settings = [] ) {

		$allowed_tags = [ 'div', 'section', 'aside', 'article', 'main', 'header', 'footer', 'nav' ];
		$tag          = 'div';

		if ( ! empty( $settings['block_tag'] ) && in_array( $settings['block_tag'], $allowed_tags, true ) ) {
			$tag = $settings['block_tag'];
		}

		return '</' . $tag . '>';
	}
}

if ( ! function_exists( 'foxiz_block_open_tag' ) ) {
	function foxiz_block_open_tag( $settings = [], $_query = null ) {
		echo foxiz_get_block_open_tag( $settings, $_query );
	}
}

if ( ! function_exists( 'foxiz_block_close_tag' ) ) {
	function foxiz_block_close_tag( $settings = [] ) {
		echo foxiz_get_block_close_tag( $settings );
	}
}

if ( ! function_exists( 'foxiz_error_posts' ) ) {
	function foxiz_error_posts( $_query = null, $min = '' ) {

		if ( current_user_can( 'edit_pages' ) ) :
			if ( empty( $_query ) || ! $_query->have_posts() || ! $_query->post_count ) {
				$messenger = esc_html__( 'No found posts, Please add a new post for this query or change the block settings: ', 'foxiz' );
			} else {
				/* translators: %s: minimum number of posts required */
				$messenger = sprintf( esc_html__( 'This block requests at least %s posts, Please add new posts for this query or change the block settings: ', 'foxiz' ), $min );
			} ?>
			<p class="rb-error">
				<?php
				foxiz_render_inline_html( $messenger );
				echo '<a class="page-edit-link" href="' . str_replace( 'action=edit', 'action=elementor', get_edit_post_link() ) . '">' . esc_html__( 'Edit Page', 'foxiz' ) . '</a>';
				?>
			</p>
			<?php
		endif;
	}
}

if ( ! function_exists( 'foxiz_block_inner_open_tag' ) ) {
	function foxiz_block_inner_open_tag( $settings = [] ) {

		$classes = 'block-inner';
		if ( ! empty( $settings['inner_classes'] ) ) {
			$classes .= ' ' . $settings['inner_classes'];
		}
		if ( ! empty( $settings['scroll_height'] ) ) {
			echo '<div class="scroll-holder">';
		}
		echo '<div class="' . esc_attr( $classes ) . '">';
	}
}

if ( ! function_exists( 'foxiz_block_inner_close_tag' ) ) {
	function foxiz_block_inner_close_tag( $settings = [] ) {

		echo '</div>';

		if ( ! empty( $settings['scroll_height'] ) ) {
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'foxiz_is_ajax_pagination' ) ) {
	function foxiz_is_ajax_pagination( $pagination ) {

		if ( ! empty( $pagination ) && ( 'load_more' === $pagination || 'infinite_scroll' === $pagination || 'next_prev' === $pagination ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_dynamic_preview_pagination' ) ) {
	function foxiz_dynamic_preview_pagination( $pagination = '' ) {

		/* translators: %s: pagination type */
		echo '<div class="rb-admin-info">' . sprintf( esc_html__( 'Dynamic %s pagination', 'foxiz' ), $pagination ) . '</div>';
	}
}

if ( ! function_exists( 'foxiz_render_pagination' ) ) {
	function foxiz_render_pagination( $settings, $_query = null ) {

		if ( ! $_query ) {
			return;
		}

		/** clear up */
		$settings = foxiz_clear_settings_assets( $settings );

		/** ajax pagination for template builder */
		if ( ! empty( $settings['query_mode'] ) && 'global' === $settings['query_mode'] ) {
			if ( empty( $settings['builder_pagination'] ) ) {
				return;
			}

			/** edit mode */
			if ( foxiz_is_template_preview() ) {
				foxiz_dynamic_preview_pagination( $settings['builder_pagination'] );

				return;
			}

			/** remove affected query settings */
			$settings['pagination'] = $settings['builder_pagination'];
			unset( $settings['builder_pagination'] );
			unset( $settings['query_mode'] );

			$settings                   = foxiz_clear_settings_query( $settings );
			$settings['posts_per_page'] = $_query->get( 'posts_per_page' );

			/** template for default WordPress templates */
			if ( is_category() ) {
				$settings['category'] = $_query->get_queried_object_id();
			} elseif ( is_tag() ) {
				$settings['tags'] = $_query->get_queried_object()->slug;
			} elseif ( is_author() ) {
				$settings['author'] = $_query->get_queried_object_id();
			} elseif ( is_search() ) {
				$settings['s'] = get_search_query();
			} elseif ( is_tax( 'series' ) ) {
				$settings['post_type'] = 'podcast';
				$settings['category']  = $_query->get_queried_object_id();
			} elseif ( is_archive() ) {
				/** disable ajax */
				if ( foxiz_is_ajax_pagination( $settings['pagination'] ) ) {
					$settings['pagination'] = 'number';
				}
			}

			/** AMP fallback */
			if ( foxiz_is_amp() && foxiz_is_ajax_pagination( $settings['pagination'] ) ) {
				$settings['pagination'] = 'number';
			}
		}

		if ( empty( $settings['pagination'] ) || empty( $settings['uuid'] ) || ( foxiz_is_amp() && foxiz_is_ajax_pagination( $settings['pagination'] ) ) ) {
			return;
		}

		/** set ajax params */
		if ( ! empty( $settings['unique'] ) ) {
			$queried_ids = $_query->get( 'foxiz_queried_ids' );
			if ( is_array( $queried_ids ) ) {
				$queried_ids = implode( ',', $queried_ids );
				if ( empty( $settings['post_not_in'] ) ) {
					$settings['post_not_in'] = $queried_ids;
				} else {
					$settings['post_not_in'] .= ',' . $queried_ids;
				}
			}
		}

		if ( ! empty( $settings['post_not_in'] ) ) {
			$settings['post_not_in'] = str_replace( ',,', ',', $settings['post_not_in'] );
		}

		if ( $_query->query_vars['paged'] > 1 ) {
			$settings['paged'] = $_query->query_vars['paged'];
		} elseif ( ! empty( get_query_var( 'paged' ) ) && get_query_var( 'paged' ) > 1 ) {
			$settings['paged'] = get_query_var( 'paged' );
		} else {
			$settings['paged'] = 1;
		}

		if ( ! empty( $_query->max_num_pages ) ) {
			$settings['page_max'] = $_query->max_num_pages;
		}
		if ( ! empty( $settings['offset'] ) && ! empty( $_query->found_posts ) && ! empty( $settings['posts_per_page'] ) ) {
			$settings['page_max'] = ceil( ( $_query->found_posts - $settings['offset'] ) / $settings['posts_per_page'] );
		}

		/** set params for custom template sections */
		if ( empty( $settings['content_source'] ) ) {
			$settings['content_source'] = $_query->get( 'content_source' );
		}
		if ( ! empty( $settings['content_source'] ) ) {
			switch ( $settings['content_source'] ) {
				case 'related':
					$settings['related_id']     = $_query->get( 'related_id' );
					$settings['posts_per_page'] = $_query->get( 'related_total' );
					if ( 'simple' === $settings['pagination'] || 'number' === $settings['pagination'] ) {
						$settings['pagination'] = 'load_more';
					}
					break;
				case 'recommended':
				case 'saved':
					if ( 'simple' === $settings['pagination'] || 'number' === $settings['pagination'] ) {
						$settings['pagination'] = 'infinite_scroll';
					}
					break;
			}
		}

		/** js settings */
		$js_settings = [];
		$localize    = 'foxiz-global';
		foreach ( $settings as $key => $val ) {
			if ( ! empty( $val ) ) {
				$js_settings[ $key ] = $val;
			}
		}
		if ( ! empty( $settings['localize'] ) ) {
			$localize = $settings['localize'];
		}
		wp_localize_script( $localize, $settings['uuid'], $js_settings );

		if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() && function_exists( 'foxiz_ajax_localize_script' ) ) {
			foxiz_ajax_localize_script( $settings['uuid'], $js_settings );
		}

		/** render */
		switch ( $settings['pagination'] ) {
			case 'next_prev':
				if ( empty( $settings['page_max'] ) || $settings['page_max'] > 1 ) {
					foxiz_render_pagination_nextprev( $_query );
				}
				break;
			case 'load_more':
				if ( empty( $settings['page_max'] ) || $settings['page_max'] > 1 ) {
					foxiz_render_pagination_load_more( $_query );
				}
				break;
			case 'infinite_scroll':
				if ( empty( $settings['page_max'] ) || $settings['page_max'] > 1 ) {
					foxiz_render_pagination_infinite( $_query );
				}
				break;
			case 'simple':
				foxiz_render_pagination_simple( $_query );
				break;
			case 'number':
				foxiz_render_pagination_number( $_query );
				break;
		}
	}
}

if ( ! function_exists( 'foxiz_render_pagination_load_more' ) ) {
	function foxiz_render_pagination_load_more( $_query = null ) {

		if ( empty( $_query ) || ! is_object( $_query ) ) {
			global $wp_query;
			$_query = $wp_query;
		}

		if ( $_query->max_num_pages < 2 ) {
			return;
		}
		?>
		<div class="pagination-wrap pagination-loadmore">
			<a href="#" rel="nofollow" role="button" class="loadmore-trigger" aria-label="<?php foxiz_html_e( 'Show More', 'foxiz' ); ?>"><span><?php foxiz_html_e( 'Show More', 'foxiz' ); ?></span><i class="rb-loader" aria-hidden="true"></i></a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_render_pagination_nextprev' ) ) {
	function foxiz_render_pagination_nextprev( $_query = null ) {

		if ( empty( $_query ) || ! is_object( $_query ) ) {
			global $wp_query;
			$_query = $wp_query;
		}
		if ( $_query->max_num_pages < 2 ) {
			return;
		}
		?>
		<div class="pagination-wrap pagination-nextprev">
			<a href="#" rel="nofollow" role="button" class="pagination-trigger ajax-prev is-disable" data-type="prev" aria-label="<?php foxiz_html_e( 'Previous', 'foxiz' ); ?>"><i class="rbi rbi-angle-left" aria-hidden="true"></i><span><?php foxiz_html_e( 'Previous', 'foxiz' ); ?></span></a>
			<a href="#" rel="nofollow" role="button" class="pagination-trigger ajax-next" data-type="next" aria-label="<?php foxiz_html_e( 'Next', 'foxiz' ); ?>"><span><?php foxiz_html_e( 'Next', 'foxiz' ); ?></span><i class="rbi rbi-angle-right" aria-hidden="true"></i></a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_render_pagination_infinite' ) ) {
	function foxiz_render_pagination_infinite( $_query = null ) {

		if ( empty( $_query ) || ! is_object( $_query ) ) {
			global $wp_query;
			$_query = $wp_query;
		}
		if ( $_query->max_num_pages < 2 ) {
			return;
		}
		?>
		<div class="pagination-wrap pagination-infinite">
			<div class="infinite-trigger"><i class="rb-loader" aria-hidden="true"></i></div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_render_pagination_simple' ) ) {
	function foxiz_render_pagination_simple( $_query = null ) {

		if ( empty( $_query ) || ! is_object( $_query ) ) {
			global $wp_query;
			$_query = $wp_query;
		}

		if ( $_query->max_num_pages < 2 ) {
			return;
		}
		?>
		<nav class="pagination-wrap pagination-simple clearfix">
			<?php if ( get_previous_posts_link() ) : ?>
				<span class="newer"><?php previous_posts_link( '<i class="rbi rbi-cleft" aria-hidden="true"></i>' . foxiz_html__( 'Newer Articles', 'foxiz' ) ); ?></span>
				<?php
			endif;
			if ( get_next_posts_link() ) :
				?>
				<span class="older"><?php next_posts_link( foxiz_html__( 'Older Articles', 'foxiz' ) . '<i class="rbi rbi-cright" aria-hidden="true"></i>' ); ?></span>
			<?php endif; ?>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'foxiz_render_pagination_number' ) ) {
	function foxiz_render_pagination_number( $_query = null, $offset = 0 ) {

		if ( empty( $_query ) || ! is_object( $_query ) ) {
			global $wp_query;
			$_query = $wp_query;
		}

		if ( $_query->max_num_pages < 2 ) {
			return;
		}

		$current = 1;
		$total   = $_query->max_num_pages;
		if ( $_query->query_vars['paged'] > 1 ) {
			$current = $_query->query_vars['paged'];
		} elseif ( ! empty( get_query_var( 'paged' ) ) && get_query_var( 'paged' ) > 1 ) {
			$current = get_query_var( 'paged' );
		}

		if ( ! empty( $offset ) ) {
			$post_per_page = $_query->query_vars['posts_per_page'];
			$total         = $_query->max_num_pages - floor( $offset / $post_per_page );
			$found_posts   = $_query->found_posts;
			if ( $found_posts < ( $total * $post_per_page ) ) {
				$total = $total - 1;
			}
		}

		$params = [
			'total'     => $total,
			'current'   => $current,
			'end_size'  => 2,
			'mid_size'  => 2,
			'prev_text' => '<i class="rbi-cleft" aria-hidden="true"></i>',
			'next_text' => '<i class="rbi-cright" aria-hidden="true"></i>',
			'type'      => 'plain',
		];

		if ( ! empty( $_query->query_vars['s'] ) ) {
			$params['add_args'] = [ 's' => rawurlencode( get_query_var( 's' ) ) ];
		}
		?>
		<nav class="pagination-wrap pagination-number">
			<?php echo paginate_links( $params ); ?>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'foxiz_search_form' ) ) {
	function foxiz_search_form( $settings = [] ) {

		echo foxiz_render_search_form( $settings );
	}
}

if ( ! function_exists( 'foxiz_render_search_form' ) ) {
	function foxiz_render_search_form( $settings = [] ) {

		$output = '';

		if ( empty( $settings['placeholder'] ) ) {
			$settings['placeholder'] = foxiz_get_option( 'search_placeholder' );
			if ( empty( $settings['placeholder'] ) ) {
				$settings['placeholder'] = foxiz_html__( 'Search Headlines, News...', 'foxiz' );
			}
		}
		if ( empty( $settings['label'] ) ) {
			$settings['label'] = foxiz_html__( 'Search', 'foxiz' );
		}
		if ( empty( $settings['icon']['url'] ) ) {
			$settings['icon'] = foxiz_get_option( 'header_search_custom_icon' );
		}
		$classes = 'rb-search-form';
		if ( ! empty( $settings['ajax_search'] ) ) {
			$classes .= ' live-search-form';
		}
		if ( ! empty( $settings['no_submit'] ) ) {
			$output .= '<div class="' . esc_attr( $classes ) . '" ' . foxiz_search_attributes( $settings ) . '>';
		} else {
			$output .= '<form method="get" action="' . esc_url( home_url( '/' ) ) . '" class="' . esc_attr( $classes ) . '" ' . foxiz_search_attributes( $settings ) . '>';
		}
		$output .= '<div class="search-form-inner">';
		if ( ! empty( $settings['icon']['url'] ) ) {
			$output .= '<span class="search-icon"><span class="search-icon-svg"></span></span>';
		} else {
			$output .= '<span class="search-icon"><i class="rbi rbi-search" aria-hidden="true"></i></span>';
		}
		$output .= '<span class="search-text"><input type="text" class="field" placeholder="' . esc_attr( $settings['placeholder'] ) . '" value="' . esc_attr( get_search_query() ) . '" name="s"/></span>';
		if ( ! empty( $settings['post_type'] ) ) {
			$output .= '<input type="hidden" class="is-hidden" value="' . esc_attr( $settings['post_type'] ) . '" name="post_type"/>';
		}
		if ( empty( $settings['no_submit'] ) ) {
			$output .= '<span class="rb-search-submit"><input type="submit" value="' . esc_attr( $settings['label'] ) . '"/><i class="rbi rbi-cright" aria-hidden="true"></i></span>';
		}
		if ( ! empty( $settings['ajax_search'] ) ) {
			$output .= '<span class="live-search-animation rb-loader"></span>';
		}
		$output .= '</div>';

		if ( ! empty( $settings['ajax_search'] ) ) {
			$output .= '<div class="live-search-response' . ( ! empty( $settings['color_scheme'] ) ? ' light-scheme' : '' ) . '"></div>';
		}
		if ( ! empty( $settings['no_submit'] ) ) {
			$output .= '</div>';
		} else {
			$output .= '</form>';
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_search_attributes' ) ) {
	function foxiz_search_attributes( $settings ) {

		$settings['limit']       = empty( $settings['limit'] ) ? '' : $settings['limit'];
		$settings['taxonomies']  = empty( $settings['taxonomies'] ) ? 'category' : $settings['taxonomies'];
		$settings['search_type'] = empty( $settings['search_type'] ) ? 'post' : $settings['search_type'];
		$settings['follow']      = empty( $settings['follow'] ) ? '0' : $settings['follow'];
		$settings['desc_source'] = empty( $settings['desc_source'] ) ? '0' : $settings['desc_source'];
		$settings['post_type']   = empty( $settings['post_type'] ) ? '' : $settings['post_type'];

		return ' data-search="' . esc_attr( $settings['search_type'] ) . '" data-limit="' . absint( $settings['limit'] ) . '" data-follow="' . esc_attr( $settings['follow'] ) . '" data-tax="' . esc_attr( $settings['taxonomies'] ) . '" data-dsource="' . esc_attr( $settings['desc_source'] ) . '"  data-ptype="' . esc_attr( $settings['post_type'] ) . '"';
	}
}

if ( ! function_exists( 'foxiz_render_elementor_link' ) ) {
	function foxiz_render_elementor_link( $link, $content = '', $classes = '', $label = '' ) {

		$output  = '';
		$output .= '<a';
		if ( ! empty( $classes ) ) {
			$output .= ' class="' . esc_attr( $classes ) . '"';
		}
		if ( ! empty( $link['is_external'] ) ) {
			$output .= ' target="_blank"';
		}
		if ( ! empty( $link['nofollow'] ) ) {
			$output .= ' rel="nofollow"';
		}
		if ( ! empty( $link['custom_attributes'] ) ) {
			$attrs = explode( ',', $link['custom_attributes'] );
			foreach ( $attrs as $attr ) {
				$attr = explode( '|', $attr );
				if ( ! empty( $attr[0] && ! empty( $attr[1] ) ) ) {
					$output .= ' ' . esc_attr( $attr[0] ) . '="' . esc_attr( $attr[1] ) . '"';
				}
			}
		}
		if ( ! empty( $link['url'] ) ) {
			$output .= ' href="' . esc_url( $link['url'] ) . '"';
		}

		if ( ! empty( $label ) ) {
			$output .= ' aria-label="' . esc_attr( $label ) . '"';
		}

		$output .= '>' . foxiz_strip_tags( $content ) . '</a>';

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_social_list' ) ) {
	function foxiz_get_social_list( $data = [], $new_tab = true, $custom = true ) {

		if ( empty( $data ) ) {
			return false;
		}

		$target_attr = $new_tab ? 'target="_blank" rel="noopener nofollow"' : 'rel="noopener nofollow"';

		$defaults = [
			'website'        => '',
			'facebook'       => '',
			'twitter'        => '',
			'youtube'        => '',
			'googlenews'     => '',
			'instagram'      => '',
			'pinterest'      => '',
			'tiktok'         => '',
			'linkedin'       => '',
			'medium'         => '',
			'flipboard'      => '',
			'twitch'         => '',
			'steam'          => '',
			'tumblr'         => '',
			'discord'        => '',
			'flickr'         => '',
			'skype'          => '',
			'snapchat'       => '',
			'quora'          => '',
			'spotify'        => '',
			'apple_podcast'  => '',
			'google_podcast' => '',
			'stitcher'       => '',
			'myspace'        => '',
			'bloglovin'      => '',
			'digg'           => '',
			'dribbble'       => '',
			'soundcloud'     => '',
			'vimeo'          => '',
			'reddit'         => '',
			'vkontakte'      => '',
			'telegram'       => '',
			'whatsapp'       => '',
			'truth'          => '',
			'paypal'         => '',
			'patreon'        => '',
			'threads'        => '',
			'bluesky'        => '',
			'rss'            => '',
		];

		$socials = shortcode_atts( $defaults, $data );

		// Social media mapping: key => [ css_class, label, icon ]
		$social_map = [
			'website'        => [ 'website', 'Website', 'rbi-link' ],
			'facebook'       => [ 'facebook', 'Facebook', 'rbi-facebook' ],
			'twitter'        => [ 'twitter', 'X', 'rbi-twitter' ],
			'youtube'        => [ 'youtube', 'YouTube', 'rbi-youtube' ],
			'googlenews'     => [ 'google-news', 'Google News', 'rbi-gnews' ],
			'instagram'      => [ 'instagram', 'Instagram', 'rbi-instagram' ],
			'pinterest'      => [ 'pinterest', 'Pinterest', 'rbi-pinterest' ],
			'tiktok'         => [ 'tiktok', 'TikTok', 'rbi-tiktok' ],
			'linkedin'       => [ 'linkedin', 'LinkedIn', 'rbi-linkedin' ],
			'medium'         => [ 'medium', 'Medium', 'rbi-medium' ],
			'flipboard'      => [ 'flipboard', 'Flipboard', 'rbi-flipboard' ],
			'twitch'         => [ 'twitch', 'Twitch', 'rbi-twitch' ],
			'steam'          => [ 'steam', 'Steam', 'rbi-steam' ],
			'tumblr'         => [ 'tumblr', 'Tumblr', 'rbi-tumblr' ],
			'discord'        => [ 'discord', 'Discord', 'rbi-discord' ],
			'flickr'         => [ 'flickr', 'Flickr', 'rbi-flickr' ],
			'skype'          => [ 'skype', 'Skype', 'rbi-skype' ],
			'snapchat'       => [ 'snapchat', 'SnapChat', 'rbi-snapchat' ],
			'quora'          => [ 'quora', 'Quora', 'rbi-quora' ],
			'spotify'        => [ 'spotify', 'Spotify', 'rbi-spotify' ],
			'apple_podcast'  => [ 'apple-podcast', 'Apple Podcasts', 'rbi-applepodcast' ],
			'google_podcast' => [ 'google-podcast', 'Google Podcasts', 'rbi-googlepodcast' ],
			'stitcher'       => [ 'stitcher', 'Stitcher', 'rbi-stitcher' ],
			'myspace'        => [ 'myspace', 'Myspace', 'rbi-myspace' ],
			'bloglovin'      => [ 'bloglovin', 'Bloglovin', 'rbi-heart' ],
			'digg'           => [ 'digg', 'Digg', 'rbi-digg' ],
			'dribbble'       => [ 'dribbble', 'Dribbble', 'rbi-dribbble' ],
			'soundcloud'     => [ 'soundcloud', 'SoundCloud', 'rbi-soundcloud' ],
			'vimeo'          => [ 'vimeo', 'Vimeo', 'rbi-vimeo' ],
			'reddit'         => [ 'reddit', 'Reddit', 'rbi-reddit' ],
			'vkontakte'      => [ 'vk', 'Vkontakte', 'rbi-vk' ],
			'telegram'       => [ 'telegram', 'Telegram', 'rbi-telegram' ],
			'whatsapp'       => [ 'whatsapp', 'WhatsApp', 'rbi-whatsapp' ],
			'truth'          => [ 'truth', 'Truth Social', 'rbi-truth' ],
			'paypal'         => [ 'paypal', 'PayPal', 'rbi-paypal' ],
			'patreon'        => [ 'patreon', 'Patreon', 'rbi-patreon' ],
			'threads'        => [ 'threads', 'Threads', 'rbi-threads' ],
			'bluesky'        => [ 'bluesky', 'Bluesky', 'rbi-bluesky' ],
			'rss'            => [ 'rss', 'Rss', 'rbi-rss' ],
		];

		$output = '';

		foreach ( $social_map as $key => $config ) {
			if ( ! empty( $socials[ $key ] ) ) {
				$output .= sprintf(
					'<a class="social-link-%s" aria-label="%s" data-title="%s" href="%s" %s><i class="rbi %s" aria-hidden="true"></i></a>',
					esc_attr( $config[0] ),
					foxiz_attr__( $config[1], 'foxiz' ),
					foxiz_attr__( $config[1], 'foxiz' ),
					esc_url( $socials[ $key ] ),
					$target_attr,
					esc_attr( $config[2] )
				);
			}
		}

		if ( $custom ) {
			for ( $i = 1; $i <= 3; $i++ ) {
				$url  = foxiz_get_option( 'custom_social_' . $i . '_url' );
				$name = foxiz_get_option( 'custom_social_' . $i . '_name' );
				$icon = foxiz_get_option( 'custom_social_' . $i . '_icon' );

				if ( ! empty( $url ) && ! empty( $name ) ) {
					$output .= sprintf(
						'<a class="social-link-custom social-link-%d social-link-%s" data-title="%s" aria-label="%s" href="%s" %s><i class="%s" aria-hidden="true"></i></a>',
						$i,
						esc_attr( sanitize_title( $name ) ),
						esc_attr( $name ),
						esc_attr( $name ),
						esc_url( $url ),
						$target_attr,
						esc_attr( $icon )
					);
				}
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_category_hero' ) ) {
	function foxiz_get_category_hero( $featured_array = [], $featured_urls_array = [], $size = 'foxiz_crop_o1', $lazy = true ) {

		if ( ! is_array( $featured_array ) || ! count( $featured_array ) ) {
			return false;
		}

		if ( 1 === count( $featured_array ) ) {
			$featured_array[1] = $featured_array[0];
		}

		$counter = 0;
		$output  = '';

		foreach ( $featured_array as $index => $id ) {

			if ( foxiz_is_amp() ) {
				$loading = '';
			} elseif ( 0 === $counter && ! $lazy ) {
					$loading = 'loading="eager" decoding="async" ';
			} else {
				$loading = 'loading="lazy" decoding="async" ';
			}

			$url = wp_get_attachment_image_url( $id, $size );
			$alt = get_post_meta( $url, '_wp_attachment_image_alt', true );
			if ( empty( $url ) && ! empty( $featured_urls_array[ $index ] ) ) {
				$url = $featured_urls_array[ $index ];
			}
			$output .= '<div class="category-hero-item">';
			$output .= '<div class="category-hero-item-inner">';
			$output .= '<img ' . $loading . 'src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"/>';
			$output .= '</div>';
			$output .= '</div>';

			++$counter;
			if ( $counter > 1 ) {
				break;
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'foxiz_render_category_hero' ) ) {
	function foxiz_render_category_hero( $featured_array = [], $featured_urls_array = [], $size = '' ) {

		echo foxiz_get_category_hero( $featured_array, $featured_urls_array, $size, false );
	}
}

if ( ! function_exists( 'foxiz_get_category_featured' ) ) {
	function foxiz_get_category_featured( $featured_array = [], $featured_urls_array = [], $size = 'foxiz_crop_g1' ) {

		if ( ! empty( $featured_array[0] ) ) {
			return '<span class="category-feat">' . wp_get_attachment_image( $featured_array[0], $size, false, [ 'loading' => 'lazy' ] ) . '</span>';
		} elseif ( ! empty( $featured_urls_array[0] ) ) {

			$output = '<img ';
			if ( ! foxiz_is_amp() ) {
				$output .= 'loading="lazy" decoding="async" ';
			}
			$output .= 'src="' . esc_url( $featured_urls_array[0] ) . ' alt="' . esc_html__( 'category featured', 'foxiz' ) . '"/>';

			return '<span class="category-feat">' . $output . '</span>';
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_render_category_featured' ) ) {
	function foxiz_render_category_featured( $featured_array = [], $featured_urls_array = [], $size = '' ) {

		echo foxiz_get_category_featured( $featured_array, $featured_urls_array, $size );
	}
}

if ( ! function_exists( 'foxiz_post_open_tag' ) ) {
	function foxiz_post_open_tag( $settings = [] ) {

		?>
		<div class="<?php echo foxiz_get_post_classes( $settings ); ?>" data-pid="<?php echo get_the_ID(); ?>">
		<?php
	}
}

if ( ! function_exists( 'foxiz_post_close_tag' ) ) {
	function foxiz_post_close_tag() {

		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_single_category_title' ) ) {
	function foxiz_single_category_title( $settings = [] ) {

		if ( ! empty( $settings['follow_category_header'] ) && foxiz_get_option( 'bookmark_system' ) ) :
			?>
			<div class="archive-title b-follow">
				<h1><?php single_term_title(); ?></h1>
				<span class="rb-follow follow-trigger" data-name="<?php single_term_title(); ?>" data-cid="<?php echo get_queried_object_id(); ?>"></span>
			</div>
		<?php else : ?>
			<h1 class="archive-title"><?php single_term_title(); ?></h1>
			<?php
		endif;
	}
}

if ( ! function_exists( 'foxiz_navigation_fallback' ) ) {
	function foxiz_navigation_fallback( $settings = [] ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$menu_name = isset( $settings['fallback_name'] ) ? $settings['fallback_name'] : '';
		?>
		<div class="rb-error">
			<?php /* translators: %s: menu location name */ ?>
			<p><?php printf( esc_html__( 'Please assign a menu to the "%s" location under ', 'foxiz' ), esc_html( $menu_name ) ); ?>
				<a href="<?php echo esc_url( get_admin_url( get_current_blog_id(), 'nav-menus.php?action=locations' ) ); ?>"><?php esc_html_e( 'Manage Locations', 'foxiz' ); ?></a>
			</p>
		</div>
		<?php
	}
}
