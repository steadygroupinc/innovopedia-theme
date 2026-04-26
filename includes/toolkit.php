<?php
/**
 * Innovopedia Founder's Toolkit Module
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Toolkit Custom Post Type
 */
function innovopedia_register_toolkit_cpt() {
	$labels = [
		'name'               => _x( 'Toolkits', 'post type general name', 'foxiz' ),
		'singular_name'      => _x( 'Tool', 'post type singular name', 'foxiz' ),
		'menu_name'          => _x( 'Founder Toolkits', 'admin menu', 'foxiz' ),
		'name_admin_bar'     => _x( 'Tool', 'add new on admin bar', 'foxiz' ),
		'add_new'            => _x( 'Add New Tool', 'tool', 'foxiz' ),
		'add_new_item'       => __( 'Add New Tool', 'foxiz' ),
		'new_item'           => __( 'New Tool', 'foxiz' ),
		'edit_item'          => __( 'Edit Tool', 'foxiz' ),
		'view_item'          => __( 'View Tool', 'foxiz' ),
		'all_items'          => __( 'All Tools', 'foxiz' ),
		'search_items'       => __( 'Search Tools', 'foxiz' ),
		'not_found'          => __( 'No tools found.', 'foxiz' ),
	];

	$args = [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'toolkit' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-hammer',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'taxonomies'         => [ 'toolkit_cat' ],
		'show_in_rest'       => true,
	];

	register_post_type( 'toolkit', $args );

	// Register Taxonomy
	register_taxonomy( 'toolkit_cat', 'toolkit', [
		'label'        => __( 'Tool Category', 'foxiz' ),
		'rewrite'      => [ 'slug' => 'tool-category' ],
		'hierarchical' => true,
		'show_in_rest' => true,
	]);
}
add_action( 'init', 'innovopedia_register_toolkit_cpt' );

/**
 * Toolkit Grid Shortcode
 * [innovopedia_toolkits]
 */
function innovopedia_toolkits_shortcode( $atts ) {
	$atts = shortcode_atts( [
		'limit' => 6,
		'cat' => ''
	], $atts );

	$args = [
		'post_type'      => 'toolkit',
		'posts_per_page' => $atts['limit'],
	];

	if ( ! empty( $atts['cat'] ) ) {
		$args['tax_query'] = [[
			'taxonomy' => 'toolkit_cat',
			'field'    => 'slug',
			'terms'    => $atts['cat'],
		]];
	}

	$query = new WP_Query( $args );
	
	ob_start();
	if ( $query->have_posts() ) : ?>
		<div class="innovopedia-toolkit-grid">
			<?php while ( $query->have_posts() ) : $query->the_post(); 
				$category = get_the_terms( get_the_ID(), 'toolkit_cat' );
				$cat_name = $category ? $category[0]->name : 'Uncategorized';
				$tool_url = get_post_meta( get_the_ID(), '_tool_url', true );
			?>
				<div class="tool-card">
					<div class="tool-image">
						<?php the_post_thumbnail( 'medium' ); ?>
						<span class="tool-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
					</div>
					<div class="tool-info">
						<h3 class="tool-title"><?php the_title(); ?></h3>
						<p class="tool-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
						<div class="tool-actions">
							<a href="<?php the_permalink(); ?>" class="btn-secondary"><?php esc_html_e( 'Details', 'foxiz' ); ?></a>
							<a href="<?php echo esc_url( $tool_url ); ?>" class="btn-primary" target="_blank"><?php esc_html_e( 'Try Tool', 'foxiz' ); ?> <i class="rbi rbi-external"></i></a>
						</div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>

	<style>
	.innovopedia-toolkit-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
		gap: 30px;
		margin: 40px 0;
	}
	.tool-card {
		background: var(--solid-white);
		border-radius: var(--round-7);
		overflow: hidden;
		border: 1px solid var(--flex-gray-15);
		transition: var(--effect);
		display: flex;
		flex-direction: column;
	}
	.tool-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 30px var(--shadow-7);
		border-color: var(--g-color);
	}
	.tool-image {
		height: 200px;
		position: relative;
		overflow: hidden;
		background: var(--flex-gray-7);
	}
	.tool-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: var(--effect);
	}
	.tool-card:hover .tool-image img {
		transform: scale(1.05);
	}
	.tool-cat-badge {
		position: absolute;
		top: 15px;
		left: 15px;
		background: var(--g-color);
		color: #fff;
		padding: 4px 12px;
		border-radius: var(--round-3);
		font-size: 10px;
		font-weight: 800;
		text-transform: uppercase;
		z-index: 2;
	}
	.tool-info {
		padding: 25px;
		display: flex;
		flex-direction: column;
		flex: 1;
	}
	.tool-title {
		font-family: var(--h2-family);
		font-size: 20px;
		font-weight: 700;
		margin-bottom: 12px;
		color: var(--body-fcolor);
		line-height: 1.3;
	}
	.tool-excerpt {
		font-size: 14px;
		color: var(--meta-fcolor);
		line-height: 1.6;
		margin-bottom: 25px;
		flex: 1;
	}
	.tool-actions {
		display: flex;
		gap: 12px;
	}
	.tool-actions a {
		flex: 1;
		padding: 12px;
		border-radius: var(--round-5);
		font-size: 12px;
		font-weight: 700;
		text-align: center;
		text-decoration: none;
		text-transform: uppercase;
		transition: var(--effect);
	}
	.btn-primary {
		background: var(--g-color);
		color: #fff;
	}
	.btn-secondary {
		background: var(--flex-gray-7);
		color: var(--body-fcolor);
	}
	.btn-primary:hover {
		background: var(--body-fcolor);
		color: #fff;
	}
	</style>
	<?php
	return ob_get_clean();
}
add_shortcode( 'innovopedia_toolkits', 'innovopedia_toolkits_shortcode' );

/**
 * Add Meta Box for Tool URL
 */
function innovopedia_toolkit_metabox() {
	add_meta_box( 'toolkit_url', 'Tool Configuration', 'innovopedia_toolkit_url_callback', 'toolkit', 'side' );
}
add_action( 'add_meta_boxes', 'innovopedia_toolkit_metabox' );

function innovopedia_toolkit_url_callback( $post ) {
	$url = get_post_meta( $post->ID, '_tool_url', true );
	echo '<label for="tool_url">Direct Tool URL:</label>';
	echo '<input type="url" id="tool_url" name="tool_url" value="' . esc_attr( $url ) . '" style="width:100%;" />';
}

function innovopedia_save_toolkit_meta( $post_id ) {
	if ( isset( $_POST['tool_url'] ) ) {
		update_post_meta( $post_id, '_tool_url', esc_url_raw( $_POST['tool_url'] ) );
	}
}
add_action( 'save_post', 'innovopedia_save_toolkit_meta' );
