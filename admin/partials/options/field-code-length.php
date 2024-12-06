<!-- refer code length HTML -->
<input style="padding: .3em;" type="number"
	id="<?php echo esc_attr($args['label_for']); ?>"
	name="wp_referral_code_options[<?php echo esc_attr($args['label_for']); ?>]"
	value="<?php echo isset($option[$args['label_for']]) ? esc_html($option[$args['label_for']]) : 5; ?>">

<div style="
	background-color: #ef4444;
	color: #ffebeb;
	padding: .5rem 1rem;
	margin-top: .3rem;
	border-radius: 5px;
	font-size:.8rem;
	display:flex;
	align-items: center
">
	<!-- WordPress admin danger icon -->
	<i class="dashicons dashicons-warning" style="color:#ffebeb; margin-right: 5px"></i>

	<?php esc_html_e('Changing this option will reset refer code of all users', 'nv-referral-code'); ?>
</div>
<!-- refer code length HTML -->