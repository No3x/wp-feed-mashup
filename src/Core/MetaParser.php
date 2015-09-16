<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 20:10
 */

namespace No3x\WPFM\Core;


class MetaParser {

	private $mainPluginFileName;

	public function __construct( $mainPluginFileName ) {
		$this->mainPluginFileName = $mainPluginFileName;
	}

	/**
	 * Get a value for input key in the header section of main plugin file.
	 * E.g. "Plugin Name", "Version", "Description", "Text Domain", etc.
	 * @param $key string plugin header key
	 * @return string if found, otherwise null
	 */
	public function getValue($key) {
		// Read the string from the comment header of the main plugin file.
		$data = file_get_contents( $this->getMainPluginFileName() );
		$match = array();
		preg_match( '/' . $key . ':\s*(.*)/', $data, $match );
		if ( count( $match ) >= 1 ) {
			return $match[1];
		}
		return null;
	}

	/**
	 * @return mixed
	 */
	public function getMainPluginFileName()
	{
		return $this->mainPluginFileName;
	}
} 