<?php if (! empty($invited_users)) : ?>
	<ul class="wrc-invited-users">
		<?php foreach ($invited_users as $user_id) : ?>
			<?php
			$user = get_user_by('id', $user_id);
			if (false !== $user) :
			?>
				<li class="wrc-invited-user-item" id="wrc-invited-user-<?php echo esc_attr($user_id); ?>">
					<?php echo esc_html(apply_filters('wp_referral_code_invited_user_text', $user->user_login, $user_id)); ?>
				</li>
			<?php endif; ?>

		<?php endforeach; ?>
	</ul>
<?php else : ?>
	<p class="wrc-empty-invite-list-message">
		<?php echo esc_html(apply_filters('wp_referral_code_empty_list_message', __('You have invited no one yet!', 'nv-referral-code'), get_current_user_id())); ?>
	</p>
<?php endif; ?>