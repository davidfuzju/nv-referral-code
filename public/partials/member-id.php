<?php

/**
 * Member Id shortcode view
 *
 * @var WP_Refer_Code $ref_code class instance
 * @package           WP_Referral_Code
 */

// 检查用户是否已登录


?>
<?php if (!empty($user_id)) : ?>
<?php echo esc_html($user_id); ?>
<?php else : ?>
<?php echo esc_html('None'); ?>
<?php endif; ?>