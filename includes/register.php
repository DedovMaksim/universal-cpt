<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_register_post_types(): void {
	$post_types = ucpt_get_post_types();

	foreach ($post_types as $slug => $config) {
		if (! is_array($config)) {
			continue;
		}

		$config = ucpt_normalize_post_type_config($config);

		$validation = ucpt_validate_slug($config['slug']);

		if (is_wp_error($validation)) {
			continue;
		}

		$labels = [
			'name'               => $config['plural'],
			'singular_name'      => $config['singular'],
			'menu_name'          => $config['plural'],
			'name_admin_bar'     => $config['singular'],
			'add_new'            => 'Добавить',
			'add_new_item'       => 'Добавить запись',
			'edit_item'          => 'Редактировать запись',
			'new_item'           => 'Новая запись',
			'view_item'          => 'Просмотреть запись',
			'all_items'          => $config['plural'],
			'search_items'       => 'Поиск',
			'not_found'          => 'Ничего не найдено',
			'not_found_in_trash' => 'В корзине ничего не найдено',
		];

		register_post_type($config['slug'], [
			'labels'       => $labels,
			'public'       => $config['public'],
			'has_archive'  => $config['has_archive'],
			'show_in_rest' => $config['show_in_rest'],
			'menu_icon'    => $config['menu_icon'],
			'supports'     => $config['supports'],
		]);
	}
}