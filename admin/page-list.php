<?php

if (! defined('ABSPATH')) {
	exit;
}

function ucpt_render_list_page(): void {
	if (! current_user_can('manage_options')) {
		wp_die('Недостаточно прав.');
	}

	$post_types = ucpt_get_post_types();
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Типы записей</h1>
		<a href="<?php echo esc_url(admin_url('admin.php?page=ucpt-add')); ?>" class="page-title-action">Добавить новый</a>

		<hr class="wp-header-end">

		<?php if (empty($post_types)) : ?>
			<p>Пока нет созданных CPT.</p>
		<?php else : ?>
			<table class="widefat striped">
				<thead>
					<tr>
						<th>Slug</th>
						<th>Единственное</th>
						<th>Множественное</th>
						<th>REST</th>
						<th>Архив</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($post_types as $slug => $config) : ?>
						<tr>
							<td>
								<strong>
									<a href="<?php echo esc_url(admin_url('admin.php?page=ucpt-add&action=edit&slug=' . rawurlencode($slug))); ?>">
										<?php echo esc_html($slug); ?>
									</a>
								</strong>
							</td>
							<td><?php echo esc_html($config['singular'] ?? ''); ?></td>
							<td><?php echo esc_html($config['plural'] ?? ''); ?></td>
							<td><?php echo ! empty($config['show_in_rest']) ? 'Да' : 'Нет'; ?></td>
							<td><?php echo ! empty($config['has_archive']) ? 'Да' : 'Нет'; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<?php
}