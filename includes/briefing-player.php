<?php
/**
 * Innovopedia Sticky Briefing Mini-Player
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Mini-Player to Footer
 */
function innovopedia_briefing_player_html() {
	?>
	<div id="innovopedia-mini-player" class="mini-player" style="display:none;">
		<div class="mini-player-inner">
			<div class="player-left">
				<div class="player-icon">⚡</div>
				<div class="player-info">
					<span class="player-label"><?php esc_html_e( 'Listening to Briefing', 'foxiz' ); ?></span>
					<span id="mini-player-title" class="player-title">Preparing...</span>
				</div>
			</div>
			<div class="player-controls">
				<button id="mini-play-pause" class="mini-btn"><i class="rbi rbi-play"></i></button>
				<button id="mini-skip" class="mini-btn"><i class="rbi rbi-next"></i></button>
				<div id="mini-progress-wrap" class="mini-progress-wrap">
					<div id="mini-progress-bar" class="mini-progress-bar"></div>
				</div>
				<button id="mini-close" class="mini-btn-close">&times;</button>
			</div>
		</div>
	</div>

	<style>
	.mini-player {
		position: fixed;
		bottom: 20px;
		left: 50%;
		transform: translateX(-50%);
		width: 90%;
		max-width: 600px;
		background: #000;
		color: #fff;
		border-radius: 50px;
		padding: 10px 25px;
		z-index: 9997;
		box-shadow: 0 10px 30px rgba(0,0,0,0.3);
		border: 1px solid #333;
		font-family: var(--body-family);
	}
	.mini-player-inner {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
	}
	.player-left {
		display: flex;
		align-items: center;
		gap: 12px;
		overflow: hidden;
	}
	.player-icon {
		background: var(--g-color);
		width: 30px;
		height: 30px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 14px;
		flex-shrink: 0;
	}
	.player-info {
		display: flex;
		flex-direction: column;
		overflow: hidden;
	}
	.player-label {
		font-size: 10px;
		font-weight: 800;
		text-transform: uppercase;
		color: var(--g-color);
		letter-spacing: 0.5px;
	}
	.player-title {
		font-size: 13px;
		font-weight: 600;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.player-controls {
		display: flex;
		align-items: center;
		gap: 15px;
	}
	.mini-btn {
		background: none;
		border: none;
		color: #fff;
		font-size: 18px;
		cursor: pointer;
		padding: 0;
	}
	.mini-progress-wrap {
		width: 100px;
		height: 4px;
		background: #333;
		border-radius: 2px;
		overflow: hidden;
	}
	.mini-progress-bar {
		height: 100%;
		background: var(--g-color);
		width: 0%;
	}
	.mini-btn-close {
		background: none;
		border: none;
		color: #666;
		font-size: 20px;
		cursor: pointer;
		margin-left: 5px;
	}
	</style>
	<?php
}
add_action( 'wp_footer', 'innovopedia_briefing_player_html' );
