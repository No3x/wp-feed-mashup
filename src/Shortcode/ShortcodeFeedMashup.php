<?php
/**
 * User: No3x
 * Date: 16.09.15
 * Time: 21:02
 */

namespace No3x\WPFM\Shortcode;
use No3x\WPFM\Template\Feed;


class ShortcodeFeedMashup extends AShortCodeLoader {

	/**
	 * @param  $atts shortcode inputs
	 * @return string shortcode content
	 */
	public function handleShortcode( $atts, $content = null ) {
		extract( $attributes = shortcode_atts( array(
			// Attributes are going to overwrite options
			'id' => 0,
			'color' => 'cyan',
		), $atts ) );
		echo Feed::render( $attributes );
	}

	public function addActionsAndFilters() {
		$this->register('feed-mashup');
	}
}