<?php

namespace CodingTest\Core;

use CodingTest\Ajax\AdminAjax;

class Ajax_AddVoting extends AdminAjax {

	public function create_output(): array {
		$success    = false;
		$votingData = $_POST['votingData'];

		$addvote = CodingTest()->VotingSystem->add_vote( $votingData['postid'], $votingData['votingtype'] );
		if ( $addvote ) {
			$success = true;
		}
		$count = CodingTest()->VotingSystem->get_votes_count( $votingData['postid'] );

		$output = [
			'success' => $success,
			'status'  => 200,
			'debug'   => $votingData,
			'addvote' => $addvote,
			'count'   => $count,
		];

		return $output;
	}

	public function set_hook_name(): string {
		return 'add_vote';
	}

	public function add_construct(): void {}
}
