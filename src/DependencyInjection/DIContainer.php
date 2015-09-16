<?php
/**
 * User: No3x
 * Date: 14.09.15
 * Time: 18:52
 */

namespace No3x\WPFM\DependencyInjection;
use \Pimple\Container;

class DIContainer  extends Container {
	public function addActionsAndFilters() {
		foreach ( $this->keys() as $key ) {
			$content = $this[ $key ];
			if ( is_object( $content ) ) {
				$reflection = new \ReflectionClass( $content );
				if ( $reflection->hasMethod( 'addActionsAndFilters' ) ) {
					$content->addActionsAndFilters();
				}
			}
		}
	}
}