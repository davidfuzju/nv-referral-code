<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Simple check for validating a URL, it must start with http:// or https://.
 * and pass FILTER_VALIDATE_URL validation.
 *
 * @param string $url to check.
 *
 * @return bool
 */
function wrc_is_valid_url( $url ) {

	// Must start with http:// or https://.
	if ( 0 !== strpos( $url, 'http://' ) && 0 !== strpos( $url, 'https://' ) ) {
		return false;
	}

	// Must pass validation.
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return false;
	}

	return true;
}

/**
 * Returns ref query holder: default is 'ref
 * url example: https://domain.com/register/?=ref=324rf4
 *
 * @return string
 */
function wrc_get_ref_code_query() {
	return apply_filters( 'wp_referral_code', 'ref' );
}


/**
 * Sets ref code for all users. if refresh is true all user will get new code
 * if refresh false only users will get a ref code who do not have one already
 *
 * @param bool $refresh
 *
 * @param int  $length
 *
 * @return void
 */
function wrc_set_ref_code_all_users( $refresh, $length ) {

	$users = get_users();

	foreach ( $users as $user ) {
		$user_id = $user->ID;
		if ( $refresh ) {
			$ref_code = Shalior_Referral_Code_Generator::get_instance()->get_ref_code( $length );
			update_user_meta( $user_id, 'wrc_ref_code', $ref_code );
		} else {
			if ( ! metadata_exists( 'user', $user_id, 'wrc_ref_code' ) ) {
				$ref_code = Shalior_Referral_Code_Generator::get_instance()->get_ref_code( $length );
				update_user_meta( $user_id, 'wrc_ref_code', $ref_code );
			}
		}
	}
}

/**
 * get current url from current request
 */
function wrc_get_current_url() {
	// Get the protocol (HTTP or HTTPS)
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	// Get the hostname
	$host = $_SERVER['HTTP_HOST'];

	// Get the requested URI (the path part)
	$uri = $_SERVER['REQUEST_URI'];

	// Combine to form the full URL of the current page
	$current_url = $protocol . $host . $uri;
	
	return $current_url;
}

/**
 * Custom build_url function to reassemble URL components into a complete URL
 *
 * @param array $url_components The array of URL components parsed by parse_url
 * @return string The reassembled complete URL
 */
function wrc_build_url($url_components) {
	$url = '';

	// Handle the scheme (http, https)
	if (isset($url_components['scheme'])) {
		$url .= $url_components['scheme'] . '://';
	}

	// Handle user and pass
	if (isset($url_components['user'])) {
		$url .= $url_components['user'];
		if (isset($url_components['pass'])) {
			$url .= ':' . $url_components['pass'];
		}
		$url .= '@';
	}

	// Handle the host (domain name)
	if (isset($url_components['host'])) {
		$url .= $url_components['host'];
	}

	// Handle the port number
	if (isset($url_components['port'])) {
		$url .= ':' . $url_components['port'];
	}

	// Handle the path
	if (isset($url_components['path'])) {
		$url .= $url_components['path'];
	}

	// Handle the query string
	if (isset($url_components['query'])) {
		$url .= '?' . $url_components['query'];
	}

	// Handle the fragment (URL anchor)
	if (isset($url_components['fragment'])) {
		$url .= '#' . $url_components['fragment'];
	}

	return $url;
}

/**
 *  array_unique version for STDClass elements
 */
function array_unique_2($array) {
	$unique = [];
	foreach ($array as $item) {
		$is_unique = true;
		foreach ($unique as $unique_item) {
			if ($item->i === $unique_item->i) {
				$is_unique = false;
				break;
			} 
		}
		
		if ($is_unique) {
			$unique[] = $item;
		}
	}
	
	return $unique;
}

/**
 * @deprecated
 */
function wp_referral_code_add_user_to_referrer_invite_list( $user_id, $referrer_id ) {
	$users_referred_by_referrer = get_user_meta( $referrer_id, 'wrc_invited_users', true );
	if ( empty( $users_referred_by_referrer ) ) {
		update_user_meta( $referrer_id, 'wrc_invited_users', array( $user_id ) );
	} else {
		$users_referred_by_referrer[] = $user_id;
		$users_referred_by_referrer   = array_unique( $users_referred_by_referrer );
		update_user_meta( $referrer_id, 'wrc_invited_users', $users_referred_by_referrer );
	}
}

