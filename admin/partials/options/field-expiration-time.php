<!-- Refer code Expiration time -->

<label for="<?php echo esc_attr($args['label_for']); ?>"></label>
<input style="padding: .3em;"
	type="number"
	id="<?php echo esc_attr($args['label_for']); ?>"
	name="wp_referral_code_options[<?php echo esc_attr($args['label_for']); ?>]"
	value="<?php echo isset($option[$args['label_for']]) ? esc_html($option[$args['label_for']]) : 5; ?>">
<br>

<small><?php esc_html_e('referral codes are permanent, this option is about plugin\'s cookies expiration time, just skip this if you don\'t know what a browser cookie is', 'nv-referral-code'); ?></small>

<!-- Refer code Expiration time -->