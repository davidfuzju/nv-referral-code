<?php
/**
 * Referrer Code Short Code View
 *
 * @var WP_Refer_Code $ref_code class instance
 * @package           WP_Referral_Code
 */
?>

<?php if (!empty($referrer_code)) : ?>
    <p><?php echo esc_html($referrer_code); ?></p>
<?php else : ?>
    <p>null</p>
<?php endif; ?>