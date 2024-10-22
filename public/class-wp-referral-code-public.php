<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://shalior.ir/wp-referral-code
 * @since      1.0.0
 *
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/public
 */
class WP_Referral_Code_Public
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
	 * @param string $wp_referral_code The name of the plugin.
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
	 * Loads plugin description
	 *
	 * @return void
	 */
	public function load_dependencies()
	{
		require_once WP_REFERRAL_CODE_PATH . '/public/shortcode-wp-referral-code.php';
	}
}
