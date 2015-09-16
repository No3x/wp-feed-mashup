<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 19:13
 */

namespace No3x\WPFM\Feed;
use No3x\WPFM\Model\Feed;

interface IFeedReader {
	function addFeed( Feed $feed );
	function removeFeed( Feed $feed );
	function refresh();
}