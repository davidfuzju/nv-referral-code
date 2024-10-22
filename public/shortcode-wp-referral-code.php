<?php
if (! defined('ABSPATH')) {
	die();
}
/**
 * Shortcode for showing referral system related data.
 *
 * @return string
 */
function wp_referral_code_user_param_shortcodes_init()
{
	function wp_referral_code_user_param($atts = array())
	{
		global $wpdb;
		// nothing for not logged in users.
		if (! is_user_logged_in()) {
			return '';
		}
		// analyze shortcode parameters.
		$para     = $atts['var'];
		$user_id  = get_current_user_id();
		$ref_code = new WP_Refer_Code($user_id);

		switch ($para) {
			case 'member_id': // [nv-referral-code var="member_id"]
				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/member-id.php';

				return ob_get_clean();
			case 'ref_code': // [nv-referral-code var="ref_code"]
				return $ref_code->get_ref_code();
			case 'ref_link': // [nv-referral-code var="ref_link"]
				return $ref_code->get_ref_link();
			case 'referrer_code': // [nv-referral-code var="referrer_code"]
				$referrer_code = get_user_meta($user_id, 'wrc_referrer_code_2', true);

				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/referrer-code.php';

				return ob_get_clean();
			case 'invited_count': // [nv-referral-code var="invited_count"]
				return empty($ref_code->get_invited_users_id()) ? '0' : count($ref_code->get_invited_users_id());
			case 'most_referring_users': // [nv-referral-code var="most_referring_users"]
				$limit = apply_filters('wp_referral_code_invited_limit_most_referring', 10);

				$results = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT COUNT(meta_value) as counted, meta_value as id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value IS NOT NULL GROUP BY meta_value ORDER BY counted DESC LIMIT %d",
						'wrc_referrer_id',
						intval($limit)
					),
					ARRAY_A
				);

				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/most-referring-list.php';

				return ob_get_clean();
			case 'invited_list': // [nv-referral-code var="invited_list"]
				$invited_users = $ref_code->get_invited_users_id();
				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/invited-list.php';

				return ob_get_clean();
			case 'copy_ref_link': // [nv-referral-code var="copy_ref_link"]
				if (! wp_script_is('jquery', 'enqueued')) {
					// Enqueue jquery only if not enqueued before.
					wp_enqueue_script('jquery');
				}
				if (! wp_script_is('clipboard', 'enqueued')) {
					// Enqueue clipboard only if not enqueued before.
					wp_enqueue_script('clipboard');
				}
				wp_enqueue_script('wrc-copy-ref-link', plugin_dir_url(__FILE__) . 'js/wp-referral-code-public.js', array(), WP_REFERRAL_CODE_VERSION, true);
				wp_enqueue_style('wrc-copy-ref-link-styles', plugin_dir_url(__FILE__) . 'css/wp-referral-code-copy-link.css', array(), WP_REFERRAL_CODE_VERSION);
				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/copy-ref-link-box.php';

				return ob_get_clean();
			case 'copy_ref_link_per_page': // [nv-referral-code var="copy_ref_link_per_page"]
				if (! wp_script_is('jquery', 'enqueued')) {
					// Enqueue jquery only if not enqueued before.
					wp_enqueue_script('jquery');
				}
				if (! wp_script_is('clipboard', 'enqueued')) {
					// Enqueue clipboard only if not enqueued before.
					wp_enqueue_script('clipboard');
				}
				wp_enqueue_script('wrc-copy-ref-link', plugin_dir_url(__FILE__) . 'js/wp-referral-code-public.js', array(), WP_REFERRAL_CODE_VERSION, true);
				wp_enqueue_style('wrc-copy-ref-link-styles', plugin_dir_url(__FILE__) . 'css/wp-referral-code-copy-link-per-page.css', array(), WP_REFERRAL_CODE_VERSION);
				ob_start();
				require WP_REFERRAL_CODE_PATH . 'public/partials/copy-ref-link-box-per-page.php';

				return ob_get_clean();
			case 'manual-setting-referrer': // [nv-referral-code var="manual-setting-referrer"]
				if (! wp_script_is('jquery', 'enqueued')) {
					// Enqueue jquery only if not enqueued before.
					wp_enqueue_script('jquery');
				}

				wp_enqueue_script('manual-setting-referrer', plugin_dir_url(__FILE__) . 'js/wp-referral-code-manual-setting-referrer.js', array(), WP_REFERRAL_CODE_VERSION, true);
				wp_enqueue_style('manual-setting-referrer-styles', plugin_dir_url(__FILE__) . 'css/wp-referral-code-manual-setting-referrer.css', array(), WP_REFERRAL_CODE_VERSION);
				wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));
				return;
		}

		// [nv-referral-code var="valid_invited_count"]
		return '';
	}

	add_shortcode('nv-referral-code', 'wp_referral_code_user_param');
}

add_action('init', 'wp_referral_code_user_param_shortcodes_init');
