<?php

/**
 * Set a login token when a user successfully logs in.
 *
 * @param string $user_login The username of the user logging in.
 * @param WP_User $user The WP_User object of the logged-in user.
 */
add_action('wp_login', 'set_login_token', 10, 2);
function set_login_token($user_login, $user)
{
	// Add a 'login_token' user meta to track user login state
	update_user_meta($user->ID, 'login_token', true);
}

/**
 * Validate and handle referral code logic.
 * Enqueues scripts and styles for displaying a referral code prompt.
 */
add_action('wp_enqueue_scripts', 'nv_referral_code_validation');
function nv_referral_code_validation()
{
	// Ensure the user is logged in
	if (!is_user_logged_in()) {
		return;
	}

	$user_id = get_current_user_id();

	// Check if the user has a 'login_token'
	if (!get_user_meta($user_id, 'login_token', true)) {
		return;
	}

	// Retrieve the user's referral code
	$user_referrer_code = get_user_meta($user_id, 'wrc_referrer_code', true);

	if (empty($user_referrer_code)) {
		// If the referral code is missing (logic can be expanded)

		$user = wp_get_current_user();
		$is_required = false;

		if ($user && $user->exists()) {
			$roles = (array) $user->roles;

			if ((count($roles) === 1 && $roles[0] === 'mp') || (count($roles) === 1 && $roles[0] === 'customer')) {
				$is_required = true;
			}
		}

		// Enqueue the JavaScript file for referral code validation
		wp_enqueue_script(
			'referral-code-validation',
			plugin_dir_url(__FILE__) . 'js/wp-referral-code-referral-code-validation.js',
			['jquery'],
			WP_REFERRAL_CODE_VERSION,
			true
		);

		// Enqueue the CSS file for referral code styling
		wp_enqueue_style(
			'referral-code-validation-style',
			plugin_dir_url(__FILE__) . 'css/wp-referral-code-referral-code-validation.css',
			[],
			WP_REFERRAL_CODE_VERSION
		);

		// Localize script: Pass AJAX URL and translation strings to JavaScript
		wp_localize_script('referral-code-validation', 'ajaxurl', admin_url('admin-ajax.php'));
		wp_localize_script('referral-code-validation', 'translation', array(
			'warning_title' => __('Welcome to NuVous!', 'nv-referral-code'),
			'warning_description' => __("If you have a referral code, please enter it in the field below and press enter. If you don't have one, feel free to skip this step", 'nv-referral-code'),
			'commit_button_title' => __('Enter', 'nv-referral-code'),
			'skip_button_title' => __('Skip', 'nv-referral-code'),
			'cancel_button_title' => __('Cancel', 'nv-referral-code'),
			'input_placeholder' => __('Referrer Membership No.', 'nv-referral-code'),
			'error' => __('An error occurred: Invalid action specified.', 'nv-referral-code'),
		));
		wp_localize_script('referral-code-validation', 'meta_data', array(
			'is_required' => $is_required,
		));
	}

	// Clear the 'login_token' after processing
	delete_user_meta($user_id, 'login_token');
}

add_action('wp_ajax_logout', 'nv_referral_code_validation_logout');
add_action('wp_ajax_nopriv_logout', 'nv_referral_code_validation_logout');

function nv_referral_code_validation_logout()
{
	wp_logout();
	wp_send_json_success(array(
		'message' => 'User logged out successfully.'
	));
}
