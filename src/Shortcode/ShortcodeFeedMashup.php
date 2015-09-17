<?php
/**
 * User: No3x
 * Date: 16.09.15
 * Time: 21:02
 */

namespace No3x\WPFM\Shortcode;
use No3x\WPFM\Feed\IFeedReader;
use No3x\WPFM\Template\Feed;
use No3x\WPFM\Feed\FeedReader;

class ShortcodeFeedMashup extends AShortCodeLoader {

	/**
	 * @var \No3x\WPFM\Feed\IFeedReader
	 */
	private $feedReader;

	public function __construct( IFeedReader $feedReader ) {
		$this->feedReader = $feedReader;
	}

	/**
	 * @param  $atts shortcode inputs
	 * @return string shortcode content
	 */
	public function handleShortcode( $atts, $content = null ) {
		$attributes = shortcode_atts( array(
			// Attributes are going to overwrite options
			'id' => 0,
			'color' => 'cyan',
		), $atts );
		$feed = $this->feedReader->getFeedFromId( 1 );
		$feedItems = $this->feedReader->getFeedItemsFromId( 1 );
		echo Feed::render( $feed, $feedItems, $attributes );
	}

	public function addActionsAndFilters() {
		$this->register('feed-mashup');
	}
}