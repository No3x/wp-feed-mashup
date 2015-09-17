<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16.09.15
 * Time: 21:35
 */

namespace No3x\WPFM\Template;
use No3x\WPFM\Twig\TwigExtensions;
use No3x\WPFM\Model\FeedItem;


class Feed {

	public static function render( $feed, $feedItems, $attributes = null ) {
		$loader = new \Twig_Loader_Filesystem( plugin_dir_path( __FILE__));
		$twig = new \Twig_Environment($loader, array( 'debug' => true ) );
		$twig->addExtension( new TwigExtensions() );
		$twig->addExtension( new \Twig_Extension_Debug() );
		foreach( $feedItems as $feedItem ) {
			/* @var $feedItem FeedItem */
			$feedItem->icon = 'fa fa-github';
			$feedItem->source = $feed->get_title();
		}
		$options = array(
			'default_icon' => 'fa fa-quote-left',
			'first_active' => true,
		);
		return $twig->render('Feed.twig', array(
			'feedItems' => $feedItems,
			'options' => $options
		) );
	}
} 