<?php

namespace CodingTest\Helpers\Traits;

trait Singleton {
	private static $instance;

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new static();
		}

		return self::$instance;
	}
}
