<?php
/**
 * Innovopedia AI Search Engine
 * Replaces standard search with an AI-powered synthesis engine.
 */

defined( 'ABSPATH' ) || exit;

/**
 * AJAX Handler for AI Search
 */
function innovopedia_ai_search_ajax() {
	check_ajax_referer( 'briefing_nonce', 'nonce' );

	$query = sanitize_text_field( $_POST['query'] );
	if ( empty( $query ) ) {
		wp_send_json_error( 'Query is empty' );
	}

	// 1. Perform a standard WP Search to get relevant context
	$search_results = new WP_Query([
		's'              => $query,
		'posts_per_page' => 5,
		'post_status'    => 'publish'
	]);

	$context = "";
	$sources = [];

	if ( $search_results->have_posts() ) {
		while ( $search_results->have_posts() ) {
			$search_results->the_post();
			$context .= "Source: " . get_the_title() . "\nContent: " . wp_trim_words( get_the_content(), 150 ) . "\n\n";
			$sources[] = [
				'title' => get_the_title(),
				'link'  => get_permalink(),
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' )
			];
		}
		wp_reset_postdata();
	}

	if ( empty( $context ) ) {
		wp_send_json_success([
			'answer'  => "I couldn't find any specific articles on Innovopedia about '$query'. Try searching for something else related to founders, tech, or business.",
			'sources' => []
		]);
		return;
	}

	// 2. Send context to Mistral to generate an answer
	$answer = innovopedia_generate_ai_search_answer( $query, $context );

	wp_send_json_success([
		'answer'  => $answer,
		'sources' => $sources
	]);
}
add_action( 'wp_ajax_innovopedia_ai_search', 'innovopedia_ai_search_ajax' );
add_action( 'wp_ajax_nopriv_innovopedia_ai_search', 'innovopedia_ai_search_ajax' );

/**
 * Generate AI Answer using OpenRouter/Mistral
 */
function innovopedia_generate_ai_search_answer( $query, $context ) {
	$api_key = get_option( 'innovopedia_openrouter_api_key' );
	$model = get_option( 'innovopedia_ai_model', 'mistralai/mistral-7b-instruct:free' );

	if ( empty( $api_key ) ) {
		return "AI Search is currently in maintenance mode. Please check back later.";
	}

	$prompt = "You are the Innovopedia AI Insight Engine. Based ONLY on the provided article snippets from our platform, answer the user's question: '$query'. 
	
	Rules:
	1. Be concise and professional (max 2 paragraphs).
	2. Use a 'Global Business Insight' tone.
	3. If the answer isn't in the context, say you don't have enough information from our specific articles.
	
	Context:
	$context";

	$response = wp_remote_post( 'https://openrouter.ai/api/v1/chat/completions', [
		'headers' => [
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type'  => 'application/json',
			'HTTP-Referer'  => home_url(),
		],
		'body' => json_encode([
			'model' => $model,
			'messages' => [
				['role' => 'system', 'content' => $prompt],
				['role' => 'user', 'content' => "Question: $query"]
			],
			'temperature' => 0.5
		]),
		'timeout' => 30
	]);

	if ( is_wp_error( $response ) ) return "Error connecting to AI engine.";

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	return isset( $body['choices'][0]['message']['content'] ) ? $body['choices'][0]['message']['content'] : "I processed the search but couldn't generate a summary.";
}

/**
 * Replace Search Template with AI Search UI
 */
function innovopedia_replace_search_template( $template ) {
	if ( is_search() ) {
		$new_template = locate_template( [ 'search-ai.php' ] );
		if ( ! empty( $new_template ) ) {
			return $new_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'innovopedia_replace_search_template', 99 );
