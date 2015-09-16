<?php
/*
Plugin Name: WP Feed Mashup
Description: Add feeds from various sources and show them in a nice way.
Version: 0.1-alpha
Author: Christian Z&ouml;ller
Author URI: http://no3x.de/
Plugin URI: http://wordpress.org/extend/plugins/wp-mail-logging/
Text Domain: wp-feed-mashup
Domain Path: /languages
*/

namespace No3x\WPFM;
use No3x\WPFM\Core\Init;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

$WPFM_minimalRequiredPhpVersion = '5.4';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function WPFM_noticePhpVersionWrong() {
	global $WPFM_minimalRequiredPhpVersion;
	echo '<div class="updated fade">' .
	  __( 'Error: plugin "WP Mail Logging" requires a newer version of PHP to be running.',  'WPFM' ).
			'<br/>' . __( 'Minimal version of PHP required: ', 'WPFM' ) . '<strong>' . $WPFM_minimalRequiredPhpVersion . '</strong>' .
			'<br/>' . __( 'Your server\'s PHP version: ', 'WPFM' ) . '<strong>' . phpversion() . '</strong>' .
		 '</div>';
}


function WPFM_PhpVersionCheck() {
	global $WPFM_minimalRequiredPhpVersion;
	if ( version_compare( phpversion(), $WPFM_minimalRequiredPhpVersion ) < 0 ) {
		add_action( 'admin_notices', 'WPFM_noticePhpVersionWrong' );
		return false;
	}
	return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function WPFM_i18n_init() {
	$pluginDir = dirname(plugin_basename(__FILE__));
	load_plugin_textdomain('WPFM', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
WPFM_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (WPFM_PhpVersionCheck()) {
	// Only init and run the init function if we know PHP version can parse it
	require __DIR__ . '/vendor/autoload.php';
	Init::init(__FILE__);
}
