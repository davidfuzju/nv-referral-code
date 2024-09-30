<?php
/**
 * Copy link shortcode view
 *
 * @var WP_Refer_Code $ref_code class instance
 * @package           WP_Referral_Code
 */

// 拼接当前页面的完整 URL
$current_url = wrc_get_current_url();

// 解析 URL 的组件
$url_components = parse_url($current_url);

// 初始化 query 参数数组
$query_params = array();

// 解析现有的 query 参数（如果存在的话）
if (isset($url_components['query'])) {
	parse_str($url_components['query'], $query_params);
}

// 添加或修改查询参数 'ref'
$query_params['ref'] = $ref_code->get_ref_code(); // 获取推荐码并添加到 query 参数中

// 将修改后的 query 参数重新组装回 URL 组件
$url_components['query'] = http_build_query($query_params);

// 使用自定义的 build_url 函数重新构建 URL
$new_url = wrc_build_url($url_components);
?>

<div class="wprc-copy-link">
	<div class="input-group" dir="rtl">
		<span class="input-group-button">
			<button class="btn-copy" data-clipboard-text="<?php echo esc_url( $new_url ); ?>">
				<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 115.77 122.88" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M89.62,13.96v7.73h12.19h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02v0.02 v73.27v0.01h-0.02c-0.01,3.84-1.57,7.33-4.1,9.86c-2.51,2.5-5.98,4.06-9.82,4.07v0.02h-0.02h-61.7H40.1v-0.02 c-3.84-0.01-7.34-1.57-9.86-4.1c-2.5-2.51-4.06-5.98-4.07-9.82h-0.02v-0.02V92.51H13.96h-0.01v-0.02c-3.84-0.01-7.34-1.57-9.86-4.1 c-2.5-2.51-4.06-5.98-4.07-9.82H0v-0.02V13.96v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07V0h0.02h61.7 h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02V13.96L89.62,13.96z M79.04,21.69v-7.73v-0.02h0.02 c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v64.59v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h12.19V35.65 v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07v-0.02h0.02H79.04L79.04,21.69z M105.18,108.92V35.65v-0.02 h0.02c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v73.27v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h61.7h0.02 v0.02c0.91,0,1.75-0.39,2.37-1.01c0.61-0.61,1-1.46,1-2.37h-0.02V108.92L105.18,108.92z"/></g></svg>
			</button>

		</span>
		<div class="tooltip"><span class="tooltiptext"><?php esc_html_e( 'Tooltip text', 'nv-referral-code' ); ?></span>
			<label>
				<input class="copy-ref-link-input" readonly type="text" value="<?php echo esc_url( $new_url ); ?>">
			</label>
		</div>
	</div>
</div>
