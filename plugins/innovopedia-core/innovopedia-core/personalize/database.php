<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Foxiz_Personalize_Db' ) ) {
	class Foxiz_Personalize_Db {

		protected static $instance = null;
		private $wpdb;
		const TABLE_NAME = 'rb_personalize';

		public function __construct() {

			global $wpdb;
			$this->wpdb = $wpdb;

			$this->create_db_table();
		}

		public function get_table_name() {

			return $this->wpdb->prefix . self::TABLE_NAME;
		}

		function create_db_table() {

			$table_name      = $this->get_table_name();
			$charset_collate = $this->wpdb->get_charset_collate();

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
					`id` bigint(11) not null AUTO_INCREMENT,
					`identifier` varchar(50),
					`user_id` bigint(11) default 0,
					`ip` varchar (50),
					`action_key` varchar(50),
					`action_id` int(11),
					`action_value` varchar(50) default null,
					`created` datetime not null default '0000-00-00 00:00:00',			
					PRIMARY KEY (id)
			) {$charset_collate};";

			dbDelta( $sql );

			return true;
		}
	}
}
