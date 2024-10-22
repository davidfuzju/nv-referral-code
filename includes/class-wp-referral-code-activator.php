<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Referral_Code
 * @subpackage WP_Referral_Code/includes
 * @author     Shalior<contact@shalior.ir>
 */
class WP_Referral_Code_Activator
{

	/**
	 * Sets a ref code meta for all users if they don't have any
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		wrc_set_ref_code_all_users(false, 6);
	}
}
