<?php
/**
 * Member Id shortcode view
 *
 * @var WP_Refer_Code $ref_code class instance
 * @package           WP_Referral_Code
 */

    // 检查用户是否已登录


?>

<?php if (!empty($member_id)) : ?>
    <p><?php echo esc_html($member_id); ?></p>
<?php else : ?>
    <p>You are not logged in.</p>
<?php endif; ?>