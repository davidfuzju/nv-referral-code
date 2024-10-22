<?php

/**
 * Handles options of the plugin
 *
 * @access public
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/admin
 * @author     Shalior <contact@shalior.ir>
 */
final class WP_Referral_Code_Settings
{

	private static $instance;
	public $page_slug = 'nv-referral-code';
	private $options;

	/**
	 * Sets up needed actions/filters for the admin options to initialize.
	 *
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function __construct()
	{

		if (! is_admin()) {
			return;
		}

		global $wp_referral_code_options;
		$this->load_settings();
		$this->options = $wp_referral_code_options;
	}

	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  2.0.0
	 * @access public
	 */
	public static function get_instance()
	{

		if (! self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function load_settings()
	{
		add_action('admin_init', array($this, 'settings_init'));
		add_action('admin_menu', array($this, 'add_options_page'));
	}

	public function field_register_url($args)
	{
		$option = $this->options;
		// include html.
		include_once WP_REFERRAL_CODE_PATH . 'admin/partials/options/filed-register-url.php';
	}

	public function field_expiration_time($args)
	{
		$option = $this->options;
		// include html.
		include_once WP_REFERRAL_CODE_PATH . 'admin/partials/options/field-expiration-time.php';
	}

	public function settings_init()
	{
		// register a new setting.
		$args = array(
			'description'       => '',
			'sanitize_callback' => array($this, 'sanitize_callback'),
			'show_in_rest'      => false,
		);
		register_setting($this->page_slug, 'nv_referral_code_options', $args);
		// register a new section.
		add_settings_section(
			'wp_referral_code_section_1',
			__('Settings', 'nv-referral-code'),
			function () {
				// nothing!
			},
			$this->page_slug
		);

		// register url length.
		add_settings_field(
			'wp_referral_code_register_url', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback.
			__('Register url', 'nv-referral-code'),
			array($this, 'field_register_url'),
			$this->page_slug,
			'wp_referral_code_section_1',
			array(
				'label_for' => 'register_url',
				'class'     => 'wrc_row',
			)
		);

		add_settings_field(
			'wp_referral_code_expiration_time', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback.
			__('Expiration time(hours)', 'nv-referral-code'),
			array($this, 'field_expiration_time'),
			$this->page_slug,
			'wp_referral_code_section_1',
			array(
				'label_for' => 'expiration_time',
				'class'     => 'wrc_row',
			)
		);
	}

	public function sanitize_callback($data)
	{

		$have_err = false;

		// check if the data has changed.
		if ($this->options == $data) {
			return $data;
		}
		// validate register_url.
		if (wrc_is_valid_url($data['register_url']) === false) {
			add_settings_error($this->page_slug, $this->page_slug, __('Register Url is not valid', 'nv-referral-code'));
			$have_err = true;
		}

		if (empty($data['expiration_time']) || ! is_numeric($data['expiration_time'])) {
			add_settings_error($this->page_slug, $this->page_slug, __('Expiration time is not valid', 'nv-referral-code'));
			$have_err = true;
		}

		if (! $have_err) {
			// sanitize.
			$data['register_url']    = esc_url_raw($data['register_url']);
			$data['expiration_time'] = sanitize_text_field($data['expiration_time']);

			return $data;
		}

		return $this->options;
	}

	public function add_options_page()
	{
		add_options_page(
			'NV Referral Code Options',
			'NV Referral Code',
			'manage_options',
			$this->page_slug,
			array($this, 'get_options_page_html')
		);
	}

	public function get_options_page_html()
	{
		if (! current_user_can('manage_options')) {
			return;
		}

		require_once WP_REFERRAL_CODE_PATH . 'admin/partials/options/wp-referral-code-admin-setting-view.php';
	}
}

WP_Referral_Code_Settings::get_instance();
