<?php
/**
 * User: No3x
 * Date: 14.09.15
 * Time: 18:34
 */

namespace No3x\WPFM\Core;
use No3x\WPFM\Core\Plugin;
use No3x\WPFM\DependencyInjection\DIContainer;
use No3x\WPFM\Feed\FeedReader;
use No3x\WPFM\Shortcode\ShortcodeFeedMashup;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Init {
	public static function init( $file ) {

		$container = new DIContainer();
		$container['Plugin'] = function ($c) {
			return new Plugin( $c['FeedReader'], $c['plugin-meta'] );
		};
		$container['plugin-meta'] = function ($c) use ( $file ) {
			return new PluginMeta( new MetaParser( $file ) );
		};
		$container['FeedReader'] = function ($c) {
			return new FeedReader();
		};
		$container['ShortcodeFeedMashup']= function ($c) {
			return new ShortcodeFeedMashup( $c['FeedReader'] );
		};
		$container->addActionsAndFilters();

		/*
		 * Install the plugin
		 * NOTE: this file gets run each time you *activate* the plugin.
		 * So in WP when you "install" the plugin, all that does it dump its files in the plugin-templates directory
		 * but it does not call any of its code.
		 * So here, the plugin tracks whether or not it has run its install operation, and we ensure it is run only once
		 * on the first activation
		 */
		if ( ! $container['Plugin']->isInstalled() ) {
			$container['Plugin']->install();
		} else {
			// Perform any version-upgrade activities prior to activation (e.g. database changes).
			$container['Plugin']->upgrade();
		}
		if ( ! $file ) {
			$file = __FILE__;
		}
		// Register the Plugin Activation Hook.
		register_activation_hook( $file, array( &$container['Plugin'], 'activate' ) );
		// Register the Plugin Deactivation Hook.
		register_deactivation_hook( $file, array( &$container['Plugin'], 'deactivate' ) );
	}
}