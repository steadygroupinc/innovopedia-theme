<?php
/**
 * AI Search Template for Innovopedia - NATIVE FOXIZ STYLE
 */

get_header();
$query = get_search_query();
?>

<div class="rb-container site-content">
    <div class="rb-row">
        <div class="rb-col-m12">
            
            <!-- Native-Style Search Header -->
            <header class="archive-header page-header">
                <div class="archive-header-inner">
                    <h1 class="archive-title">
                        <span class="label-text"><?php esc_html_e( 'Insight Search:', 'foxiz' ); ?></span>
                        <span class="query-text"><?php echo esc_html( $query ); ?></span>
                    </h1>
                    
                    <!-- Integrated Search Bar -->
                    <div class="page-search-form-wrap" style="max-width: 600px; margin-top: 30px;">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <div class="search-form-inner" style="position: relative;">
                                <input type="search" class="search-input" placeholder="<?php esc_attr_e( 'Ask Innovopedia anything...', 'foxiz' ); ?>" value="<?php echo $query; ?>" name="s" style="width: 100%; padding: 15px 25px; border-radius: var(--round-7); border: 1px solid var(--flex-gray-15); background: var(--solid-light);" />
                                <button type="submit" class="search-submit" style="position: absolute; right: 5px; top: 5px; bottom: 5px; padding: 0 20px; background: var(--g-color); color: #fff; border: none; border-radius: var(--round-5);"><i class="rbi rbi-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </header>

            <!-- AI Answer Section (Integrated like a Featured Block) -->
            <?php if ( ! empty( $query ) ) : ?>
            <section id="ai-answer-section" class="ai-answer-container">
                <div class="ai-answer-box">
                    <div class="ai-label-wrap">
                        <span class="ai-label-tag"><?php esc_html_e( 'AI SYNTHESIS', 'foxiz' ); ?></span>
                    </div>
                    <div id="ai-answer-content" class="ai-answer-text">
                        <div class="ai-loading-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <div id="ai-sources-wrap" class="ai-sources-block" style="display:none;">
                        <span class="sources-title"><?php esc_html_e( 'CITED SOURCES:', 'foxiz' ); ?></span>
                        <div id="ai-sources-list" class="sources-pills"></div>
                    </div>
                </div>
            </section>
            <?php else : ?>
            <!-- Suggested Discover Section -->
            <div class="suggested-discovery">
                <h3 class="suggested-heading"><?php esc_html_e( 'Trending Insight Topics', 'foxiz' ); ?></h3>
                <div class="topic-grid">
                    <a href="<?php echo esc_url( home_url( '/?s=AI+Innovation' ) ); ?>"><?php esc_html_e( 'AI Innovation', 'foxiz' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/?s=Venture+Capital' ) ); ?>"><?php esc_html_e( 'Venture Capital', 'foxiz' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/?s=Growth+Hacking' ) ); ?>"><?php esc_html_e( 'Growth Hacking', 'foxiz' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/?s=Founder+Stories' ) ); ?>"><?php esc_html_e( 'Founder Stories', 'foxiz' ); ?></a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Native Results Feed -->
            <div class="search-results-feed">
                <h3 class="section-heading"><?php echo ! empty( $query ) ? esc_html__( 'Article Results', 'foxiz' ) : esc_html__( 'Latest Intelligence', 'foxiz' ); ?></h3>
                <?php
                if ( have_posts() ) {
                    $foxiz_settings = foxiz_get_archive_page_settings( 'search_' );
                    foxiz_the_blog( $foxiz_settings );
                } else {
                    if ( empty( $query ) ) {
                        $latest = new WP_Query([ 'posts_per_page' => 8 ]);
                        if ( $latest->have_posts() ) {
                            $foxiz_settings = foxiz_get_archive_page_settings( 'search_' );
                            foxiz_the_blog( $foxiz_settings );
                        }
                    } else {
                        echo '<p class="no-results">' . esc_html__( 'No specific matches found. Try broadening your terms.', 'foxiz' ) . '</p>';
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    const query = '<?php echo esc_js( $query ); ?>';
    if (query) {
        $.ajax({
            url: innovopediaBriefing.ajax_url,
            type: 'POST',
            data: { action: 'innovopedia_ai_search', query: query, nonce: innovopediaBriefing.nonce },
            success: function(response) {
                if (response.success) {
                    const contentDiv = $('#ai-answer-content');
                    contentDiv.empty();
                    let i = 0;
                    const text = response.data.answer;
                    function typeWriter() {
                        if (i < text.length) {
                            contentDiv.append(text.charAt(i));
                            i++;
                            setTimeout(typeWriter, 15);
                        } else {
                            if (response.data.sources.length > 0) {
                                const list = $('#ai-sources-list');
                                response.data.sources.forEach(s => {
                                    list.append(`<a href="${s.link}" class="source-pill">${s.title}</a>`);
                                });
                                $('#ai-sources-wrap').fadeIn();
                            }
                        }
                    }
                    typeWriter();
                }
            }
        });
    }
});
</script>

<style>
/* NATIVE FOXIZ SEARCH INTEGRATION */
.ai-answer-container {
    margin-bottom: 50px;
    background: var(--solid-light);
    border-radius: var(--round-7);
    border-left: 4px solid var(--g-color);
    padding: 35px;
    box-shadow: 0 5px 20px var(--shadow-7);
}

.ai-label-tag {
    background: var(--g-color);
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: var(--round-3);
    text-transform: uppercase;
    margin-bottom: 20px;
    display: inline-block;
}

.ai-answer-text {
    font-size: 18px;
    line-height: 1.6;
    color: var(--body-fcolor);
    font-family: var(--body-family);
}

.ai-sources-block {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--flex-gray-15);
}

.sources-title {
    font-size: 11px;
    font-weight: 800;
    color: var(--meta-fcolor);
    margin-bottom: 15px;
    display: block;
}

.sources-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.source-pill {
    font-size: 12px;
    font-weight: 700;
    background: var(--flex-gray-7);
    color: var(--body-fcolor);
    padding: 6px 12px;
    border-radius: var(--round-3);
    transition: var(--effect);
}

.source-pill:hover {
    background: var(--g-color);
    color: #fff;
}

.section-heading {
    font-family: var(--h2-family);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--flex-gray-15);
}

.suggested-discovery {
    padding: 40px 0;
    text-align: center;
}

.topic-grid {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.topic-grid a {
    padding: 10px 20px;
    border: 1px solid var(--flex-gray-15);
    border-radius: var(--round-5);
    font-weight: 700;
    transition: var(--effect);
}

.topic-grid a:hover {
    border-color: var(--g-color);
    color: var(--g-color);
}

/* Loading Dots */
.ai-loading-dots span {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: var(--g-color);
    border-radius: 50%;
    margin-right: 5px;
    animation: dots 1.4s infinite ease-in-out both;
}
@keyframes dots {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1.0); }
}
</style>

<?php get_footer(); ?>
