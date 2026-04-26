<?php
/**
 * Innovopedia Premium Newsletter Module
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newsletter Shortcode
 * [innovopedia_newsletter]
 */
function innovopedia_newsletter_shortcode( $atts ) {
	$atts = shortcode_atts( [
		'title' => 'The Insight Ledger',
		'subtitle' => 'Join 12,000+ founders and builders receiving our daily AI-curated tech intelligence.',
		'count' => '12,000+'
	], $atts );

	ob_start();
	?>
	<div class="innovopedia-newsletter-block">
		<div class="newsletter-content">
			<div class="newsletter-meta">
				<span class="newsletter-badge"><?php esc_html_e( 'MEMBERSHIP', 'foxiz' ); ?></span>
				<span class="member-count"><i class="rbi rbi-user"></i> <?php echo esc_html( $atts['count'] ); ?></span>
			</div>
			<h2 class="newsletter-title"><?php echo esc_html( $atts['title'] ); ?></h2>
			<p class="newsletter-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
			
			<form class="newsletter-form">
				<input type="email" placeholder="<?php esc_attr_e( 'Enter your business email', 'foxiz' ); ?>" required />
				<button type="submit"><?php esc_html_e( 'Subscribe Now', 'foxiz' ); ?> <i class="rbi rbi-arrow-right"></i></button>
			</form>
			
			<p class="newsletter-disclaimer"><?php esc_html_e( 'Strictly zero spam. Unsubscribe anytime.', 'foxiz' ); ?></p>
		</div>
		<div class="newsletter-visual">
			<div class="visual-circle"></div>
			<div class="visual-icon">✨</div>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		$('.newsletter-form').on('submit', function(e) {
			e.preventDefault();
			const $form = $(this);
			const email = $form.find('input').val();
			const $button = $form.find('button');
			
			$button.html('<i class="rbi rbi-loading-spinner"></i>').prop('disabled', true);

			$.ajax({
				url: (typeof innovopediaBriefing !== 'undefined') ? innovopediaBriefing.ajax_url : '/wp-admin/admin-ajax.php',
				type: 'POST',
				data: {
					action: 'innovopedia_subscribe_newsletter',
					email: email,
					nonce: (typeof innovopediaBriefing !== 'undefined') ? innovopediaBriefing.nonce : ''
				},
				success: function(response) {
					if (response.success) {
						$form.fadeOut(300, function() {
							$form.after('<div class="newsletter-success">✨ ' + response.data.message + '</div>');
						});
					} else {
						alert(response.data || 'Subscription failed. Please try again.');
						$button.html('Subscribe Now').prop('disabled', false);
					}
				},
				error: function() {
					$button.html('Subscribe Now').prop('disabled', false);
				}
			});
		});
	});
	</script>

	<style>
	.newsletter-success {
		background: var(--g-color, #ff184e);
		color: #fff;
		padding: 20px;
		border-radius: 12px;
		font-weight: 700;
		text-align: center;
		animation: fadeInUp 0.5s ease;
		margin-top: 20px;
	}
	@keyframes fadeInUp {
		from { opacity: 0; transform: translateY(20px); }
		to { opacity: 1; transform: translateY(0); }
	}
	.innovopedia-newsletter-block {
		background: #000;
		color: #fff;
		padding: 60px;
		border-radius: 30px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: relative;
		overflow: hidden;
		margin: 40px 0;
		font-family: var(--body-family, sans-serif);
	}
	.newsletter-content {
		max-width: 60%;
		z-index: 2;
	}
	.newsletter-meta {
		display: flex;
		align-items: center;
		gap: 15px;
		margin-bottom: 20px;
	}
	.newsletter-badge {
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 1.5px;
		color: var(--g-color, #ff184e);
		border: 1px solid var(--g-color, #ff184e);
		padding: 4px 10px;
		border-radius: 4px;
	}
	.member-count {
		font-size: 13px;
		font-weight: 600;
		color: #aaa;
	}
	.newsletter-title {
		font-family: var(--h1-family, sans-serif);
		font-size: 42px;
		font-weight: 900;
		margin-bottom: 15px;
		color: #fff;
	}
	.newsletter-subtitle {
		font-size: 18px;
		color: #ccc;
		margin-bottom: 30px;
		line-height: 1.5;
	}
	.newsletter-form {
		display: flex;
		gap: 10px;
		margin-bottom: 15px;
	}
	.newsletter-form input {
		flex: 1;
		background: #1a1a1a;
		border: 1px solid #333;
		padding: 15px 25px;
		border-radius: 12px;
		color: #fff;
		font-size: 16px;
	}
	.newsletter-form button {
		background: var(--g-color, #ff184e);
		color: #fff;
		border: none;
		padding: 15px 30px;
		border-radius: 12px;
		font-weight: 800;
		cursor: pointer;
		transition: transform 0.2s;
	}
	.newsletter-form button:hover {
		transform: translateY(-2px);
	}
	.newsletter-disclaimer {
		font-size: 12px;
		color: #666;
	}
	.newsletter-visual {
		position: absolute;
		right: -50px;
		top: -50px;
		width: 300px;
		height: 300px;
		z-index: 1;
	}
	.visual-circle {
		width: 100%;
		height: 100%;
		background: radial-gradient(circle, #ff184e99 0%, transparent 70%);
		opacity: 0.3;
	}
	.visual-icon {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 80px;
		opacity: 0.1;
	}

	@media (max-width: 768px) {
		.innovopedia-newsletter-block {
			flex-direction: column;
			padding: 40px 30px;
			text-align: center;
		}
		.newsletter-content {
			max-width: 100%;
		}
		.newsletter-meta {
			justify-content: center;
		}
		.newsletter-form {
			flex-direction: column;
		}
		.newsletter-title {
			font-size: 32px;
		}
	}
	</style>
	<?php
	return ob_get_clean();
}
add_shortcode( 'innovopedia_newsletter', 'innovopedia_newsletter_shortcode' );

/**
 * AJAX Handler for Newsletter Subscription
 */
function innovopedia_subscribe_newsletter_ajax() {
	check_ajax_referer( 'briefing_nonce', 'nonce' );

	$email = sanitize_email( $_POST['email'] );
	if ( ! is_email( $email ) ) {
		wp_send_json_error( 'Invalid email address.' );
	}

	$subscribers = get_option( 'innovopedia_subscribers', [] );
	if ( ! in_array( $email, $subscribers ) ) {
		$subscribers[] = [
			'email' => $email,
			'date'  => current_time( 'mysql' )
		];
		update_option( 'innovopedia_subscribers', $subscribers );
	}

	$welcome_message = "You're in! We're preparing your first AI-curated briefing.";
	
	$api_key = get_option( 'innovopedia_openrouter_api_key' );
	if ( ! empty( $api_key ) ) {
		$response = wp_remote_post( 'https://openrouter.ai/api/v1/chat/completions', [
			'headers' => [ 'Authorization' => 'Bearer ' . $api_key, 'Content-Type'  => 'application/json' ],
			'body' => json_encode([
				'model' => 'mistralai/mistral-7b-instruct:free',
				'messages' => [['role' => 'system', 'content' => 'Write a 1-sentence extremely exciting welcome message for a new subscriber to "The Insight Ledger" newsletter. Focus on tech and founders.']]
			])
		]);
		if ( ! is_wp_error( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $body['choices'][0]['message']['content'] ) ) {
				$welcome_message = $body['choices'][0]['message']['content'];
			}
		}
	}

	wp_send_json_success([ 'message' => $welcome_message ]);
}
add_action( 'wp_ajax_innovopedia_subscribe_newsletter', 'innovopedia_subscribe_newsletter_ajax' );
add_action( 'wp_ajax_nopriv_innovopedia_subscribe_newsletter', 'innovopedia_subscribe_newsletter_ajax' );
