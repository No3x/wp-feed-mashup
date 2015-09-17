<?php
/**
 * User: No3x
 * Date: 17.09.15
 * Time: 15:24
 */

namespace No3x\WPFM\Twig;

use No3x\WPFM\Model\IHashable;

class TwigExtensions extends \Twig_Extension {

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName() {
		return "WPFM Twig Extensions";
	}

	/**
	 * Return a list of all filters.
	 *
	 * @return array
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('md5', [$this,'md5Filter']),
			new \Twig_SimpleFilter('humanDateDiff', [$this, 'humanDateDiff']),
		];
	}

	/**
	 * Return the md5 hash of a string
	 *
	 * @param string $value
	 * @return string 32 alphanumeric characters
	 */
	public function md5Filter( $value ) {
		if( $value instanceof IHashable ) {
			error_log( print_r( $value->hash(), true ) );
			return $value->hash();
		}
		if( is_array( $value ) || is_object( $value ) ) {
			$value = serialize( $value);
		}
		return md5( $value );
	}

	public function humanDateDiff( $value ) {
		return human_time_diff( strtotime( $value ) );
	}
}