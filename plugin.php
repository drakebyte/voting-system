<?php

/*
Plugin Name: Test plugin for WordPress senior developer
Plugin URI: http://lorand.com
Description: Test plugin for WordPress senior developer.
Version: 0.0.1.20240116
Author: Lorand
Author URI: http://lorand.com
License: A "Slug" license name e.g. GPL2
Textdomain: test-plugin-i18n
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CODINGTEST_PLUGIN_BASENAME' ) ) {
	define( 'CODINGTEST_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'CODINGTEST_PLUGIN_VERSION' ) ) {
	define( 'CODINGTEST_PLUGIN_VERSION', '0.0.1.20231113.3' );
}
if ( ! defined( 'CODINGTEST_PLUGIN_DIR' ) ) {
	define( 'CODINGTEST_PLUGIN_DIR', __DIR__ );
}
if ( ! defined( 'CODINGTEST_PLUGIN_URL' ) ) {
	define( 'CODINGTEST_PLUGIN_URL', plugins_url() . '/wordpress-plugin-coding-test' );
}

require_once CODINGTEST_PLUGIN_DIR . '/vendor/autoload.php';

require_once CODINGTEST_PLUGIN_DIR . '/inc/init.php';
