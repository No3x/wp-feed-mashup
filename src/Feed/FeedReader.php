<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 19:12
 */

namespace No3x\WPFM\Feed;
use No3x\WPFM\Model\Feed;

class FeedReader implements IFeedReader {

	function addFeed(Feed $feed) {
		$feed->save();
	}

	function removeFeed(Feed $feed) {
		$feed->delete();
	}

	function refresh() {
		foreach( Feed::all() as $feed ) {
			/* @var $feed Feed */
			//print_r ( $feed->get_rss_url() );
		}
	}
}