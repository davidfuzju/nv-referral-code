<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting.
		settings_fields( 'wp-referral-code' );
		// output setting sections and their fields.
		do_settings_sections( 'wp-referral-code' );
		// output save settings button.
		submit_button( __( 'Save Settings', 'wp-referral-code' ) );
		?>
	</form>

</div>

<div class="wrap wrc-shortcodes-table">
	<h1><?php esc_html_e( 'Available shortcodes', 'wp-referral-code' ); ?></h1>
	<span style="margin: .5rem 0">Click on Shortcode to copy it</span>
	<table>
		<thead>
		<tr>
			<th>Shortcode</th>
			<th>Description</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="copy_ref_link"]
			</td>
			<td>
				<?php esc_html_e( 'Copy Referral Link', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays a user-friendly box that allows the current user to easily copy their referral link', 'wp-referral-code' ); ?>
			</td>
		</tr>
		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="ref_code"]
			</td>
			<td>
				<?php esc_html_e( 'Referral Code', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays the current user\'s unique referral code', 'wp-referral-code' ); ?>
			</td>
		</tr>

		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="ref_link"]
			</td>
			<td>
				<?php esc_html_e( 'Referral Link', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays the referral link specific to the current user. This includes the user\'s referral code and registration link for new users', 'wp-referral-code' ); ?>
			</td>
		</tr>

		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="invited_count"]
			</td>
			<td>
				<?php esc_html_e( 'Invited User Count', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays the number of users that the current user has successfully referred', 'wp-referral-code' ); ?>
			</td>
		</tr>

		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="invited_list"]
			</td>
			<td>
				<?php esc_html_e( 'Invited User List', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays a list of users that the current user has successfully referred. The default display is the list of usernames, but you can use hooks to customize the display', 'wp-referral-code' ); ?>
			</td>
		</tr>

		<tr>
			<td class="wrc-shortcode">
				[wp-referral-code var="most_referring_users"]
			</td>
			<td>
				<?php esc_html_e( 'Top Referring Users', 'wp-referral-code' ); ?><br>
				<?php esc_html_e( 'Displays a list of the top referring users. By default, the list displays the top 10 users, but you can use hooks to customize the number of users displayed', 'wp-referral-code' ); ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<!-- Asking for review -->
<div class="wrap" style="border: 1px solid #ddd; background-color: #f9f9f9; padding: 20px;">
	<h3 style="margin-top: 0;">Enjoying the plugin?</h3>
	<p>Help me out by leaving a review on WordPress.org.</p>
	<a href="https://wordpress.org/support/plugin/wp-referral-code/reviews/#new-post" target="_blank">
		Leave a review
	</a>
</div>


<div class="wrc-toast">
	<div class="wrc-toast-content">
		<?php esc_html_e( 'Copied to clipboard!', 'wp-referral-code' ); ?>
	</div>
</div>


<script>
	jQuery(document).ready(function () {
		jQuery('.wrc-shortcode').click(function () {
			console.log('clicked');
			var range = document.createRange();
			range.selectNode(jQuery(this)[0]);
			window.getSelection().removeAllRanges();
			window.getSelection().addRange(range);
			document.execCommand('copy');
			window.getSelection().removeAllRanges();

			let toast = jQuery('.wrc-toast');
			if (!toast.hasClass('show')) {
				toast.addClass('show');
				setTimeout(function () {
					toast.removeClass('show');
				}, 1300);
			}
		});
	});
</script>

<style>
	/* keyframes for fade in and out animation */
	@keyframes fadein {
		from {
			opacity: 0;
		}
		to {
			opacity: .85;
		}
	}

	@keyframes fadeout {
		from {
			opacity: .85;
		}
		to {
			opacity: 0;
		}
	}

	/* CSS code for toast */
	td.wrc-shortcode {
		padding: 1rem;
		font-weight: bold;
		cursor: pointer;
		font-family: monospace, serif, Arial, "Times New Roman", "Bitstream Charter", Times, serif;
	}

	.wrc-toast {
		visibility: hidden;
		min-width: 250px;
		margin-left: -125px;
		background-color: #0a4b83;
		color: #fff;
		text-align: center;
		border-radius: 2px;
		padding: .5rem 1rem;
		position: fixed;
		z-index: 1;
		left: 50%;
		bottom: 30px;
		font-size: 1rem;
		opacity: 0;
		transition: opacity 300ms;
	}

	.wrc-toast.show {
		visibility: visible;
		animation: fadein 0.5s ease, fadeout 0.5s ease 1.3s;
		opacity: .85;
	}

	.wrc-shortcodes-table table {
		border-collapse: collapse;
		width: 100%;
		max-width: 100%;
		overflow-x: auto;
		margin-top: 1rem;
	}

	.wrc-shortcodes-table th,
	.wrc-shortcodes-table td {
		border: 1px solid rgba(169, 169, 169, 0.91);
		padding: 8px;
		text-align: left;
	}

	.wrc-shortcodes-table th {
		background-color: #f5f5f5;
		font-weight: bold;
	}

	.wrc-shortcodes-table tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	.wrc-shortcodes-table tr:hover {
		background-color: #ddd;
	}

	@media (max-width: 768px) {
		.wrc-shortcodes-table table {
			display: block;
			overflow-y: hidden;
			border: none;
		}

		.wrc-shortcodes-table thead {
			display: none;
		}

		.wrc-shortcodes-table tbody {
			display: block;
			width: auto;
			position: relative;
			overflow-x: auto;
			white-space: nowrap;
		}

		.wrc-shortcodes-table th,
		.wrc-shortcodes-table td {
			display: block;
			border: none;
			text-align: left;
		}

		.wrc-shortcodes-table td:before {
			content: attr(data-label);
			float: left;
			text-transform: uppercase;
			font-weight: bold;
			padding-right: 1em;
		}
	}

</style>
