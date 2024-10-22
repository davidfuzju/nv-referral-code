<?php

/**
 * Handles Creating ref codes
 */
class Shalior_Referral_Code_Generator
{
	private static $instance = null;

	/**
	 * Get an instance of class
	 *
	 * @return Shalior_Referral_Code_Generator|null
	 * @deprecated Use get_instance instead
	 */
	public static function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new Shalior_Referral_Code_Generator();
		}

		return self::$instance;
	}

	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new Shalior_Referral_Code_Generator();
		}

		return self::$instance;
	}

	/**
	 * Generates a duplication safe ref code
	 * 
	 * 1.	The referral ID must be unique, with no duplicates.
	 * 2.	The referral ID consists of 12 digits: YYMMXXXXXXXX.
	 * 3.	YY represents the last two digits of the year when the referral ID is generated, for example, in 2024, YY would be 24.
	 * 4.	MM represents the month when the referral ID is generated, for example, for August, MM would be 08.
	 * 5.	The YYMMXXXXXXXX part cannot contain more than four consecutive identical digits (except for 0), for example, 1111 or 2222.
	 * 6.	The YYMMXXXXXXXX part cannot contain more than four consecutive sequential digits (like 1234), for example, 0123, 4567, 9876, 3210.
	 *
	 * @param null|int $length
	 *
	 * @return string
	 */
	public function get_ref_code($length = null)
	{

		$ref_code    = $this->generate_ref_code();
		if ($this->is_unique($ref_code)) {
			return $ref_code;
		}

		$validated = false;
		do {
			$ref_code  = $this->generate_ref_code();
			$validated = $this->is_unique($ref_code);
			if ($validated) {
				return $ref_code;
			}
		} while (! $validated);

		return $ref_code;
	}

	/**
	 * Generate a random string for refer codes.
	 *
	 * @param int $length
	 *
	 * @return bool|string
	 */
	private function generate_ref_code()
	{
		$year = date("y"); // Get the last two digits of the year
		$month = date("m"); // Get the two-digit month
		// Generate a random part that follows the rules
		$random_part = $this->generateValidRandomPart();
		$referral_id = $year . $month . $random_part;
		return $referral_id;
	}

	/**
	 *  Generate an 8-digit random number and ensure it follows the rules
	 */
	function generateValidRandomPart()
	{
		do {
			$random_part = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT); // Generate 8 digits, pad with 0s if needed
		} while ($this->hasInvalidSequences($random_part));
		return $random_part;
	}

	/**
	 *  Check if there are more than four consecutive identical digits or increasing/decreasing sequences
	 */
	function hasInvalidSequences($number)
	{
		// Check if there are more than four consecutive identical digits
		if (preg_match('/(\d)\1{3,}/', $number)) {
			return true;
		}

		// Check if there are more than four consecutive increasing or decreasing sequences
		$sequential_patterns = [
			'0123',
			'1234',
			'2345',
			'3456',
			'4567',
			'5678',
			'6789',
			'9876',
			'8765',
			'7654',
			'6543',
			'5432',
			'4321',
			'3210'
		];

		foreach ($sequential_patterns as $pattern) {
			if (strpos($number, $pattern) !== false) {
				return true;
			}
		}

		return false;
	}

	private function is_unique($ref_code)
	{
		$user = get_users(
			array(
				'meta_key'     => 'wrc_ref_code',
				'meta_value'   => $ref_code,
				'meta_compare' => '=',
				'fields'       => 'ids',
			)
		);
		if (count($user) > 0) {
			return false;
		}

		return true;
	}
}
