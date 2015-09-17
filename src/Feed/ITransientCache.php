<?php

/**
 * User: No3x
 * Date: 17.09.15
 * Time: 12:51
 */

namespace No3x\WPFM\Feed;

/**
 * Interface ITransientCache - Cache with Transient API
 * @package No3x\WPFM\Feed
 * https://codex.wordpress.org/Transients_API
 */
interface ITransientCache extends ICache {
	/** Name of the transient */
	const TRANSIENT_NAME = 'wpfm_feed_query';
} 