<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_render_header_bi' ) ) {
	function foxiz_render_header_bi() {

		$settings  = foxiz_get_header_settings( 'hd1' );
		$classes   = [];
		$classes[] = 'header-wrap rb-section header-bi';
		$classes[] = 'header-wrapper';
		
		if ( foxiz_get_mobile_quick_access() ) {
			$classes[] = 'has-quick-menu';
		}
		?>
		<header id="site-header" class="<?php echo join( ' ', $classes ); ?>">
			<?php
			foxiz_render_top_site();
			foxiz_reading_process_indicator();
			?>
			
			<!-- Branding & Utility Row -->
			<div class="logo-sec bi-style">
				<div class="logo-sec-inner rb-container edge-padding">
					<div class="logo-sec-left">
						<?php foxiz_render_logo( $settings ); ?>
					</div>
					<div class="logo-sec-right">
						<div class="bi-utility-links">
							<a href="/subscribe" class="bi-link bi-subscribe">
								<i class="rbi rbi-arrow-right"></i> <?php echo esc_html__( 'Subscribe', 'foxiz' ); ?>
							</a>
							<a href="/newsletters" class="bi-link bi-newsletters">
								<?php echo esc_html__( 'Newsletters', 'foxiz' ); ?>
							</a>
							<div class="bi-burger-wrap">
								<?php foxiz_mobile_toggle_btn(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Main Navigation Row -->
			<div id="navbar-outer" class="navbar-outer bi-nav-style">
				<div id="sticky-holder" class="sticky-holder">
					<div class="navbar-wrap">
						<div class="rb-container edge-padding">
							<div class="navbar-inner">
								<div class="navbar-center">
									<?php
									foxiz_render_main_menu( 'bi-main-menu', $settings['sub_scheme'] );
									?>
								</div>
								<div class="navbar-right">
									<?php
									if ( ! empty( $settings['header_search_icon'] ) ) {
										foxiz_header_search( $settings );
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Greeting Bar -->
			<div class="bi-greeting-bar">
				<div class="rb-container edge-padding">
					<div class="bi-greeting-inner">
						<span class="bi-greeting-text">
							<?php echo innovopedia_get_greeting(); ?>
						</span>
						<a href="/briefing" class="bi-briefing-btn">
							<i class="rbi rbi-play"></i> <?php echo esc_html__( 'Your Briefing', 'foxiz' ); ?>
						</a>
					</div>
				</div>
			</div>

			<!-- Live Market Ticker (TradingView) -->
			<div class="bi-ticker-wrap">
				<div class="tradingview-widget-container">
					<div class="tradingview-widget-container__widget"></div>
					<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
					{
					"symbols": [
						{
						"proName": "NASDAQ:NVDA",
						"title": "NVDA"
						},
						{
						"proName": "NASDAQ:AAPL",
						"title": "AAPL"
						},
						{
						"proName": "NASDAQ:MSFT",
						"title": "MSFT"
						},
						{
						"proName": "BITSTAMP:BTCUSD",
						"title": "BTC"
						},
						{
						"proName": "BITSTAMP:ETHUSD",
						"title": "ETH"
						},
						{
						"proName": "NASDAQ:TSLA",
						"title": "TSLA"
						},
						{
						"proName": "NASDAQ:GOOGL",
						"title": "GOOGL"
						},
						{
						"proName": "BINANCE:SOLUSD",
						"title": "SOL"
						}
					],
					"showSymbolLogo": true,
					"colorTheme": "light",
					"isTransparent": true,
					"displayMode": "adaptive",
					"locale": "en"
					}
					</script>
				</div>
			</div>

			<?php 
			foxiz_header_mobile( $settings );
			foxiz_header_ad_widget_section(); 
			?>
		</header>
<?php
	}
}
