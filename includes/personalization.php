<?php
/**
 * Innovopedia AI Personalization Engine - OPTIMIZED
 */

defined( 'ABSPATH' ) || exit;

/**
 * Track User History (Moved to template_redirect to avoid memory issues)
 */
function innovopedia_track_user_history() {
	if ( ! is_singular( 'post' ) || is_admin() ) {
		return;
	}

	$post_id = get_the_ID();
	$history = isset( $_COOKIE['innovopedia_history'] ) ? json_decode( stripslashes( $_COOKIE['innovopedia_history'] ), true ) : [];

	if ( ! is_array( $history ) ) { $history = []; }

	// Add current post to history, limit to last 5 for performance
	if ( ( $key = array_search( $post_id, $history ) ) !== false ) {
		unset( $history[ $key ] );
	}
	array_unshift( $history, $post_id );
	$history = array_slice( $history, 0, 5 );

	// Use a flag to prevent multiple sets in one request
	if ( ! defined( 'INNOVOPEDIA_HISTORY_SET' ) ) {
		setcookie( 'innovopedia_history', json_encode( $history ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
		define( 'INNOVOPEDIA_HISTORY_SET', true );
	}
}
add_action( 'template_redirect', 'innovopedia_track_user_history' );

/**
 * Get Recommended Topics - OPTIMIZED
 */
function innovopedia_get_ai_personal_recommendation() {
	if ( is_admin() ) return ['message' => '', 'topics' => []];

	$history = isset( $_COOKIE['innovopedia_history'] ) ? json_decode( stripslashes( $_COOKIE['innovopedia_history'] ), true ) : [];

	if ( empty( $history ) || ! is_array( $history ) ) {
		return [
			'message' => 'Start reading to see your personalized AI insights.',
			'topics'  => []
		];
	}

	$titles = [];
	foreach ( $history as $pid ) {
		if ( get_post_status( $pid ) === 'publish' ) {
			$titles[] = get_the_title( $pid );
		}
	}

	if ( empty( $titles ) ) return ['message' => 'Explore our latest insights.', 'topics' => []];

	$context = implode( ', ', $titles );
	$api_key = get_option( 'innovopedia_openrouter_api_key' );

	if ( empty( $api_key ) ) {
		return [ 'message' => 'Add your API key to enable AI personalization.', 'topics' => [] ];
	}

	// Use a very specific cache key
	$cache_key = 'ai_p_v2_' . md5( $context );
	$cached_result = get_transient( $cache_key );
	if ( $cached_result ) return $cached_result;

	$response = wp_remote_post( 'https://openrouter.ai/api/v1/chat/completions', [
		'headers' => [ 'Authorization' => 'Bearer ' . $api_key, 'Content-Type'  => 'application/json' ],
		'body' => json_encode([
			'model' => 'mistralai/mistral-7b-instruct:free',
			'messages' => [
				['role' => 'system', 'content' => 'Based on the user history, write a 1-sentence personalized welcome.'],
				['role' => 'user', 'content' => "History: $context"]
			]
		]),
		'timeout' => 5
	]);

	if ( is_wp_error( $response ) ) return ['message' => 'Ready for more?', 'topics' => []];

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	$ai_text = isset( $body['choices'][0]['message']['content'] ) ? $body['choices'][0]['message']['content'] : 'Discover more insights tailored for you.';

	$result = [ 'message' => $ai_text, 'topics' => [] ];
	set_transient( $cache_key, $result, HOUR_IN_SECONDS ); // Reduced to 1 hour
	
	return $result;
}

/**
 * Shortcode to display Personalization Block
 */
function innovopedia_for_you_shortcode() {
	$ai_data = innovopedia_get_ai_personal_recommendation();
	$history = isset( $_COOKIE['innovopedia_history'] ) ? json_decode( stripslashes( $_COOKIE['innovopedia_history'] ), true ) : [];

	if ( ! is_array( $history ) ) { $history = []; }

	ob_start();
	?>
	<div class="innovopedia-personal-wrap">
		<div class="personal-header">
			<span class="ai-icon">✨</span>
			<h3 class="personal-title"><?php esc_html_e( 'For You', 'foxiz' ); ?></h3>
		</div>
		<div class="personal-ai-message">
			<p><?php echo esc_html( $ai_data['message'] ); ?></p>
		</div>
		<?php if ( ! empty( $history ) ) : ?>
			<div class="personal-history">
				<h4><?php esc_html_e( 'Recently Viewed', 'foxiz' ); ?></h4>
				<div class="history-list">
					<?php 
					$count = 0;
					foreach ( $history as $pid ) {
						if ( $count >= 3 ) break;
						if ( get_post_status( $pid ) !== 'publish' ) continue;
						$count++;
						?>
						<a href="<?php echo get_permalink( $pid ); ?>" class="history-item">
							<span class="history-dot"></span>
							<span class="history-title"><?php echo get_the_title( $pid ); ?></span>
						</a>
						<?php 
					} 
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<style>
	.innovopedia-personal-wrap {
		background: var(--solid-light);
		padding: 30px;
		border-radius: var(--round-7);
		border: 1px solid var(--flex-gray-15);
		font-family: var(--body-family);
		transition: var(--effect);
	}
	.personal-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 20px;
	}
	.personal-title {
		font-family: var(--h1-family);
		margin: 0;
		font-size: 22px;
		font-weight: 700;
		color: var(--body-fcolor);
	}
	.personal-ai-message {
		font-size: 16px;
		line-height: 1.6;
		color: var(--body-fcolor);
		margin-bottom: 30px;
		padding-left: 15px;
		border-left: 3px solid var(--g-color);
	}
	.personal-history h4 {
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: 1.5px;
		color: var(--meta-fcolor);
		margin-bottom: 20px;
		font-weight: 800;
	}
	.history-list {
		display: flex;
		flex-direction: column;
		gap: 15px;
	}
	.history-item {
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 14px;
		font-weight: 600;
		text-decoration: none;
		color: var(--body-fcolor);
		transition: var(--effect);
	}
	.history-item:hover {
		color: var(--g-color);
		transform: translateX(5px);
	}
	.history-dot {
		width: 6px;
		height: 6px;
		background: var(--g-color);
		border-radius: 50%;
		flex-shrink: 0;
	}
	</style>
	<?php
	return ob_get_clean();
}
add_shortcode( 'innovopedia_for_you', 'innovopedia_for_you_shortcode' );
