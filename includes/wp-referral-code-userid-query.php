<?php
// Register the AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_get_user_id_by_username', 'nv_get_user_id_by_username');

// Handle the AJAX request
function nv_get_user_id_by_username()
{
	// Check if the username is set
	if (isset($_POST['username'])) {
		$username = sanitize_text_field($_POST['username']);
		$user = get_user_by('login', $username);

		if ($user) {
			wp_send_json_success(['userid' => $user->ID]);
		} else {
			wp_send_json_error(['message' => 'User not found']);
		}
	} else {
		wp_send_json_error(['message' => 'No username provided']);
	}
}
