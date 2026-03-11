<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_get_post_types(): array {
	$post_types = get_option('ucpt_post_types', []);

	return is_array($post_types) ? $post_types : [];
}

function ucpt_save_post_types(array $post_types): bool {
	return update_option('ucpt_post_types', $post_types);
}

function ucpt_get_schema_version(): int {
	return (int) get_option('ucpt_schema_version', 0);
}

function ucpt_set_schema_version(int $version): bool {
	return update_option('ucpt_schema_version', $version);
}