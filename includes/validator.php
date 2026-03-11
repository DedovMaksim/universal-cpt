<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_normalize_post_type_config(array $input): array {
	$defaults = ucpt_get_default_post_type_config();

	$config = wp_parse_args($input, $defaults);

	$config['slug'] = substr(sanitize_key($config['slug']), 0, 20);
	$config['singular'] = sanitize_text_field($config['singular']);
	$config['plural'] = sanitize_text_field($config['plural']);
	$config['menu_icon'] = sanitize_text_field($config['menu_icon']);

	$config['public'] = ! empty($config['public']);
	$config['has_archive'] = ! empty($config['has_archive']);
	$config['show_in_rest'] = ! empty($config['show_in_rest']);

	$supports = is_array($config['supports']) ? $config['supports'] : [];
	$allowed_supports = ucpt_get_allowed_supports();

	$config['supports'] = array_values(array_intersect($allowed_supports, $supports));

	if (empty($config['supports'])) {
		$config['supports'] = ['title', 'editor'];
	}

	return $config;
}

function ucpt_validate_slug(string $slug, ?string $current_slug = null) {
	$slug = substr(sanitize_key($slug), 0, 20);

	if ($slug === '') {
		return new WP_Error('invalid_slug', 'Slug не может быть пустым.');
	}

	if (in_array($slug, ucpt_get_reserved_post_types(), true)) {
		return new WP_Error('reserved_slug', 'Этот slug зарезервирован WordPress.');
	}

	$registered = get_post_types([], 'names');

	if ($current_slug !== $slug && in_array($slug, $registered, true)) {
		return new WP_Error('existing_slug', 'Такой slug уже используется.');
	}

	return true;
}