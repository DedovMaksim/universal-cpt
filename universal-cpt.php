<?php
/**
 * Plugin Name: Universal CPT
 * Description: Простой конструктор Custom Post Types через админку.
 * Version: 0.1.0
 * Author: Your Name
 * Text Domain: universal-cpt
 */

if (! defined('ABSPATH')) {
	exit;
}

define('UCPT_VERSION', '0.1.0');
define('UCPT_PATH', plugin_dir_path(__FILE__));
define('UCPT_URL', plugin_dir_url(__FILE__));

require_once UCPT_PATH . 'includes/functions.php';
require_once UCPT_PATH . 'includes/storage.php';
require_once UCPT_PATH . 'includes/validator.php';
require_once UCPT_PATH . 'includes/migrations.php';
require_once UCPT_PATH . 'includes/register.php';

if (is_admin()) {
	require_once UCPT_PATH . 'admin/menu.php';
	require_once UCPT_PATH . 'admin/page-list.php';
	require_once UCPT_PATH . 'admin/page-edit.php';
}

add_action('plugins_loaded', 'ucpt_boot');

function ucpt_boot(): void {
	ucpt_run_migrations();
}

add_action('init', 'ucpt_register_post_types');