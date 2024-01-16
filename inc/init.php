<?php

/**
 * Initialise global values, constants, etc
 */

/*
 * We initialise this plugin's class as a singleton so we have everything at hand without having to repeat queries
 */
function CodingTest(): \CodingTest\App\App {
	static $instance = null;
	if ( $instance === null ) {
		$instance = \CodingTest\App\App::instance();
	}

	return $instance;
}

CodingTest();
