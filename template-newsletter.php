<?php
/**
 * Template Name: Newsletter Landing Page
 * Description: A clean, high-conversion landing page for the Innovopedia Newsletter.
 */

get_header(); ?>

<div class="newsletter-landing">
    <div class="rb-container">
        <div class="newsletter-hero">
            <div class="newsletter-content">
                <span class="sub-label"><?php esc_html_e('JOIN THE INTELLIGENCE', 'innovopedia'); ?></span>
                <h1 class="newsletter-title"><?php esc_html_e('Insights for the Next Generation of Tech Leaders.', 'innovopedia'); ?></h1>
                <p class="newsletter-desc">
                    <?php esc_html_e('Get the latest tech intelligence, AI breakthroughs, and founder strategies delivered straight to your inbox every morning.', 'innovopedia'); ?>
                </p>
                
                <div class="newsletter-form-wrap">
                    <?php echo do_shortcode('[innovopedia_newsletter]'); ?>
                    <p class="newsletter-trust"><?php esc_html_e('Join 50,000+ tech professionals. No spam, ever.', 'innovopedia'); ?></p>
                </div>
            </div>
            
            <div class="newsletter-visual">
                <div class="mockup-screen">
                    <div class="mockup-header">
                        <div class="dots"><span></span><span></span><span></span></div>
                    </div>
                    <div class="mockup-body">
                        <div class="mock-line title"></div>
                        <div class="mock-line line-1"></div>
                        <div class="mock-line line-2"></div>
                        <div class="mock-line line-3"></div>
                        <div class="mock-grid">
                            <div class="box"></div>
                            <div class="box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="newsletter-features">
            <div class="feature-item">
                <div class="icon"><i class="rbi rbi-bolt"></i></div>
                <h3><?php esc_html_e('Daily Briefings', 'innovopedia'); ?></h3>
                <p><?php esc_html_e('The 5 most important tech stories summarized in 2 minutes.', 'innovopedia'); ?></p>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="rbi rbi-chart"></i></div>
                <h3><?php esc_html_e('Market Pulse', 'innovopedia'); ?></h3>
                <p><?php esc_html_e('Weekly data-driven insights into the tech stock market.', 'innovopedia'); ?></p>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="rbi rbi-award"></i></div>
                <h3><?php esc_html_e('Exclusive Strategy', 'innovopedia'); ?></h3>
                <p><?php esc_html_e('Interviews and playbooks from successful AI founders.', 'innovopedia'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
.newsletter-landing {
    padding: 100px 0;
    background: #f8f9fa;
    min-height: 80vh;
    display: flex;
    align-items: center;
}
.newsletter-hero {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
    margin-bottom: 100px;
}
.sub-label {
    display: inline-block;
    color: var(--g-color);
    font-weight: 800;
    letter-spacing: 2px;
    font-size: 12px;
    margin-bottom: 20px;
}
.newsletter-title {
    font-size: 56px;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 30px;
    color: #000;
}
.newsletter-desc {
    font-size: 20px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 40px;
}
.newsletter-form-wrap {
    max-width: 500px;
}
.newsletter-trust {
    margin-top: 20px;
    font-size: 13px;
    color: #888;
}

/* MOCKUP VISUAL */
.newsletter-visual {
    position: relative;
}
.mockup-screen {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 50px 100px -20px rgba(0,0,0,0.15), 0 30px 60px -30px rgba(0,0,0,0.2);
    overflow: hidden;
    border: 1px solid #eee;
}
.mockup-header {
    background: #f1f1f1;
    padding: 12px 15px;
}
.mockup-header .dots { display: flex; gap: 6px; }
.mockup-header .dots span { width: 8px; height: 8px; background: #ddd; border-radius: 50%; }
.mockup-body { padding: 40px; }
.mock-line { background: #f1f1f1; height: 12px; border-radius: 4px; margin-bottom: 15px; }
.mock-line.title { width: 60%; height: 20px; background: #eee; margin-bottom: 30px; }
.mock-line.line-1 { width: 100%; }
.mock-line.line-2 { width: 90%; }
.mock-line.line-3 { width: 95%; }
.mock-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px; }
.mock-grid .box { background: #f8f8f8; height: 80px; border-radius: 8px; }

/* FEATURES */
.newsletter-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}
.feature-item {
    text-align: center;
}
.feature-item .icon {
    font-size: 32px;
    color: var(--g-color);
    margin-bottom: 20px;
}
.feature-item h3 {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 15px;
}
.feature-item p {
    color: #666;
    line-height: 1.5;
}

@media (max-width: 991px) {
    .newsletter-hero { grid-template-columns: 1fr; gap: 50px; text-align: center; }
    .newsletter-form-wrap { margin: 0 auto; }
    .newsletter-visual { display: none; }
    .newsletter-features { grid-template-columns: 1fr; }
    .newsletter-title { font-size: 38px; }
}
</style>

<?php get_footer(); ?>
