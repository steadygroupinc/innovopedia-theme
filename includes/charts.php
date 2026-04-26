<?php
/**
 * Innovopedia Interactive Charts Module
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue Chart.js
 */
function innovopedia_enqueue_charts() {
	wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', [], '4.4.1', true );
}
add_action( 'wp_enqueue_scripts', 'innovopedia_enqueue_charts' );

/**
 * Chart Shortcode
 * [innovopedia_chart type="line" labels="Jan,Feb,Mar" data="10,45,30" title="Market Growth"]
 */
function innovopedia_chart_shortcode( $atts ) {
	$atts = shortcode_atts( [
		'type'   => 'line',
		'labels' => '',
		'data'   => '',
		'title'  => 'Insight Data',
		'color'  => ''
	], $atts );

	$id = 'chart-' . uniqid();
	$labels = explode( ',', $atts['labels'] );
	$data = explode( ',', $atts['data'] );
	$brand_color = ! empty( $atts['color'] ) ? $atts['color'] : '#ff184e'; // Using site primary color

	ob_start();
	?>
	<div class="innovopedia-chart-container" style="margin: 40px 0; padding: 20px; background: var(--solid-light); border-radius: 20px; border: 1px solid var(--flex-gray-15);">
		<canvas id="<?php echo esc_attr( $id ); ?>"></canvas>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const ctx = document.getElementById('<?php echo esc_js( $id ); ?>');
		new Chart(ctx, {
			type: '<?php echo esc_js( $atts['type'] ); ?>',
			data: {
				labels: <?php echo json_encode( $labels ); ?>,
				datasets: [{
					label: '<?php echo esc_js( $atts['title'] ); ?>',
					data: <?php echo json_encode( $data ); ?>,
					borderColor: '<?php echo esc_js( $brand_color ); ?>',
					backgroundColor: '<?php echo esc_js( $brand_color ); ?>22',
					borderWidth: 3,
					tension: 0.4,
					fill: true,
					pointBackgroundColor: '<?php echo esc_js( $brand_color ); ?>',
					pointRadius: 5
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						display: true,
						labels: {
							font: {
								family: "'Outfit', sans-serif",
								size: 14,
								weight: 'bold'
							}
						}
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						grid: {
							color: 'rgba(128, 128, 128, 0.1)'
						}
					},
					x: {
						grid: {
							display: false
						}
					}
				}
			}
		});
	});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'innovopedia_chart', 'innovopedia_chart_shortcode' );
