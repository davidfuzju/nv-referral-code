<?php
/**
 * Referrer Code Short Code View
 *
 * @var WP_Refer_Code $ref_code class instance
 * @package           WP_Referral_Code
 */
?>
<?php if (!empty($referrer_code)) : ?>
<?php echo esc_html($referrer_code); ?>
<?php else : ?>
<?php echo esc_html("None"); ?>
<?php endif; ?>