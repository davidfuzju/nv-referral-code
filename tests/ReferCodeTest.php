<?php

class ReferCodeTest extends WP_UnitTestCase
{

	public function set_up()
	{
		parent::set_up();
	}

	public function test_user_factory()
	{
		$user = self::factory()->user->create_and_get();

		$this->assertTrue($user instanceof WP_User);
	}

	public function test_it_can_create_a_refer_code_for_user()
	{
		remove_action('user_register', 'wp_referral_code_handle_new_registration', 20);
		$user = self::factory()->user->create_and_get();

		assertEmpty($user->get('wrc_ref_code'));

		$ref_code_instance = new WP_Refer_Code($user->ID);
		$ref_code          = $ref_code_instance->get_ref_code();

		$this->assertNotEmpty($ref_code);
	}
}
