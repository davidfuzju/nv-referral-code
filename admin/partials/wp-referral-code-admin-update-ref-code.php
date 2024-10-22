<table class="form-table">
	<?php wp_nonce_field('update_ref_code', 'wrc_update_ref_code_nonce'); ?>
	<tr>
		<th><?php esc_html_e('Update refer code', 'wp-referral-code'); ?></th>

		<td>
			<label>
				<input type="text" name="wrc_new_ref_code"
					placeholder="<?php esc_attr_e('Refer code', 'wp-referral-code'); ?>"
					value="<?php echo esc_attr($ref_code->get_ref_code()); ?>" />
				<br>
				<small><?php esc_html_e('Custom refer code', 'wp-referral-code'); ?></small>
			</label>
		</td>
	</tr>

</table>