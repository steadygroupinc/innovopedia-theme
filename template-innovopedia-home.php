<?php
/**
 * Template Name: Innovopedia Master Homepage
 * Description: A high-end, custom-engineered editorial homepage for Innovopedia.
 */

get_header(); ?>

<main id="main" class="innovopedia-master-home">
    
    <!-- SECTION 0: TRENDING TICKER -->
    <section class="home-trending-bar">
        <div class="rb-container">
            <div class="trending-flex">
                <span class="trending-label"><i class="rbi rbi-trending-up"></i> <?php esc_html_e('TRENDING NOW', 'innovopedia'); ?></span>
                <div class="trending-list">
                    <?php
                    $trending = new WP_Query([ 'posts_per_page' => 5, 'orderby' => 'comment_count' ]);
                    if ( $trending->have_posts() ) : while ( $trending->have_posts() ) : $trending->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="trending-item"><?php the_title(); ?></a>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: MASTER HERO -->
    <section class="home-hero rb-container">
        <?php
        $hero_query = new WP_Query([
            'posts_per_page' => 1,
            'meta_key'       => '_is_featured',
            'post_status'    => 'publish'
        ]);
        if ( ! $hero_query->have_posts() ) {
            $hero_query = new WP_Query([ 'posts_per_page' => 1 ]);
        }

        if ( $hero_query->have_posts() ) : $hero_query->the_post(); ?>
            <div class="hero-master-grid">
                <div class="hero-main-story">
                    <div class="hero-wrap">
                        <div class="hero-image">
                            <?php the_post_thumbnail('full'); ?>
                            <div class="hero-overlay"></div>
                        </div>
                        <div class="hero-content">
                            <span class="hero-badge"><?php esc_html_e('FEATURED STORY', 'innovopedia'); ?></span>
                            <h1 class="hero-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                            <p class="hero-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 22); ?></p>
                            <div class="hero-meta">
                                <span class="meta-author"><?php the_author(); ?></span>
                                <span class="meta-date"><?php echo get_the_date(); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hero-side-stories">
                    <?php
                    $side_query = new WP_Query([ 'posts_per_page' => 2, 'offset' => 1 ]);
                    if ( $side_query->have_posts() ) : while ( $side_query->have_posts() ) : $side_query->the_post(); ?>
                        <div class="side-story-item">
                            <div class="side-thumb"><?php the_post_thumbnail('foxiz_crop_g2'); ?></div>
                            <div class="side-info">
                                <span class="side-cat"><?php the_category(', '); ?></span>
                                <h3 class="side-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- SECTION 2: AI BRIEFING BAR -->
    <section class="home-briefing-bar">
        <div class="rb-container">
            <?php echo do_shortcode('[innovopedia_briefing]'); ?>
        </div>
    </section>

    <!-- SECTION 3: THE INTELLIGENCE GRID -->
    <section class="home-grid rb-container">
        <div class="grid-layout">
            <div class="grid-main">
                <h2 class="section-label"><span><?php esc_html_e('Latest Intelligence', 'innovopedia'); ?></span></h2>
                <?php
                $latest = new WP_Query([ 'posts_per_page' => 6, 'offset' => 1 ]);
                if ( $latest->have_posts() ) : ?>
                    <div class="latest-feed">
                        <?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
                            <article class="feed-item">
                                <div class="item-thumb"><?php the_post_thumbnail('thumbnail'); ?></div>
                                <div class="item-info">
                                    <h3 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="item-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php wp_reset_postdata(); endif; ?>
            </div>

            <aside class="grid-sidebar">
                <div class="sidebar-block">
                    <?php echo do_shortcode('[innovopedia_newsletter]'); ?>
                </div>
                <div class="sidebar-block">
                    <h3 class="sidebar-title"><?php esc_html_e('Founder Toolkit', 'innovopedia'); ?></h3>
                    <?php echo do_shortcode('[innovopedia_toolkits limit="3"]'); ?>
                </div>
            </aside>
        </div>
    </section>

    <!-- SECTION 4: DATA VISUALIZATION -->
    <section class="home-data rb-container">
        <h2 class="section-label"><span><?php esc_html_e('Market Pulse', 'innovopedia'); ?></span></h2>
        <div class="data-wrap">
            <?php echo do_shortcode('[innovopedia_chart type="line" labels="Mon,Tue,Wed,Thu,Fri" data="12,19,15,25,22" title="Global Tech Index"]'); ?>
        </div>
    </section>

</main>

