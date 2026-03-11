<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_render_edit_page(): void {
	if (! current_user_can('manage_options')) {
		wp_die('Недостаточно прав.');
	}

	$action = isset($_GET['action']) ? sanitize_key($_GET['action']) : 'add';
	$current_slug = isset($_GET['slug']) ? sanitize_key($_GET['slug']) : '';

	$post_types = ucpt_get_post_types();
	$config = ucpt_get_default_post_type_config();

	if ($action === 'edit' && $current_slug && isset($post_types[$current_slug])) {
		$config = wp_parse_args($post_types[$current_slug], $config);
	}

	$error_message = '';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		check_admin_referer('ucpt_save_post_type');

		$input = [
			'slug'         => $_POST['slug'] ?? '',
			'singular'     => $_POST['singular'] ?? '',
			'plural'       => $_POST['plural'] ?? '',
			'public'       => $_POST['public'] ?? '',
			'has_archive'  => $_POST['has_archive'] ?? '',
			'show_in_rest' => $_POST['show_in_rest'] ?? '',
			'menu_icon'    => $_POST['menu_icon'] ?? '',
			'supports'     => $_POST['supports'] ?? [],
		];

		$normalized = ucpt_normalize_post_type_config($input);
		$validation = ucpt_validate_slug($normalized['slug'], $current_slug ?: null);

		if (is_wp_error($validation)) {
			$error_message = $validation->get_error_message();
			$config = $normalized;
		} else {
			if ($action === 'edit' && $current_slug && $current_slug !== $normalized['slug']) {
				unset($post_types[$current_slug]);
			}

			$post_types[$normalized['slug']] = $normalized;
			ucpt_save_post_types($post_types);

			$config = $normalized;

			echo '<div class="notice notice-success is-dismissible"><p>CPT сохранён.</p></div>';
		}
	}

	if ($error_message) {
		echo '<div class="notice notice-error"><p>' . esc_html($error_message) . '</p></div>';
	}

	$allowed_supports = ucpt_get_allowed_supports();
	?>
	<div class="wrap">
		<h1><?php echo $action === 'edit' ? 'Редактировать тип записи' : 'Добавить тип записи'; ?></h1>

		<form method="post">
			<?php wp_nonce_field('ucpt_save_post_type'); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="ucpt-singular">Единственное название</label></th>
						<td><input name="singular" type="text" id="ucpt-singular" class="regular-text" value="<?php echo esc_attr($config['singular']); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="ucpt-plural">Множественное название</label></th>
						<td><input name="plural" type="text" id="ucpt-plural" class="regular-text" value="<?php echo esc_attr($config['plural']); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="ucpt-slug">Slug</label></th>
						<td><input name="slug" type="text" id="ucpt-slug" class="regular-text" value="<?php echo esc_attr($config['slug']); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="ucpt-menu-icon">Dashicon</label></th>
						<td><input name="menu_icon" type="text" id="ucpt-menu-icon" class="regular-text" value="<?php echo esc_attr($config['menu_icon']); ?>"></td>
					</tr>

					<tr>
						<th scope="row">Настройки</th>
						<td>
							<label><input name="public" type="checkbox" value="1" <?php checked(! empty($config['public'])); ?>> Публичный</label><br>
							<label><input name="has_archive" type="checkbox" value="1" <?php checked(! empty($config['has_archive'])); ?>> Архив</label><br>
							<label><input name="show_in_rest" type="checkbox" value="1" <?php checked(! empty($config['show_in_rest'])); ?>> REST API</label>
						</td>
					</tr>

					<tr>
						<th scope="row">Supports</th>
						<td>
							<?php foreach ($allowed_supports as $support) : ?>
								<label style="display:block; margin-bottom: 6px;">
									<input
										name="supports[]"
										type="checkbox"
										value="<?php echo esc_attr($support); ?>"
										<?php checked(in_array($support, $config['supports'], true)); ?>
									>
									<?php echo esc_html($support); ?>
								</label>
							<?php endforeach; ?>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button('Сохранить'); ?>
		</form>
	</div>
	<?php
}