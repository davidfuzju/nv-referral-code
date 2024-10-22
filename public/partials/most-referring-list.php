<?php if (! empty($results)) : ?>
	<table class="wrc-most-referring-table">
		<thead class="wrc-most-referring-thead">
			<tr>
				<td><?php esc_html_e('User', 'wp-referral-code'); ?></td>
				<td><?php esc_html_e('Count', 'wp-referral-code'); ?></td>
			</tr>
		</thead>
		<?php foreach ($results as $result) : ?>
			<tbody class="wrc-most-referring-tbody">
				<tr>
					<?php $user = get_user_by('id', $result['id']); ?>
					<td><?php echo esc_html(apply_filters('wp_referral_code_most_referring_user_text', $user->user_login, $result['id'])); ?></td>
					<td><?php echo esc_html($result['counted']); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
	</table>
<?php else : ?>
	<p class="wrc-empty-most-referring-message">
		<?php echo esc_html(apply_filters('wp_referral_code_empty_most_referring_message', __('No Referrals yet! be the first one.', 'wp-referral-code'))); ?>
	</p>
<?php endif; ?>