<style>
:root {
    --innovopedia-blue: #5000ff;
    --innovopedia-blue-light: #7033ff;
}
.innovopedia-master-home {
    background: #fff;
    padding-bottom: 80px;
}
.rb-container {
    padding-left: 30px;
    padding-right: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

/* TRENDING BAR */
.home-trending-bar {
    background: #000;
    color: #fff;
    padding: 12px 0;
    font-size: 13px;
    margin-bottom: 40px;
}
.trending-flex {
    display: flex;
    align-items: center;
    gap: 30px;
}
.trending-label {
    background: var(--innovopedia-blue);
    color: #fff;
    font-weight: 900;
    padding: 4px 12px;
    border-radius: var(--round-3);
    font-size: 11px;
    letter-spacing: 1px;
    flex-shrink: 0;
}
.trending-list {
    display: flex;
    gap: 40px;
    overflow: hidden;
    white-space: nowrap;
}
.trending-item {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    opacity: 0.8;
    transition: 0.3s;
}
.trending-item:hover { opacity: 1; color: var(--innovopedia-blue); }

/* MASTER HERO GRID */
.hero-master-grid {
    display: grid;
    grid-template-columns: 2fr 1.2fr;
    gap: 30px;
    margin-bottom: 60px;
}
.hero-wrap {
    position: relative;
    border-radius: var(--round-7);
    overflow: hidden;
    height: 650px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.hero-image { width: 100%; height: 100%; }
.hero-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease; }
.hero-wrap:hover .hero-image img { transform: scale(1.04); }
.hero-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0; height: 85%;
    background: linear-gradient(to top, rgba(0,0,0,0.98) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
}
.hero-content {
    position: absolute;
    bottom: 50px; left: 50px; right: 50px;
    color: #fff; z-index: 2;
}
.hero-badge {
    background: var(--innovopedia-blue);
    padding: 6px 14px; font-size: 11px; font-weight: 800;
    border-radius: var(--round-3); margin-bottom: 25px; display: inline-block; color: #fff;
    text-transform: uppercase; letter-spacing: 1px;
}
.hero-title {
    font-family: var(--h1-family); font-size: 62px; font-weight: 900;
    line-height: 1.05; margin-bottom: 25px; letter-spacing: -1px;
}
.hero-title a { color: #fff; text-decoration: none; transition: 0.3s; }
.hero-title a:hover { color: var(--innovopedia-blue); }
.hero-excerpt { font-size: 19px; color: #ddd; max-width: 600px; margin-bottom: 30px; line-height: 1.6; }
.hero-meta { font-size: 13px; font-weight: 600; color: #bbb; display: flex; gap: 20px; }

.hero-side-stories {
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.side-story-item {
    background: #000;
    border-radius: var(--round-7);
    overflow: hidden;
    height: calc(50% - 15px);
    position: relative;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}
.side-thumb { width: 100%; height: 100%; }
.side-thumb img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.6); transition: 0.4s; }
.side-story-item:hover .side-thumb img { filter: brightness(0.8); transform: scale(1.05); }
.side-info {
    position: absolute;
    bottom: 30px; left: 30px; right: 30px;
    z-index: 2;
}
.side-cat a {
    color: var(--innovopedia-blue);
    text-transform: uppercase;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-decoration: none;
    margin-bottom: 10px;
    display: inline-block;
}
.side-title {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.2;
}
.side-title a { color: #fff; text-decoration: none; }

.home-briefing-bar {
    background: #fcfcfc;
    padding: 40px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    margin-bottom: 60px;
}

.grid-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 80px;
    margin-bottom: 80px;
}
.section-label {
    font-family: var(--h2-family);
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 3px;
    border-bottom: 3px solid #000;
    margin-bottom: 45px;
    padding-bottom: 15px;
}
.section-label span {
    background: #000;
    color: #fff;
    padding: 12px 20px;
}

.latest-feed {
    display: grid;
    gap: 40px;
}
.feed-item {
    display: flex;
    gap: 30px;
    align-items: center;
    padding-bottom: 40px;
    border-bottom: 1px solid #f0f0f0;
}
.item-thumb {
    width: 180px;
    height: 120px;
    flex-shrink: 0;
    border-radius: var(--round-5);
    overflow: hidden;
}
.item-thumb img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
.feed-item:hover .item-thumb img { transform: scale(1.05); }
.item-title {
    font-family: var(--h2-family);
    font-size: 24px;
    font-weight: 800;
    line-height: 1.3;
}
.item-title a { color: #000; text-decoration: none; transition: 0.2s; }
.item-title a:hover { color: var(--innovopedia-blue); }

.sidebar-block {
    margin-bottom: 60px;
}
.sidebar-title {
    font-size: 20px;
    font-weight: 900;
    margin-bottom: 30px;
    padding-left: 15px;
    border-left: 4px solid var(--innovopedia-blue);
}

@media (max-width: 1199px) {
    .hero-title { font-size: 48px; }
    .grid-layout { gap: 40px; }
}
@media (max-width: 991px) {
    .rb-container { padding-left: 20px; padding-right: 20px; }
    .hero-master-grid { grid-template-columns: 1fr; }
    .hero-side-stories { display: none; }
    .grid-layout { grid-template-columns: 1fr; }
    .hero-title { font-size: 36px; }
    .hero-wrap { height: 500px; }
    .item-thumb { width: 120px; height: 80px; }
    .item-title { font-size: 18px; }
}
</style>

<?php get_footer(); ?>
