<?php
/*
Plugin Name: URL Health Check
Plugin URI: https://github.com/joshp23/YOURLS-URL-Health-Check
Description: Checks submitted long URL's for validity, reachability, and redirection
Version: 0.0.2
Author: Josh Panter
Author URI: https://unfettered.net
*/
// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
yourls_add_filter('shunt_add_new_link', 'url_health_check');
function url_health_check( $is, $url, $keyword = '' , $title = '') {

	$return = false;

	if ( ! yourls_get_protocol( $url ) )
		$url = 'https://'.$url;

	// validate url
	if( !filter_var($url, FILTER_VALIDATE_URL) ) {
		$return['status']    = 'fail';
		$return['code']      = 'error:invalid';
		$return['message']   = yourls__( 'HealthCheck: Invalid URL');
		$return['statusCode'] = '400';
	}

	if ( $return == false && in_array( yourls_get_protocol( $url ), array( 'http://', 'https://' ) ) ) {

		$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 0); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 3); // TODO Is this reasonable?
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$finalURL = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL );
			curl_close($ch);

		// update url to redirection desstiantion if needed
		if ( $finalURL !== $url ) {

			define( 'URLHC_REDIRECT', $finalURL );
			yourls_add_filter( 'add_new_link', 'URLHC_redirection' );

		// otherwise, check for success
		} elseif ( $httpcode !== 200 ) {
			// and if not...
			$return['status']   = 'fail';
			$return['code']     = 'error:url';
			$return['message']  = yourls__( 'The destination URL is unreachable.' );
			$return['statusCode'] = 200; // regardless of result, this is still a valid request
		}
	}

	return $return;
}

function URLHC_redirection( $return, $old , $keyword, $title  ) {
	$new = URLHC_REDIRECT;
	global $ydb;
	$table = YOURLS_DB_TABLE_URL;
	$update = null;
	if (version_compare(YOURLS_VERSION, '1.7.3') >= 0) {
		$binds = array('old' => $old, 'new' => $new);
		$sql = "UPDATE `$table` SET `url` = REPLACE(`url`, :old, :new) WHERE `url` = :old";
		$update = $ydb->fetchAffected($sql, $binds);
	} else {
		$update = $ydb->query("UPDATE `$table` SET `url` = REPLACE(`url`, '$old', '$new') WHERE `url` = '$old'");
	}
	$return['url']['url'] = $new;
	return $return;
}
