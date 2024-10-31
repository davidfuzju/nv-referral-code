<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/includes
 * @author     Shalior <contact@shalior.ir>
 */
class WP_Referral_Code
{

	private static $instance;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $wp_referral_code The string used to uniquely identify this plugin.
	 */
	protected $wp_referral_code;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The Options of NV Referral Code
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array
	 */
	public $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('WP_REFERRAL_CODE_VERSION')) {
			$this->version = WP_REFERRAL_CODE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->wp_referral_code = 'nv-referral-code';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		add_action('init', array($this, 'init'), 1);

		$this->options = get_option(
			'wp_referral_code_options',
			array(
				'register_url'    => wp_registration_url(),
				'expiration_time' => 10,
			)
		);
	}


	/**
	 * Returns an instance of the class
	 *
	 * @return WP_Referral_Code
	 */
	public static function get_instance()
	{

		if (! self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/helpers/bootstrap.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wp-referral-code-admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wp-referral-code-public.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-refer-code.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wp-referral-code-registration.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wp-referral-code-manual-setting-referrer.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new WP_Referral_Code_Admin($this->get_wp_referral_code(), $this->get_version());
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_wp_referral_code()
	{
		return $this->wp_referral_code;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 * d
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new WP_Referral_Code_Public($this->get_wp_referral_code(), $this->get_version());
	}


	/**
	 * Handles initiation of plugin
	 * Sets cookies if request has a ref code
	 *
	 * @return void
	 */
	public function init()
	{
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if (! isset($_GET[wrc_get_ref_code_query()])) {
			return;
		}

		// expiration time
		$expires    = isset($this->options['expiration_time']) ? ($this->options['expiration_time'] * HOUR_IN_SECONDS) : (10 * HOUR_IN_SECONDS);

		// set refer_code in Cookies
		$name       = 'refer_code';
		$refer_code = sanitize_text_field(wp_unslash($_GET[wrc_get_ref_code_query()]));
		wrc_set_cookie($name, $refer_code, time() + $expires);

		// set refer_url in Cookies
		$name2      = 'refer_url';
		$refer_url  = wrc_get_current_url();
		wrc_set_cookie($name2, $refer_url, time() + $expires);
		// phpcs:enable
	}
}
