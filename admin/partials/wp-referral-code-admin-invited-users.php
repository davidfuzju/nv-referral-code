<?php
/**
 * Admin user-edit page view: Shows user's referral data
 *
 * @var  WP_Refer_Code $ref_code classInstance
 * @var int $user_id user's id
 * @package wp-referral-code
 */

$referrer_id       = $ref_code->get_referrer_id();
$invited_users = $ref_code->get_invited_users();

?>
<br>
<h2 id="wp-referral-code-user-edit"><?php esc_html_e( 'WP Referral Code', 'wp-referral-code' ); ?></h2>

<table class="form-table">
	<tr>
		<th><?php esc_html_e( 'Referral Code info', 'wp-referral-code' ); ?></th>
		<td>
			<!-- Lists  -->
			<br>
			<ul class="invited-users_list list">
				<?php if ( ! empty( $referrer_id ) ) : ?>
					<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $referrer_id . '#wp-referral-code-user-edit' ) ); ?>"  target="_blank">
						<?php esc_html_e( 'This user has been invited by ', 'wp-referral-code' ); ?>
						<strong class="text-lg">
							<?php
							echo esc_html(
								get_user_meta( $referrer_id, 'first_name', true ) . ' ' .
								get_user_meta( $referrer_id, 'last_name', true )
							);
							?>
						</strong>

						<?php if ( empty( get_user_meta( $user_id, 'wrc_referrer_url', true ) ) ) : ?>
						<?php else : ?>
						<?php esc_html_e( 'through the URL ', 'wp-referral-code') ?>
						<strong class="text-lg">
							<?php
							echo esc_html(
								'[' . 
								get_user_meta( $user_id, 'wrc_referrer_url', true ) .
								']'
							);
							?>
						</strong></a>
						<?php endif ?>
					<br>
					<hr>
				<?php else : ?>
					<?php esc_html_e( 'No one invited this user', 'wp-referral-code' ); ?> <br>
					<hr>
				<?php endif; ?>

				<?php esc_html_e( 'This user\'s default invite link: ', 'wp-referral-code' ); ?>
				<a href="<?php esc_url( $ref_code->get_ref_link() ); ?>" target="_blank"><?php echo esc_url( $ref_code->get_ref_link() ); ?></a>
				<br>
				<hr>

				<div style="margin: 1rem 0;">
					<p><strong>Manually add a user to referred list</strong></p>
					<br>

					<?php require_once WP_REFERRAL_CODE_PATH . 'admin/partials/user-select-search.php'; ?>
					<button style="background-color: #2ddd30; border-color: #389d05"
							id="wrc-add-rel-button"
							data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
							class="wrc-add-relation button button-small button-primary add">
						<?php esc_html_e( 'Add', 'wp-referral-code' ); ?>
					</button>
				</div>

				<hr>
				<?php if ( empty( $invited_users ) ) : ?>
					<?php esc_html_e( 'this user has invited 0 users', 'wp-referral-code' ); ?>
				<?php else : ?>

				<h4><?php esc_html_e( 'This user has invited following users: ', 'wp-referral-code' ); ?></h4>
				<ul class="wp-referral-code-invited-users">
					<?php
					foreach ( $invited_users as $user ) :
						$invited_user_id = $user->i;
						$invited_user_url = $user->j;
						$invited_user = new WP_User( $invited_user_id );
						?>
						<li class="invited-user-item item" id="<?php echo esc_attr( $invited_user_id ); ?>">
							<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $invited_user_id ) ); ?>" target="_blank">
								<?php echo esc_html( $invited_user->get( 'first_name' ) . ' ' . $invited_user->get( 'last_name' ) . "( $invited_user->user_login )" ); ?>
							</a>
							<?php if ( empty( $invited_user_url ) ) : ?>
							<?php else : ?>
							<a href="<?php echo esc_url( $invited_user_url ); ?>" target="_blank">
								<?php echo esc_html( '[' . $invited_user_url. ']' ); ?>
							</a>
							<?php endif ?>

							<button style="background-color: #dd382d; border-color: #dd382d"
									class="wrc-remove-relation button button-small button-primary delete-permanently"
									data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
									data-user-id="<?php echo esc_attr( $invited_user_id ); ?>">
								<?php esc_html_e( 'Delete', 'wp-referral-code' ); ?>
							</button>
						</li>
						<?php
					endforeach;
					endif;
				?>
				</ul>
			</ul>
		</td>
	</tr>
</table>
