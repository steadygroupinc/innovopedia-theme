<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

require_once foxiz_get_file_path( 'includes/fallback-functions.php' );
require_once foxiz_get_file_path( 'includes/fonts.php' );

/** ADMIN */
if ( is_admin() ) {
	include_once foxiz_get_file_path( 'backend/setting-helpers.php' );
	include_once foxiz_get_file_path( 'backend/hooks.php' );
	include_once foxiz_get_file_path( 'backend/activation/class-activation.php' );
	include_once foxiz_get_file_path( 'backend/theme-options.php' );
	include_once foxiz_get_file_path( 'backend/single-settings.php' );
	include_once foxiz_get_file_path( 'backend/tax-settings.php' );
	include_once foxiz_get_file_path( 'backend/mega-menu.php' );
	include_once foxiz_get_file_path( 'backend/dynamic-css.php' );
}

/** HELPER FUNCTIONS */
require_once foxiz_get_file_path( 'includes/helpers.php' );

/** PERSONALIZE */
require_once foxiz_get_file_path( 'personalize/personalize.php' );
require_once foxiz_get_file_path( 'personalize/parts.php' );
require_once foxiz_get_file_path( 'personalize/reading-list.php' );

/** THEME FUNCTIONS */
require_once foxiz_get_file_path( 'includes/sidebars.php' );
require_once foxiz_get_file_path( 'includes/menu.php' );
require_once foxiz_get_file_path( 'includes/actions.php' );
require_once foxiz_get_file_path( 'includes/query.php' );

/** PODCAST */
if ( foxiz_get_option( 'podcast_supported' ) ) {
	include_once foxiz_get_file_path( 'podcast/init.php' );
}

/** AJAX */
require_once foxiz_get_file_path( 'templates/ajax.php' );

/** TEMPLATES PARTS */
require_once foxiz_get_file_path( 'templates/parts.php' );
require_once foxiz_get_file_path( 'templates/header-parts.php' );
require_once foxiz_get_file_path( 'templates/footer-parts.php' );

/** TEMPLATES */
require_once foxiz_get_file_path( 'templates/entry.php' );
require_once foxiz_get_file_path( 'templates/popup.php' );
require_once foxiz_get_file_path( 'templates/blog.php' );
require_once foxiz_get_file_path( 'templates/page.php' );

/** HEADER */
require_once foxiz_get_file_path( 'templates/header/layouts.php' );
require_once foxiz_get_file_path( 'templates/header/transparent.php' );

/** SINGLE */
require_once foxiz_get_file_path( 'templates/single/templates.php' );
require_once foxiz_get_file_path( 'templates/single/reviews.php' );
require_once foxiz_get_file_path( 'templates/single/layouts.php' );
require_once foxiz_get_file_path( 'templates/single/footer.php' );
require_once foxiz_get_file_path( 'templates/single/related.php' );
require_once foxiz_get_file_path( 'templates/single/attachment.php' );
require_once foxiz_get_file_path( 'templates/single/standard-1.php' );
require_once foxiz_get_file_path( 'templates/single/standard-1a.php' );
require_once foxiz_get_file_path( 'templates/single/standard-2.php' );
require_once foxiz_get_file_path( 'templates/single/standard-3.php' );
require_once foxiz_get_file_path( 'templates/single/standard-4.php' );
require_once foxiz_get_file_path( 'templates/single/standard-5.php' );
require_once foxiz_get_file_path( 'templates/single/standard-6.php' );
require_once foxiz_get_file_path( 'templates/single/standard-7.php' );
require_once foxiz_get_file_path( 'templates/single/standard-8.php' );
require_once foxiz_get_file_path( 'templates/single/standard-9.php' );
require_once foxiz_get_file_path( 'templates/single/standard-10.php' );
require_once foxiz_get_file_path( 'templates/single/standard-11.php' );
require_once foxiz_get_file_path( 'templates/single/video-1.php' );
require_once foxiz_get_file_path( 'templates/single/video-1a.php' );
require_once foxiz_get_file_path( 'templates/single/video-2.php' );
require_once foxiz_get_file_path( 'templates/single/video-3.php' );
require_once foxiz_get_file_path( 'templates/single/video-4.php' );
require_once foxiz_get_file_path( 'templates/single/audio-1.php' );
require_once foxiz_get_file_path( 'templates/single/audio-1a.php' );
require_once foxiz_get_file_path( 'templates/single/audio-2.php' );
require_once foxiz_get_file_path( 'templates/single/audio-3.php' );
require_once foxiz_get_file_path( 'templates/single/audio-4.php' );
require_once foxiz_get_file_path( 'templates/single/gallery-1.php' );
require_once foxiz_get_file_path( 'templates/single/gallery-2.php' );
require_once foxiz_get_file_path( 'templates/single/gallery-3.php' );

/** MODULES */
require_once foxiz_get_file_path( 'templates/modules/author.php' );
require_once foxiz_get_file_path( 'templates/modules/category.php' );
require_once foxiz_get_file_path( 'templates/modules/classic.php' );
require_once foxiz_get_file_path( 'templates/modules/grid.php' );
require_once foxiz_get_file_path( 'templates/modules/list.php' );
require_once foxiz_get_file_path( 'templates/modules/overlay.php' );

/** BLOCKS */
require_once foxiz_get_file_path( 'templates/blocks/authors.php' );
require_once foxiz_get_file_path( 'templates/blocks/breaking-news.php' );
require_once foxiz_get_file_path( 'templates/blocks/categories.php' );
require_once foxiz_get_file_path( 'templates/blocks/classic-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/gallery.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-box-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-box-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-flex-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-flex-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-personalize-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-personalize-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/grid-small-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/heading.php' );
require_once foxiz_get_file_path( 'templates/blocks/hierarchical-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/hierarchical-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/hierarchical-3.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-box-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-box-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-flex.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-personalize.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-small-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-small-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/list-small-3.php' );
require_once foxiz_get_file_path( 'templates/blocks/newsletter.php' );
require_once foxiz_get_file_path( 'templates/blocks/overlay-1.php' );
require_once foxiz_get_file_path( 'templates/blocks/overlay-2.php' );
require_once foxiz_get_file_path( 'templates/blocks/overlay-flex.php' );
require_once foxiz_get_file_path( 'templates/blocks/overlay-personalize.php' );
require_once foxiz_get_file_path( 'templates/blocks/playlist.php' );
require_once foxiz_get_file_path( 'templates/blocks/quick-links.php' );
require_once foxiz_get_file_path( 'templates/blocks/tax-accordion.php' );

/** Woocommerce */
if ( class_exists( 'WooCommerce' ) ) {
	include_once foxiz_get_file_path( 'woocommerce/hooks.php' );
	include_once foxiz_get_file_path( 'templates/blocks/product-grid.php' );
}
