<?php

if (! defined('ABSPATH')) {
	exit;
}

add_action('admin_menu', 'ucpt_register_admin_menu');

function ucpt_register_admin_menu(): void {
	add_menu_page(
		'Universal CPT',
		'Universal CPT',
		'manage_options',
		'ucpt',
		'ucpt_render_list_page',
		'dashicons-database',
		58
	);

	add_submenu_page(
		'ucpt',
		'Все типы записей',
		'Все типы записей',
		'manage_options',
		'ucpt',
		'ucpt_render_list_page'
	);

	add_submenu_page(
		'ucpt',
		'Добавить тип записи',
		'Добавить новый',
		'manage_options',
		'ucpt-add',
		'ucpt_render_edit_page'
	);
}