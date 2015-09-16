<?php
/**
 * User: No3x
 * Date: 14.09.15
 * Time: 19:16
 */

namespace No3x\WPFM\Model;
use \WordPress\ORM\BaseModel;

class Feed extends BaseModel {
	protected $id;
	protected $title;
	protected $rss_url;

	public static function get_primary_key() {
		return 'id';
	}

	public static function get_table() {
		global $wpdb;
		return $wpdb->prefix . 'wpfm_feeds';
	}

	public static function get_searchable_fields() {
		return ['title', 'rss_url'];
	}

	public static function installDatabaseTables() {
		global $wpdb;
		$tableName = self::get_table();
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `$tableName` (
				`id` INT NOT NULL AUTO_INCREMENT,
				`title`  VARCHAR(200) NOT NULL DEFAULT '',
				`rss_url` VARCHAR(200) NOT NULL DEFAULT '',
				PRIMARY KEY (`id`)
			) DEFAULT CHARACTER SET = utf8 DEFAULT COLLATE utf8_general_ci;"
		);
	}
	public static function installFixtures() {
		$fixture = new self( array(
				'title' => 'GitHub',
				'rss_url' => 'https://github.com/No3x.atom',
		));
		$fixture->save();
	}
} 