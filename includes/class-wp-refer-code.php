<?php

/**
 * This class is responsible for assigning ref code to user and
 * get refer code related data
 */
class WP_Refer_Code
{

	/**
	 * User's id
	 *
	 * @var int
	 */
	private $user_id;

	/**
	 * Holds user's refer code
	 *
	 * @var string
	 */
	private $ref_code;


	/**
	 * WP_Refer_Code constructor.
	 *
	 * @param int $user_id user id.
	 *
	 * @throws RuntimeException When invalid user id giver.
	 */
	public function __construct($user_id)
	{
		if (! get_user_by('id', $user_id)) {
			throw new RuntimeException("User with id $user_id does'nt exists");
		}

		$user_ref_code = get_user_meta($user_id, 'wrc_ref_code', true);
		if (empty($user_ref_code)) {
			$user_ref_code = Shalior_Referral_Code_Generator::get_instance()->get_ref_code();
			update_user_meta($user_id, 'wrc_ref_code', $user_ref_code);
		}
		$this->ref_code = $user_ref_code;
		$this->user_id  = $user_id;
	}

	/**
	 * Search users for a refer code.
	 *
	 * @param string $ref_code Refer code.
	 *
	 * @return int | false
	 */
	public static function get_user_id_by_ref_code($ref_code)
	{
		$user = get_users(
			array(
				'meta_key'   => 'wrc_ref_code',
				'meta_value' => $ref_code,
				'fields'     => array('ID'),
			)
		);

		return ! empty($user) ? $user[0]->ID : false;
	}

	/**
	 * Return refer code
	 *
	 * @return string
	 */
	public function get_ref_code()
	{
		return $this->ref_code;
	}

	/**
	 *
	 * Sets a ref code if user doesn't have already.
	 *
	 * @deprecated
	 * @param string $ref_code Refer code.
	 */
	public function set_ref_code($ref_code)
	{

		$this->ref_code = $ref_code;
		if (! metadata_exists('user', $this->user_id, 'wrc_ref_code')) {
			update_user_meta($this->user_id, 'wrc_ref_code', $ref_code);
		}
	}

	/**
	 * Updates user's refer code. Returns false on failure.
	 *
	 * @param string $ref_code New custom refer code.
	 *
	 * @return bool|int
	 */
	public function update_ref_code($ref_code)
	{
		if (metadata_exists('user', $this->user_id, 'wrc_ref_code')) {
			$this->ref_code = $ref_code;

			/// update history users' wrc_referrer_code
			$users = get_users(
				array(
					'meta_key'   => 'wrc_referrer_id',
					'meta_value' => $this->user_id,
					'fields'     => array('ID'),
				)
			);

			// Loop through each user and update their 'wrc_referrer_code_2' field
			foreach ($users as $user) {
				$user_id = $user->ID; // Get the user ID

				// Update the 'wrc_referrer_code_2' field for the user
				update_user_meta($user_id, 'wrc_referrer_code_2', $this->ref_code);
			}

			/// update current user's wrc_ref_code
			return update_user_meta($this->user_id, 'wrc_ref_code', $ref_code);
		}

		return false;
	}

	/**
	 * Return the id of user who referred this user, false if referred by no one
	 *
	 * @return int
	 */
	public function get_referrer_id()
	{
		return get_user_meta($this->user_id, 'wrc_referrer_id', true);
	}

	/**
	 * Returns user's specific refer link
	 *
	 * @return string
	 */
	public function get_ref_link()
	{
		global $wp_referral_code_options;
		$ref_link = add_query_arg(array(wrc_get_ref_code_query() => $this->ref_code), $wp_referral_code_options['register_url']);

		return esc_url($ref_link);
	}

	/**
	 * Returns array of user ids invited by this user
	 *
	 * @param null $user_id User id.
	 *
	 * @return array
	 */
	public function get_invited_users_id($user_id = null)
	{
		if (null === $user_id) {
			$user_id = $this->user_id;
		}
		$invited = get_user_meta($user_id, 'wrc_invited_users', true);

		return empty($invited) ? array() : $invited;
	}

	/**
	 * Returns array of users invited by this user
	 *
	 * @param null $user_id User id.
	 *
	 * @return array element is object { i => $user_id, j => $referrer_url }
	 */
	public function get_invited_users($user_id = null)
	{
		if (null === $user_id) {
			$user_id = $this->user_id;
		}
		$invited = get_user_meta($user_id, 'wrc_invited_users_2', true);

		return empty($invited) ? array() : $invited;
	}
}
