<?php
/**
 * User: No3x
 * Date: 17.09.15
 * Time: 12:46
 */

namespace No3x\WPFM\Feed;


interface ICache {
	function refresh();
	function purge();
	function load();
} 