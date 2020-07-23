<?php
/*
 * Plugin Name: Import tracking
 * Description: Импорт файлов для добавления в базу данных по отслеживанию заказов
 * Version: 1.0
 * Author: Родион Рословец
 * Author URI: https://vk.com/id69478429
 */

register_activation_hook(__FILE__, 'Import_tracking_activation');
function Import_tracking_activation()
{
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	function create_table()
	{
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = $wpdb->get_blog_prefix() . 'tracking_info';
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

		$sql = "CREATE TABLE {$table_name} (
	id bigint(20) unsigned NOT NULL auto_increment,
	client_id bigint(20) unsigned NOT NULL,
	cargo_id bigint(20) unsigned NOT NULL,
	cargo_weight bigint(20) unsigned NOT NULL,
	cargo_size varchar(255) NOT NULL default '',
	cargo_count varchar(255) NOT NULL default '',
	cargo_curr_status bigint(20) unsigned NOT NULL,
	status_1 varchar(255) NOT NULL default '',
	status_1_date varchar(255) NOT NULL default '',
	status_2 varchar(255) NOT NULL default '',
	status_2_date varchar(255) NOT NULL default '',
	status_3 varchar(255) NOT NULL default '',
	status_3_date varchar(255) NOT NULL default '',
	status_4 varchar(255) NOT NULL default '',
	status_4_date varchar(255) NOT NULL default '',
	status_5 varchar(255) NOT NULL default '',
	status_5_date varchar(255) NOT NULL default '',
	PRIMARY KEY  (id)
	)
	{$charset_collate};";

		dbDelta($sql);
	}

	create_table();
}

add_action('admin_print_footer_scripts', 'newFunction');

function newFunction()
{
	if ($GLOBALS['pagenow'] !== 'index.php')
		return;

	echo '<script type="text/javascript" src="' . get_site_url() . '/wp-content/plugins/Import-tracking/addForm.js">';
}

add_action('wp_enqueue_scripts', 'add_import_scripts');

function add_import_scripts(){
	wp_enqueue_style('import_style', get_home_url().'/wp-content/plugins/Import-tracking/import-tracking.css');
	wp_enqueue_script('import_search_script', get_home_url().'/wp-content/plugins/Import-tracking/search.js');
}

register_deactivation_hook( __FILE__, 'Import_tracking_deactivation');

function Import_tracking_deactivation(){
	global $wpdb;

	$table_name = $wpdb->get_blog_prefix() . 'tracking_info';

	$sql = "DROP TABLE $table_name";

	$wpdb->query($sql);
}

