<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 19:12
 */

namespace No3x\WPFM\Feed;
use No3x\WPFM\Model\Feed;
use No3x\WPFM\Model\FeedItem;

/**
 * Retrieves FeedItems from Feeds via rss reader.
 * It implements a cache.
 * @package No3x\WPFM\Feed
 */
class FeedReader implements IFeedReader, ITransientCache {
	/**
	 * @var Feed[] subscribed feeds.
	 */
	private $feeds;

	function addActionsAndFilters() {
		add_action( 'init', array( $this, 'init') );
	}

	/**
	 * Initialises '$feeds' on WordPress init.
	 */
	function init() {
		if ( false !== ($feeds_cached = $this->load() ) ) {
			$this->feeds = $feeds_cached;
		}
	}

	/**
	 * Add a feed to Reader.
	 * @param Feed $feed the feed to add
	 */
	function addFeed(Feed $feed) {
		$feed->save();
		$this->refresh();
	}

	/**
	 * Remove Feed from reader
	 * @param Feed $feed the feed to remove
	 */
	function removeFeed(Feed $feed) {
		$feed->delete();
		$this->refresh();
	}

	function getFeedFromId( $id ) {
		if( isset( $this->feeds ) && array_key_exists( $id, $this->feeds ) )
			return Feed::find_one($id);
	}

	function getFeedItemsFromId( $id ) {
		if( isset( $this->feeds ) && array_key_exists( $id, $this->feeds ) )
			return $this->feeds[$id];
	}

	/**
	 * Refreshes the cached data.
	 * @return mixed
	 */
	function refresh() {
		foreach( Feed::all() as $feed ) {
			$feeds_new = $this->fetch( $feed );
			if( false === $feeds_new ) {
				// There was a problem loading new content
				if( false !== ($feeds_cached = $this->load() ) ) {
					// Try to get old feed data
					if( array_key_exists( $feed->get_id(), $feeds_cached ) ) {
						// If there is already a feed use it's feed items
						$this->feeds[$feed->get_id()] = $feeds_cached[$feed->get_id()];
					}
				} else {
					// Well there is nothing we can do in this case - there is no cached version of this feeditems
				}
			} else {
				// If new load was good use it
				$this->feeds[$feed->get_id()] = $feeds_new;
			}
		}
		$this->save();
	}

	/**
	 * Returns the cached data.
	 * @return mixed
	 */
	function load() {
		if ( false !== ( $feeds_cached = maybe_unserialize( get_transient( ITransientCache::TRANSIENT_NAME ) ) ) ) {
			return $feeds_cached;
		} else {
			return false;
			//TODO: Error
		}
	}

	/**
	 * Removed the cached data.
	 * @return mixed
	 */
	function clear() {
		delete_transient( ITransientCache::TRANSIENT_NAME );
	}

	/**
	 * Saves the data to cache.
	 * @return mixed
	 */
	function save() {
		set_transient( ITransientCache::TRANSIENT_NAME, maybe_serialize($this->feeds), 1 * MINUTE_IN_SECONDS);
	}

	private function fetch( Feed $feed ) {
		$num = 5;
		$cache_time = 30 * MINUTE_IN_SECONDS;

		// Include SimplePie RSS parsing engine
		include_once ABSPATH . WPINC . '/feed.php';

		// Set the cache time for SimplePie
		add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', "return $cache_time;" ) );

		// Build the SimplePie object
		$rss = fetch_feed($feed->get_rss_url() );

		// Check for errors in the RSS XML
		if ( !is_wp_error( $rss ) ) {
			// Set a limit for the number of items to parse
			$maxitems = $rss->get_item_quantity($num );
			$rss_items = $rss->get_items(0, $maxitems );

			/* @var $feedItems FeedItem[] */
			$feedItems = array();
			foreach ($rss_items as $item) {
				$feedItems[] = new FeedItem( array(
					'title' => $item->get_title(),
					'link' => $item->get_permalink(),
					'desc' => $item->get_description(),
					'date_posted' => $item->get_date(),
				) );
			}
			return $feedItems;
		} else {
			return false;
		}
		return false;
	}
}