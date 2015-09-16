<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.15
 * Time: 20:03
 */

namespace No3x\WPFM\Core;
use  No3x\WPFM\Core\MetaParser;

class PluginMeta {

	/**
	 * @var MetaParser
	 */
	private $metaParser;

	private $path;
	private $uri;
	private $display_name;
	private $slug;
	private $main_file;
	private $description;
	private $version;
	private $version_installed;
	private $author_name;
	private $author_uri;
	private $wp_uri;
	private $support_uri;
	private $license;

	public function __construct( MetaParser $metaParser ) {
		$this->metaParser = $metaParser;

		$this->path = realpath( plugin_dir_path( $metaParser->getMainPluginFileName() ) ) . DIRECTORY_SEPARATOR;
		$this->uri = plugin_dir_url( $metaParser->getMainPluginFileName() );
		//$this->display_name = $plugin->getPluginDisplayName();
		//$this->slug = $plugin->getPluginSlug();
		$this->main_file = $metaParser->getMainPluginFileName();
		$this->description = $metaParser->getValue( 'Description' );
		$this->version = $metaParser->getValue( 'Version' );
		//$this->version_installed = $plugin->getVersionSaved();
		$this->author_name = $metaParser->getValue( 'Author' );
		$this->author_uri = $metaParser->getValue( 'Author URI' );
		$this->wp_uri = $metaParser->getValue( 'Plugin URI' );
		$this->support_uri = $metaParser->getValue( 'Support URI' );
		$this->license = $metaParser->getValue( 'License' );
	}

	/**
	 * @return mixed
	 */
	public function getAuthorName()
	{
		return $this->author_name;
	}

	/**
	 * @return mixed
	 */
	public function getAuthorUri()
	{
		return $this->author_uri;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getDisplayName()
	{
		return $this->display_name;
	}

	/**
	 * @return mixed
	 */
	public function getLicense()
	{
		return $this->license;
	}

	/**
	 * @return mixed
	 */
	public function getMainFile()
	{
		return $this->main_file;
	}

	/**
	 * @return mixed
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @return mixed
	 */
	public function getSupportUri()
	{
		return $this->support_uri;
	}

	/**
	 * @return mixed
	 */
	public function getUri()
	{
		return $this->uri;
	}

	/**
	 * @return mixed
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @return mixed
	 */
	public function getVersionInstalled()
	{
		return $this->version_installed;
	}

	/**
	 * @return mixed
	 */
	public function getWpUri()
	{
		return $this->wp_uri;
	}


} 