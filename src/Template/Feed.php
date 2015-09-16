<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16.09.15
 * Time: 21:35
 */

namespace No3x\WPFM\Template;


class Feed {

	public static function render( $data ) {
		$loader = new \Twig_Loader_Filesystem( plugin_dir_path( __FILE__));
		$twig = new \Twig_Environment($loader, array( 'debug' => true ) );
		$twig->addExtension( new \Twig_Extension_Debug() );
		$feeds[] = array(
			'id' => '12',
			'timestamp' => human_time_diff( time () ),
			'source' => 'GitHub',
			'title' => 'My First Feed',
			'content' => 'My First Feed Content',
			'icon' => 'fa fa-github',
		);
		$feeds[] = array(
			'id' => '17',
			'timestamp' => human_time_diff(  time ()  ),
			'source' => 'Twitter',
			'title' => 'Twitter Feed',
			'content' => 'Twitter Feed Content',
		);
		$options = array(
			'default_icon' => 'fa fa-quote-left',
			'first_active' => true,
		);
		return $twig->render('Feed.twig', array( 'feeds' => $feeds, 'options' => $options ) );
	}
} 