<?php

namespace CodingTest\Core;

class VotingSystem {

	public string $user_ip;

	/**
	 * Constructor for the VotingSystem class.
	 * Initializes the user IP and sets up hooks for content display and meta box addition.
	 */
	public function __construct() {
		$this->get_user_ip();

		add_filter( 'the_content', [ $this, 'posts_show_voting' ], 200 );
		add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box' ] );
	}

	/**
	 * Retrieves the user's IP address and stores a hashed version for GDPR compliance.
	 */
	private function get_user_ip(): void {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->user_ip = md5( $ip ); // GDPR concerns
	}

	/**
	 * Appends the voting interface to the post content.
	 *
	 * @param string $content The original content of the post.
	 *
	 * @return string The content appended with the voting HTML.
	 */
	public function posts_show_voting( $content ) {
		global $post;
		if ( get_post_type( $post ) === 'post' && is_single() ) {
			$post_id     = $post->ID;
			$voting_data = [
				'post_id'          => $post_id,
				'already_voted'    => false,
				'voted_up'         => false,
				'voted_down'       => false,
				'voted_up_label'   => __('YES', 'test-plugin-i18n'),
				'voted_down_label' => __('NO', 'test-plugin-i18n'),
			];

			// Check if a cookie exists and contains the post ID
			if ( isset( $_COOKIE['user_votes'] ) ) {
				$user_votes = unserialize( stripslashes( $_COOKIE['user_votes'] ), [ "allowed_classes" => false ] );
				$count      = $this->get_votes_count( $post_id );
				if ( is_array( $user_votes ) && array_key_exists( $post_id, $user_votes ) ) {
					$voting_data['already_voted']    = true;
					$voting_data['voted_up']         = ( $user_votes[ $post_id ] === 'upvote' );
					$voting_data['voted_down']       = ( $user_votes[ $post_id ] === 'downvote' );
					$voting_data['voted_up_label']   = $count['upvote']['percentage'] . ' %';
					$voting_data['voted_down_label'] = $count['downvote']['percentage'] . ' %';
					$voting_data['type']             = $user_votes[ $post_id ];
				}
			}

			ob_start();
			include( CODINGTEST_PLUGIN_DIR . '/templates/parts/voting.php' );
			$voting_html = ob_get_clean();

			// Apply filters and pass both $voting_html and $voting_data to it
			$voting_html = apply_filters( 'codingtest_voting_html', $voting_html, $voting_data );

			return $content . $voting_html;
		}

		return $content;
	}

	/**
	 * Calculates the vote counts and percentages for a given post.
	 *
	 * @param int $post_id The ID of the post.
	 *
	 * @return array An associative array containing vote counts and percentages for upvotes and downvotes.
	 */
	public function get_votes_count( $post_id ): array {
		$votes['upvote']['value']   = $this->get_vote_count( $post_id, 'upvote' );
		$votes['downvote']['value'] = $this->get_vote_count( $post_id, 'downvote' );

		$total_votes = $votes['upvote']['value'] + $votes['downvote']['value'];

		if ( $total_votes > 0 ) {
			$votes['upvote']['percentage']   = round( ( $votes['upvote']['value'] / $total_votes ) * 100, 0 );
			$votes['downvote']['percentage'] = round( ( $votes['downvote']['value'] / $total_votes ) * 100, 0 );
		} else {
			$votes['upvote']['percentage']   = 0;
			$votes['downvote']['percentage'] = 0;
		}

		return $votes;
	}

	/**
	 * Retrieves the count of a specific type of vote (upvote or downvote) for a post.
	 *
	 * @param int    $post_id The ID of the post.
	 * @param string $type    The type of vote to count ('upvote' or 'downvote').
	 *
	 * @return int The number of votes of the specified type.
	 */
	public function get_vote_count( $post_id, $type ): int {
		$vote_count = get_post_meta( $post_id, 'vote_' . $type, true );
		if ( empty( $vote_count ) ) {
			return 0;
		}

		return $vote_count;
	}

	/**
	 * Adds a custom meta box to the post edit screen to display vote counts.
	 *
	 * @param string $post_type The type of post being edited.
	 */
	public function add_custom_meta_box( $post_type ) {
		$post_types = [ 'post' ];

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'votes_on_this_post',
				__( 'Votes on this post', 'test-plugin-i18n' ),
				[ $this, 'show_votes_on_this_post' ],
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * Displays the vote counts on the post edit screen inside the custom meta box.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function show_votes_on_this_post( $post ): void {
		$votes = $this->get_votes_count( $post->ID );

		$upvoteCount   = $votes['upvote']['value'];
		$downvoteCount = $votes['downvote']['value'];

		$upvotePercentage   = isset( $votes['upvote']['percentage'] ) ? $votes['upvote']['percentage'] . '%' : '0%';
		$downvotePercentage = isset( $votes['downvote']['percentage'] ) ? $votes['downvote']['percentage'] . '%' : '0%';

		echo "<p>Upvotes: {$upvoteCount} ({$upvotePercentage})<br/>Downvotes: {$downvoteCount} ({$downvotePercentage})</p>";
	}

	/**
	 * Processes a vote from a user for a specific post.
	 *
	 * @param int    $post_id The ID of the post being voted on.
	 * @param string $type    The type of vote ('upvote' or 'downvote').
	 *
	 * @return bool True if the vote was successfully added, false otherwise.
	 */
	public function add_vote( $post_id, $type ) {
		$vote_ip_list = $this->can_user_vote( $post_id );
		if ( $vote_ip_list === false ) {
			return false;
		}
		$vote_ip_list[] = $this->user_ip;
		update_post_meta( $post_id, 'vote_ip_list', $vote_ip_list );
		$vote_count = $this->get_vote_count( $post_id, $type );
		$vote_count ++;
		update_post_meta( $post_id, 'vote_' . $type, $vote_count );

		// Handle the cookie for user votes
		$user_votes = [];
		if ( isset( $_COOKIE['user_votes'] ) ) {
			$user_votes = unserialize( stripslashes( $_COOKIE['user_votes'] ), [ "allowed_classes" => false ] );
		}
		$user_votes[ $post_id ] = $type;
		setcookie( 'user_votes', serialize( $user_votes ), time() + ( 86400 * 365 ), "/" );

		return true;
	}

	/**
	 * Checks whether the current user has already voted on a post.
	 *
	 * @param int $post_id The ID of the post to check.
	 *
	 * @return mixed An array of IPs that have voted on the post if the user can vote, false otherwise.
	 */
	public function can_user_vote( $post_id ) {
		$vote_ip_list = get_post_meta( $post_id, 'vote_ip_list', true );
		if ( empty( $vote_ip_list ) ) {
			return [];
		}
		if ( ! in_array( $this->user_ip, $vote_ip_list ) ) {
			return $vote_ip_list;
		}

		return false;
	}

}
