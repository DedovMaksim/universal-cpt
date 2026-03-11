<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_get_reserved_post_types(): array {
	return [
		'post',
		'page',
		'attachment',
		'revision',
		'nav_menu_item',
		'custom_css',
		'customize_changeset',
		'oembed_cache',
		'user_request',
		'wp_block',
		'wp_template',
		'wp_template_part',
		'wp_navigation',
	];
}

function ucpt_get_allowed_supports(): array {
	return [
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'comments',
		'revisions',
		'custom-fields',
		'author',
		'page-attributes',
	];
}

function ucpt_get_default_post_type_config(): array {
	return [
		'slug'         => '',
		'singular'     => '',
		'plural'       => '',
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-admin-post',
		'supports'     => ['title', 'editor'],
	];
}