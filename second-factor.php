<?php
/*
Plugin Name: Second Factor
Plugin URI: http://wordpress.org/#
Description: Require secondary authentication for registered user access
Author: Demitrious Kelly
Version: 1.0
Author URI: http://blog.apokalyptik.com/
*/

function second_factor_get_token( $user ) {
	$token = get_user_meta( $user->ID, 'second_factor' );
	return $token[0];
}

function second_factor_regenerate_token( $username, $send=true ) {
	$user = get_user_by('login', $username);
	$hash = hash_hmac( 'sha256', serialize( array( $_GET, $_POST, $_SERVER ) ), uniqid( 'second_factor', true ) );
	$now = time();
	$token = implode( ':', array( '1', $hash, $now ) );
	if ( get_user_meta( $user->ID, 'second_factor' ) )
		update_user_meta( $user->ID, 'second_factor', $token );
	else
		add_user_meta( $user->ID, 'second_factor', $token, true );
	if ( !$send )
		return;
	$user_hash = hash_hmac( 'sha256', $user->ID, $user->user_pass );
	$user_time_hash  = hash_hmac( 'sha256', $hash, $user_hash );
	$user_token = hexdec( substr( md5( $user_time_hash . $user_hash ), 9, 4 ) );
	wp_mail( 
		$user->user_email, 
		get_bloginfo( 'name' ) . " -- Second Factor Token for " . gmdate( 'r', $now ), 
		"Your new second factor token is: $user_token"
	);
}

function second_factor_logout_regen_token() {
	$user = wp_get_current_user();
	if ( is_object( $user ) && isset( $user->ID ) && $user->ID )
		second_factor_regenerate_token( $user->user_login, false );
}

function second_factor_is_login_url() {
	$url = parse_url( wp_login_url() );
	$uri = $url['path'];
	return ( 0 === strpos( $_SERVER['REQUEST_URI'], $uri ) );
}

function second_factor_ignore_url() {
	if ( second_factor_is_login_url() )
		return true;
	return false;
}

function second_factor_enforce_security() {
	if ( second_factor_ignore_url() )
		return;
	$user = wp_get_current_user();
	if ( !is_object( $user ) || !isset( $user->ID ) || !$user->ID )
		return;
	$token = explode( ':', second_factor_get_token( $user ) );
	$user_hash = hash_hmac( 'sha256', $user->ID, $user->user_pass );
	$user_time_hash  = hash_hmac( 'sha256', $token[1], $user_hash );
	$user_token = hexdec( substr( md5( $user_time_hash . $user_hash ), 9, 4 ) );
	if ( !isset( $_COOKIE['second_factor'] ) || $_COOKIE['second_factor'] != $user_time_hash ) {
		if ( isset( $_POST['second_factor'] ) && $_POST['second_factor'] == $user_token ) {
			setcookie( 'second_factor', $user_time_hash, 0, '/' );
		} else {
			echo '<html><body><div style="width: 30em; margin: auto;">';
			echo '<p>An email message has been sent to you with the following subject line:</p>';
			echo '<p style="text-align: center;"><strong>&#8216;'.get_bloginfo( 'name' ) . " -- Second Factor Token for " . gmdate( 'r', $token[2] )."&#8217;</strong></p>";
			echo '<p>This email contains a token, which you need to enter, below, to complete your login.  ';
			echo 'Logging out and back in will cause a new message with a new token to be sent to you, and the old token will no longer be valid.</p>';
			echo '<form method="POST"><p style="text-align: center;">Second Factor Token: <input name="second_factor" type="password"><input type="submit"> ';
			echo 'or <a href="'.wp_logout_url('/').'">log out</a></p></form></div>';
			echo '</body></html>';
			die();
		}
	}
}

add_action( 'wp_loaded', 'second_factor_enforce_security', 0 );
add_action( 'wp_login', 'second_factor_regenerate_token' );
add_action( 'wp_logout', 'second_factor_logout_regen_token' );
