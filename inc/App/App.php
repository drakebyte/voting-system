<?php

namespace CodingTest\App;

use CodingTest\Core\Ajax_AddVoting;
use CodingTest\Core\VotingSystem;
use CodingTest\Helpers\Traits\Singleton;
use CodingTest\Settings\EnqueueAssets;

/**
 * Main singleton
 * store all kinds of variables that are otherwise only accessed through global
 * gain performance
 * avoid having to repeat same db query functions in multiple locations
 */
class App {
	use Singleton;

	public VotingSystem   $VotingSystem;
	public EnqueueAssets  $EnqueueAssets;
	public Ajax_AddVoting $Ajax_AddVoting;

	public function __construct() {
		$this->VotingSystem   = new VotingSystem;
		$this->EnqueueAssets  = new EnqueueAssets;
		$this->Ajax_AddVoting = new Ajax_AddVoting;
	}

}
