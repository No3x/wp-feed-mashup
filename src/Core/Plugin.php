<?php
/**
 * User: No3x
 * Date: 14.09.15
 * Time: 18:55
 */

namespace No3x\WPFM\Core;
use No3x\WPFM\Core\LifeCycle;
use No3x\WPFM\Feed\IFeedReader;
use No3x\WPFM\Model\Feed;

class Plugin extends LifeCycle {
	/**
	 * @var \No3x\WPFM\Feed\IFeedReader
	 */
	private $feedReader;
	/**
	 * @var \No3x\WPFM\Core\PluginMeta
	 */
	private $plugin_meta;

	public function __construct( IFeedReader $feedReader, PluginMeta $plugin_meta ) {
		parent::__construct( $plugin_meta );
		$this->plugin_meta = $plugin_meta;
		$this->feedReader = $feedReader;
	}

	public function addActionsAndFilters() {
		add_action( 'plugins_loaded', array( $this, 'refreshReader') );
		add_action( 'wp_enqueue_scripts', array( $this, 'addStyles' ) );
	}

	public function addStyles() {
		wp_enqueue_style( 'wpfm-tabs', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '../../../css/tabs.css', array(), '0.0.1' );
	}

	public function refreshReader() {
		$this->feedReader->refresh();
	}

	protected function installDatabaseTables() {
		Feed::installDatabaseTables();
		Feed::installFixtures();
	}
}