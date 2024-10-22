<?php

/**
 * Handles custom functionality on the edit user screen,
 *
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/Admin
 * @author Shalior<contact@shalior.ir>
 */

/**
 * Edit user screen class.
 *
 * @access public
 */
final class Shalior_Grs_User_Edit
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
		add_action('wp_ajax_wp_referral_code_delete_user_relation', array($this, 'ajax_delete_user_relation'));
		add_action('wp_ajax_wp_referral_code_add_user_relation', array($this, 'ajax_add_user_relation'));
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
		add_action('show_user_profile', array($this, 'profile_fields'), 1);
		add_action('edit_user_profile', array($this, 'profile_fields'), 1);

		add_action('profile_update', array($this, 'update_ref_code'), 10, 1);
		add_action('user_profile_update_errors', array($this, 'validate_ref_code'), 3, 10);

		wp_enqueue_script('wp-referral-code-main', WP_REFERRAL_CODE_URI . 'admin/js/main.min.js', array(), WP_REFERRAL_CODE_VERSION, false);
		wp_enqueue_script('select2', WP_REFERRAL_CODE_URI . 'admin/js/select2.full.min.js', array('jquery'), WP_REFERRAL_CODE_VERSION, true);
		wp_enqueue_style('select2', WP_REFERRAL_CODE_URI . 'admin/css/select2.min.css', array(), WP_REFERRAL_CODE_VERSION, 'all');
		wp_localize_script(
			'wp-referral-code-main',
			'WPReferralCode',
			array(
				'alert'          => array(
					'title'       => __('Are you sure?', 'nv-referral-code'),
					'text'        => __('You won\'t be able to revert this!', 'nv-referral-code'),
					'confirmText' => __('Yes, delete it!', 'nv-referral-code'),
					'cancelText'  => __('Cancel', 'nv-referral-code'),
					'error'       => __('Something went wrong!', 'nv-referral-code'),
				),
				'confirmedAlert' => array(
					'title' => __('Deleted!', 'nv-referral-code'),
					'text'  => __('The relation has been deleted.', 'nv-referral-code'),
				),
				'nonce'          => wp_create_nonce('wp_referral_code_delete_user_relation_nonce'),
				'nonceAdd'       => wp_create_nonce('wp_referral_code_add_user_relation_nonce'),
			)
		);
	}

	public function update_ref_code($user_id)
	{

		if (! current_user_can('promote_users') || ! current_user_can('edit_user', $user_id)) {
			return;
		}

		if (! isset($_POST['wrc_update_ref_code_nonce']) || ! wp_verify_nonce($_POST['wrc_update_ref_code_nonce'], 'update_ref_code')) {
			return;
		}

		$new_ref_code = sanitize_text_field(wp_unslash($_POST['wrc_new_ref_code']));

		if (empty($new_ref_code)) {
			return;
		}

		(new WP_Refer_Code($user_id))->update_ref_code($new_ref_code);
	}

	/**
	 * Validate submitted ref code
	 *
	 * @param WP_Error $errors
	 * @param boolean  $update
	 * @param WP_User  $user
	 *
	 * @return void
	 */
	public function validate_ref_code($errors, $update, $user)
	{
		if ($update) {
			if (! isset($_POST['wrc_update_ref_code_nonce']) || ! wp_verify_nonce($_POST['wrc_update_ref_code_nonce'], 'update_ref_code')) {
				return;
			}

			$new_ref_code = sanitize_text_field(wp_unslash($_POST['wrc_new_ref_code']));

			if (empty($new_ref_code)) {
				return;
			}

			$ref_code_owner_id = WP_Refer_Code::get_user_id_by_ref_code($new_ref_code);
			// ref code is not already in use.
			if (false === $ref_code_owner_id) {
				return;
			}

			if ((int) $ref_code_owner_id !== $user->ID) {
				$errors->add('unique-ref-code', __('Submitted refer code is already in use', 'nv-referral-code'));
			}
		}
	}

	/**
	 * Handles ajax request to delete referral relation
	 *
	 * @return void
	 */
	public function ajax_delete_user_relation()
	{
		list($to_delete_user_id, $referrer_id) = $this->ajax_relation_request_validation('wp_referral_code_delete_user_relation_nonce');

		wp_referral_code_delete_relation_2($to_delete_user_id, $referrer_id);
		wp_send_json_success(
			array(
				'done',
				$referrer_id,
				$to_delete_user_id,
			)
		);
	}

	public function ajax_add_user_relation()
	{
		list($to_add_user_id, $referrer_id) = $this->ajax_relation_request_validation('wp_referral_code_add_user_relation_nonce');

		if ($to_add_user_id === $referrer_id) {
			wp_send_json_error(
				array(
					'error' => __('You can not add yourself as a referrer', 'nv-referral-code'),
				),
				200
			);
			wp_die();
		}

		$already_has_referrer = ! empty(get_user_meta($to_add_user_id, 'wrc_referrer_id', true));

		if ($already_has_referrer) {
			wp_send_json_error(
				array(
					'error' => __('User already has a referrer, You might want to first delete the relation', 'nv-referral-code'),
				),
				200
			);
			wp_die();
		}

		// user referred by 
		$referer_code = (new WP_Refer_Code($referrer_id))->get_ref_code();
		$referrer_url = null;

		// set referrer as inviter of new user.
		update_user_meta($to_add_user_id, 'wrc_referrer_id', $referrer_id);
		update_user_meta($to_add_user_id, 'wrc_referrer_code', $referer_code);
		update_user_meta($to_add_user_id, 'wrc_referrer_code_2', $referer_code);
		update_user_meta($to_add_user_id, 'wrc_referrer_url', $referrer_url);

		// adding new user to referrer invited list.
		wp_referral_code_add_user_to_referrer_invite_list_2($to_add_user_id, $referrer_id, $referrer_url);
		do_action('wp_referral_code_manual_user_added', $to_add_user_id, $referrer_id);

		wp_send_json_success(
			array(
				'done',
				$referrer_id,
				$to_add_user_id,
			)
		);
	}

	/**
	 * Adds custom profile fields.
	 *
	 * @param WP_User $user
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function profile_fields($user)
	{

		if (! current_user_can('promote_users') || ! current_user_can('edit_user', $user->ID)) {
			return;
		}
		$user_id  = $user->ID;
		$ref_code = new WP_Refer_Code($user_id);

		ob_start();
		include_once WP_REFERRAL_CODE_PATH . '/admin/partials/wp-referral-code-admin-invited-users.php';
		include_once WP_REFERRAL_CODE_PATH . '/admin/partials/wp-referral-code-admin-update-ref-code.php';
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ob_get_clean();
		// phpcs:enable
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


	private function ajax_relation_request_validation($nonce_action)
	{
		check_ajax_referer($nonce_action, 'nonce', true);

		$data        = wp_unslash($_POST);
		$user_id     = sanitize_text_field($data['user_id']);
		$referrer_id = sanitize_text_field($data['referrer_id']);

		if (! is_numeric($user_id) || ! (new WP_User($user_id)) || ! (new WP_User($referrer_id))) {
			wp_send_json_error(
				array(
					'Invalid data',
					$referrer_id,
					$user_id,
				),
				422
			);
		}

		return array($user_id, $referrer_id);
	}
}

Shalior_Grs_User_Edit::get_instance();
