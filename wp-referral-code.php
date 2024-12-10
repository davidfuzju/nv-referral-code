<?php

/**
 * NuVous referral code
 *
 * @wordpress-plugin
 * Plugin Name:       NV Referral Code
 * Plugin URI:        https://github.com/davidfuzju/nv-referral-code
 * Description:       This plugin brings referral marketing to your WordPress website. It's dead simple, fast, customizable, and it's all free!
 * Version:           1.5.5
 * Author:            David FU <david.fu.zju@gmail.com>
 * Author URI:        https://github.com/davidfuzju
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nv-referral-code
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

// holds the plugin path.
define('WP_REFERRAL_CODE_PATH', plugin_dir_path(__FILE__));
define('WP_REFERRAL_CODE_URI', plugin_dir_url(__FILE__));
define('WP_REFERRAL_CODE_VERSION', '1.5.5');

/**
 * The code that runs during plugin activation.
 */
function activate_wp_referral_code()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-referral-code-activator.php';
	WP_Referral_Code_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_referral_code()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-referral-code-deactivator.php';
	WP_Referral_Code_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_referral_code');
register_deactivation_hook(__FILE__, 'deactivate_wp_referral_code');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-referral-code.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wp_referral_code()
{

	WP_Referral_Code::get_instance();
}

// gets necessary options.
$wp_referral_code_options = get_option(
	'wp_referral_code_options',
	array(
		'register_url'    => wp_registration_url(),
		'expiration_time' => 10,
	)
);

// runs the plugin.
run_wp_referral_code();

do_action('wp_referral_code_loaded');
