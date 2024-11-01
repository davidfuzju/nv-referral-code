<?php
// Register the AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_manual_setting_referer', 'nv_process_referral_code_manual_setting_referrer');

function nv_process_referral_code_manual_setting_referrer()
{
	global $wpdb;

	// Ensure the referral code is provided
	if (!isset($_POST['referral_code']) || empty($_POST['referral_code'])) {
		wp_send_json_error(array(
			'message' => 'Referral code is required.',
			'error_code' => 'REFERRAL_CODE_MISSING'
		));
		wp_die();
	}

	// Get the referral code from the AJAX request
	$referral_code = sanitize_text_field($_POST['referral_code']);

	try {
		// Check if the referral code exists in the database (stored in usermeta as wrc_ref_code)
		$referring_user = $wpdb->get_row($wpdb->prepare(
			"SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'wrc_ref_code' AND meta_value = %s",
			$referral_code
		));

		// Handle if the referral code is not found
		if (!$referring_user) {
			wp_send_json_error(array(
				'message' => 'Invalid referral code. Please try again.',
				'error_code' => 'REFERRAL_NOT_FOUND'
			));
			wp_die();
		}

		// Check if the referral code belongs to the current user
		if ($referring_user->user_id == get_current_user_id()) {
			wp_send_json_error(array(
				'message' => 'You cannot use your own referral code.',
				'error_code' => 'SELF_REFERRAL_CODE'
			));
			wp_die();
		}

		// Get the current logged-in user
		$current_user_id = get_current_user_id();

		// Check if the current user is logged in
		if (!$current_user_id) {
			wp_send_json_error(array(
				'message' => 'You must be logged in to apply a referral code.',
				'error_code' => 'USER_NOT_LOGGED_IN'
			));
			wp_die();
		}

		// Check if the user has already been referred
		$existing_referrer = get_user_meta($current_user_id, 'wrc_referrer_id', true);
		if ($existing_referrer) {
			wp_send_json_error(array(
				'message' => 'You have already been referred by another user.',
				'error_code' => 'ALREADY_REFERRED'
			));
			wp_die();
		}

		// Update the current user with the referrer's information
		update_user_meta($current_user_id, 'wrc_referrer_id', $referring_user->user_id);
		update_user_meta($current_user_id, 'wrc_referrer_code', $referral_code);
		update_user_meta($current_user_id, 'wrc_referrer_code_2', $referral_code);
		update_user_meta($current_user_id, 'wrc_referrer_url', ''); // Set this to empty as per request

		// adding new user to referrer invited list.
		wp_referral_code_add_user_to_referrer_invite_list_2($current_user_id, $referring_user->user_id, null);

		// Get referrer's user information (e.g., display name)
		$referrer_info = get_userdata($referring_user->user_id);
		$referrer_name = $referrer_info->display_name;

		// Return success with additional data
		wp_send_json_success(array(
			'message' => 'Referral code successfully applied!',
			'referrer_name' => $referrer_name,
			'referral_code' => $referral_code,
			'thank_you_message' => 'Thank you for using the referral system!'
		));
	} catch (Exception $e) {
		wp_send_json_error(array(
			'message' => 'An error occurred while processing the referral code.',
			'error_code' => 'INTERNAL_ERROR',
			'error_details' => $e->getMessage()
		));
	}

	wp_die(); // Properly terminate the script after AJAX response
}
