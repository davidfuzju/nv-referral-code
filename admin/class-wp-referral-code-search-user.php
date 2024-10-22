<?php

/**
 * Edit user screen class.
 *
 * @access public
 */
final class Shalior_Search_User
{

	private static $instance;

	/**
	 * Sets up needed actions/filters for the admin to initialize.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct()
	{

		if (! is_admin()) {
			return;
		}
		// Only run our customization on the 'user-edit.php' page in the admin.
		add_action('load-user-edit.php', array($this, 'load_user_edit'));
		add_action('wp_ajax_wp_referral_code_search_user_select2', array($this, 'ajax_search_user'));
	}

	/**
	 * Adds actions/filters on load.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function load_user_edit()
	{
		wp_enqueue_script('select2', WP_REFERRAL_CODE_URI . 'admin/js/select2.full.min.js', array('jquery'), WP_REFERRAL_CODE_VERSION, true);
		wp_enqueue_style('select2', WP_REFERRAL_CODE_URI . 'admin/css/select2.min.css', array(), WP_REFERRAL_CODE_VERSION, true);
	}

	public function ajax_search_user()
	{
		// verify ajax nonce.
		check_ajax_referer('wp_referral_code_search_user_select2', 'nonce');

		$data     = wp_unslash($_REQUEST);
		$page     = isset($data['page']) ? sanitize_text_field($data['page']) : 1;
		$per_page = 10;
		$user_id  = isset($data['user_id']) ? sanitize_text_field($data['user_id']) : 0;

		$excludes = (new WP_Refer_Code($user_id))->get_invited_users_id();

		$search = isset($data['term']) ? '*' . sanitize_text_field($data['term']) . '*' : '';

		$users_query = new WP_User_Query(
			array(
				'exclude'        => $excludes,
				'search'         => $search,
				'search_columns' => array('user_login', 'user_url', 'user_email', 'user_nicename', 'display_name'),
				'orderby'        => 'user_registered',
				'order'          => 'DESC',
				'fields'         => array('ID', 'user_login', 'user_email', 'display_name'),
				// pagination.
				'paged'          => $page,
				'number'         => $per_page,
				'count_total'    => true,
			)
		);
		$users = $users_query->get_results();
		$users = array_map(
			function ($user) {
				$text = '(' . $user->ID . ') ' . $user->display_name . ' (' . $user->user_login . ')';

				return array(
					'id'   => $user->ID,
					'text' => esc_html(apply_filters('wp_referral_code_search_user_select2_text', $text, $user)),
				);
			},
			$users
		);

		$total_count = $users_query->get_total();

		wp_send_json(
			array(
				'total'      => $total_count,
				'results'    => $users,
				'pagination' => array(
					'more' => (bool) ($total_count > ($page * $per_page)),
				),
			)
		);
	}

	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  1.0.0
	 * @access public
	 */
	public static function get_instance()
	{

		if (! self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Shalior_Search_User::get_instance();
