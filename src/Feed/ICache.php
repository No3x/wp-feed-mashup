<?php
/**
 * User: No3x
 * Date: 17.09.15
 * Time: 12:46
 */

namespace No3x\WPFM\Feed;


/**
 * Interface ICache - Basic functions for caching.
 * @package No3x\WPFM\Feed
 */
interface ICache {
	/**
	 * Refreshes the cached data.
	 * @return mixed
	 */
	function refresh();

	/**
	 * Removed the cached data.
	 * @return mixed
	 */
	function clear();

	/**
	 * Returns the cached data.
	 * @return mixed
	 */
	function load();

	/**
	 * Saves the data to cache.
	 * @return mixed
	 */
	function save();
} 