<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://shalior.ir/wp-referral-code
 * @since      1.0.0
 *
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two
 *
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/admin
 * @author     Shalior <contact@shalior.ir>
 */
class WP_Referral_Code_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $wp_referral_code The ID of this plugin.
	 */
	private $wp_referral_code;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $wp_referral_code The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct($wp_referral_code, $version)
	{

		$this->wp_referral_code = $wp_referral_code;
		$this->version          = $version;
		$this->load_dependencies();
	}

	/**
	 * Loads admin related dependencies
	 */
	public function load_dependencies()
	{
		require_once WP_REFERRAL_CODE_PATH . '/admin/class-wp-referral-code-user-edit.php';
		require_once WP_REFERRAL_CODE_PATH . '/admin/class-wp-referral-code-options.php';
		require_once WP_REFERRAL_CODE_PATH . '/admin/class-wp-referral-code-users-columns.php';
		require_once WP_REFERRAL_CODE_PATH . '/admin/class-wp-referral-code-search-user.php';

		if (is_admin()) {
			add_filter(
				'plugin_action_links',
				function ($links, $file) {
					if (plugin_basename('wp-referral-code/wp-referral-code.php') === $file) {
						$links[] = '<a href="' . admin_url('options-general.php?page=wp-referral-code') . '">' . __('Settings', 'wp-referral-code') . '</a>';
						$links[] = '<a href="https://shalior.ir/wp-referral-code">' . __('Documentation & Support', 'wp-referral-code') . '</a>';
					}

					return $links;
				},
				10,
				2
			);
		}
	}
}
