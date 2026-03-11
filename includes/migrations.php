<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_run_migrations(): void {
	$version = ucpt_get_schema_version();

	if ($version < 1) {
		$post_types = ucpt_get_post_types();
		$normalized = [];

		foreach ($post_types as $slug => $config) {
			if (! is_array($config)) {
				continue;
			}

			$config['slug'] = $config['slug'] ?? $slug;
			$config = ucpt_normalize_post_type_config($config);

			if ($config['slug'] !== '') {
				$normalized[$config['slug']] = $config;
			}
		}

		ucpt_save_post_types($normalized);
		ucpt_set_schema_version(1);
	}
}