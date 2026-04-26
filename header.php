<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-theme="<?php echo foxiz_get_theme_mode(); ?>">
<?php wp_body_open(); ?>

<div class="site-outer">
    
    <!-- INNOVOPEDIA MASTER HEADER -->
    <header class="innovopedia-header">
        <div class="rb-container">
            <!-- Top Row -->
            <div class="header-top">
                <div class="header-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">INNOVOPEDIA<span>.COM</span></a>
                </div>
                <div class="header-top-right">
                    <a href="#" class="btn-subscribe"><i class="rbi rbi-arrow-right-up"></i> <?php esc_html_e( 'Subscribe', 'innovopedia' ); ?></a>
                    <a href="#" class="btn-newsletters"><?php esc_html_e( 'Newsletters', 'innovopedia' ); ?></a>
                    <button class="hamburger-trigger">
                        <i class="rbi rbi-menu"></i>
                    </button>
                </div>
            </div>
            
            <!-- Sub Header (Categories) -->
            <div class="header-sub">
                <ul class="nav-categories">
                    <li><a href="/category/business">Business</a></li>
                    <li><a href="/category/tech">Tech</a></li>
                    <li><a href="/category/markets">Markets</a></li>
                    <li><a href="/category/ai">AI</a></li>
                    <li><a href="/category/strategy">Strategy</a></li>
                    <li><a href="/category/science">Science</a></li>
                    <li><a href="/category/reviews">Reviews</a></li>
                </ul>
                <button class="sub-search-trigger" onclick="window.location.href='/?s='">
                    <i class="rbi rbi-search"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- SLIDE-OUT SIDE PANEL -->
    <div class="side-overlay"></div>
    <div class="innovopedia-side-panel">
        <div class="side-panel-header">
            <div class="side-logo">
                <img src="/wp-content/themes/innovopedia/assets/images/logo-icon.png" style="width: 40px; height: 40px; border-radius: 6px;" alt="IN">
            </div>
            <div class="side-close"><i class="rbi rbi-close"></i></div>
        </div>
        
        <div class="side-search-wrap">
            <form action="/" method="get">
                <input type="text" name="s" class="side-search-input" placeholder="Search Innovopedia..." />
            </form>
        </div>

        <ul class="side-nav">
            <li><a href="/category/business">Business</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/tech">Tech</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/markets">Markets</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/ai">AI</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/strategy">Strategy</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/science">Science</a> <i class="rbi rbi-angle-down"></i></li>
            <li><a href="/category/reviews">Reviews</a> <i class="rbi rbi-angle-down"></i></li>
        </ul>

        <div class="side-footer">
            <a href="#" class="btn-subscribe" style="font-size: 18px;"><i class="rbi rbi-arrow-right-up"></i> Subscribe</a>
            <div class="side-socials">
                <a href="#"><i class="rbi rbi-facebook"></i></a>
                <a href="#"><i class="rbi rbi-twitter"></i></a>
                <a href="#"><i class="rbi rbi-linkedin"></i></a>
                <a href="#"><i class="rbi rbi-instagram"></i></a>
            </div>
            <div style="margin-top: 30px; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                <img src="/wp-content/themes/innovopedia/assets/images/logo-icon.png" style="width: 32px; height: 32px; border-radius: 4px;" alt="IN"> <?php esc_html_e( 'Get the app', 'innovopedia' ); ?>
            </div>
        </div>
    </div>

	<main class="site-wrap">
