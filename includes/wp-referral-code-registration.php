<?php
add_action('user_register', 'wp_referral_code_handle_new_registration', 20, 1);
/**
 * Save referral data of newly registered user
 *
 * @param int $user_id User's id.
 *
 * @return void
 */
function wp_referral_code_handle_new_registration($user_id)
{
	$ref_code = '';
	$ref_url = '';
	if (isset($_COOKIE['refer_code'])) {
		$ref_code = sanitize_text_field(wp_unslash($_COOKIE['refer_code']));
		$ref_url = sanitize_text_field($_COOKIE['refer_url']);
	}

	nv_referral_code_handle_new_registration(
		$user_id,
		$ref_code,
		$ref_url,
		function ($user_id, $ref_code, $ref_url) {
			if (isset($_COOKIE['refer_code'])) {
				wrc_set_cookie('refer_code', 0, time() - HOUR_IN_SECONDS, '/', '', false, false);
				unset($_COOKIE['refer_code']);
			}
		}
	);

	// remove cookie.
	if (isset($_COOKIE['refer_code'])) {
		wrc_set_cookie('refer_code', 0, time() - HOUR_IN_SECONDS, '/', '', false, false);
		unset($_COOKIE['refer_code']);
	}

	if (isset($_COOKIE['refer_url'])) {
		wrc_set_cookie('refer_url', 0, time() - HOUR_IN_SECONDS, '/', '', false, false);
		unset($_COOKIE['refer_url']);
	}
}


function nv_referral_code_handle_new_registration($user_id, $ref_code, $ref_url = '', callable $on_invalid_ref = null)
{
	$ref_code = apply_filters('wp_referral_code_new_user_ref_code', $ref_code, $ref_url, $user_id);

	$referee_user_id = $user_id;

	// make ref code for new users.
	$new_user_ref_code = new WP_Refer_Code($referee_user_id);

	// out if no ref code.
	if (empty($ref_code)) {
		return;
	}

	$referrer_user_id = WP_Refer_Code::get_user_id_by_ref_code($ref_code);

	// if quite if the ref_code is invalid.
	if (false === $referrer_user_id) {
		if (is_callable($on_invalid_ref)) {
			$on_invalid_ref($user_id, $ref_code, $ref_url);
		}

		return;
	}

	/**
	 * Fires before refer code related information are submitted on database
	 * this action won't run if ref code doesn't exist
	 * passed parameters:
	 * $referee_user_id: id of newly registered user
	 * $referrer_user_id: id of the user who referred newly registered user (the guy who should be rewarded :) )
	 * $ref_code: referral code of referrer
	 * $new_user_refer_code refer_code of newly registered user
	 * $ref_url: referral url of referrer
	 */
	do_action('wp_referral_code_before_refer_submitted', $referee_user_id, $referrer_user_id, $ref_code, $new_user_ref_code, $ref_url);

	if (! apply_filters('wp_referral_code_validate_submission', true, $referee_user_id, $referrer_user_id, $ref_code, $new_user_ref_code, $ref_url)) {
		return;
	}

	// set referrer as inviter of new user.
	update_user_meta($referee_user_id, 'wrc_referrer_id', $referrer_user_id);
	update_user_meta($referee_user_id, 'wrc_referrer_code', $ref_code);
	update_user_meta($referee_user_id, 'wrc_referrer_code_2', $ref_code);
	update_user_meta($referee_user_id, 'wrc_referrer_url', $ref_url);

	// adding new user to referrer invited list.
	wp_referral_code_add_user_to_referrer_invite_list_2($referee_user_id, $referrer_user_id, $ref_url);
	/**
	 * Fires after refer code related information are submitted on database
	 * this action won't run if ref code doesn't exist
	 * passed parameters:
	 * $referee_user_id: id of newly registered user
	 * $referrer_user_id: id of the user who referred newly registered user (the guy who should be rewarded :) )
	 * $ref_code: referral code of referrer
	 * $new_user_refer_code refer_code of newly registered user
	 * $ref_url: referral url of referrer
	 */
	do_action('wp_referral_code_after_refer_submitted', $referee_user_id, $referrer_user_id, $ref_code, $new_user_ref_code, $ref_url);
}
