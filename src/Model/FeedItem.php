<?php
/**
 * User: No3x
 * Date: 17.09.15
 * Time: 12:40
 */

namespace No3x\WPFM\Model;


class FeedItem {
	private $properties;

	public function __construct( $item ) {
		foreach( $item as $key => $value ) {
			self::__set($key, $value);
		}
	}

	public function __set($key, $value) {
		//Perform data validation here before inserting data
		$this->properties[$key] = $value;
		return $this;
	}

	public function __get($key) {
		//You might want to check that the data exists here
		return $this->properties[$key];
	}

	public function __isset($key) {
		return array_key_exists( $key, $this->properties );
	}
}