/**
 * @deprecated
 */
if ( ! function_exists( 'wp_referral_code_delete_relation' ) ) {
	function wp_referral_code_delete_relation( $to_delete_user_id, $referrer_id ) {
		$users_referred_by_referrer = get_user_meta( $referrer_id, 'wrc_invited_users', true );
		if ( empty( $users_referred_by_referrer ) ) {
			return;
		}
		$users_referred_by_referrer = array_diff( $users_referred_by_referrer, array( $to_delete_user_id ) );
		$users_referred_by_referrer = array_unique( $users_referred_by_referrer );
		update_user_meta( $referrer_id, 'wrc_invited_users', $users_referred_by_referrer );
		update_user_meta( $to_delete_user_id, 'wrc_referrer_id', null );

		do_action( 'wp_referral_code_after_relation_deleted', $to_delete_user_id, $referrer_id );
	}
}

/**
 * Add the referred user to the referral invitation list, with the array stored in the wrc_invited_users field of the user_meta table. 
 */
function wp_referral_code_add_user_to_referrer_invite_list_2( $user_id, $referrer_id, $referrer_url) {
	$users_referred_by_referrer = get_user_meta( $referrer_id, 'wrc_invited_users_2', true );
	if ( empty( $users_referred_by_referrer ) ) {
		update_user_meta( $referrer_id, 'wrc_invited_users_2', array( (object)array('i' => $user_id, 'j' => $referrer_url) ) );
	} else {
		$users_referred_by_referrer[] = (object)array('i' => $user_id, 'j' => $referrer_url);
		$users_referred_by_referrer = array_unique_2( $users_referred_by_referrer );
		update_user_meta( $referrer_id, 'wrc_invited_users_2', $users_referred_by_referrer );
	}
}

/**
 * Remove the referred user from the referral invitation list, with the array stored in the wrc_invited_users field of the user_meta table. 
 */
if ( ! function_exists( 'wp_referral_code_delete_relation_2' ) ) {
	function wp_referral_code_delete_relation_2( $to_delete_user_id, $referrer_id ) {
		$users_referred_by_referrer = get_user_meta( $referrer_id, 'wrc_invited_users_2', true );
		if ( empty( $users_referred_by_referrer ) ) {
			return;
		}
		$users_referred_by_referrer = array_udiff(
			$users_referred_by_referrer, 
			array( (object)array('i' => $to_delete_user_id, 'j' => null) ), 
			function ($a, $b) { 
				if ( $a->i === $b->i ) {
					return 0;
				}
				return ($a->i < $b->i) ? -1 : 1;  // 按 'i' 进行排序比较
			}
		);

		$users_referred_by_referrer = array_unique_2( $users_referred_by_referrer );
		update_user_meta( $referrer_id, 'wrc_invited_users_2', $users_referred_by_referrer );
		update_user_meta( $to_delete_user_id, 'wrc_referrer_id', null );
		update_user_meta( $to_delete_user_id, 'wrc_referrer_code', null );
		update_user_meta( $to_delete_user_id, 'wrc_referrer_url', null );

		do_action( 'wp_referral_code_after_relation_deleted', $to_delete_user_id, $referrer_id );
	}
}

if ( ! function_exists( 'wrc_set_cookie' ) ) {

	function wrc_set_cookie( $name, $value, $expire = 0 ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		} elseif ( true === WP_DEBUG ) {
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			trigger_error( "{$name} cookie cannot be set headers already sent.", E_USER_NOTICE );
			// phpcs:enable
		}
	}
}

function nv_get_referral_url() {
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
	$user_id  = get_current_user_id();
	$ref_code = new WP_Refer_Code( $user_id );
	$query_params['ref'] = $ref_code->get_ref_code(); // 获取推荐码并添加到 query 参数中

	// 将修改后的 query 参数重新组装回 URL 组件
	$url_components['query'] = http_build_query($query_params);

	// 使用自定义的 build_url 函数重新构建 URL
	$new_url = wrc_build_url($url_components);

	return $new_url;
}