<div class="voting-container <?php echo $voting_data['already_voted'] ? '' : 'user-can-vote'; ?>">
	<p class="vote-introtext">
	<?php if ($voting_data['already_voted']):
		echo __('THANK YOU FOR YOUR VOTE', 'test-plugin-i18n');
	else:
		echo __('WAS THIS ARTICLE HELPFUL?', 'test-plugin-i18n');
	endif; ?>
	</p>
	<div class="voting-buttons">
		<?php
		// Function to determine button class
		function getButtonClass($voted, $type) {
			return 'vote-button vote-' . $type . ($voted ? ' selected' : '');
		}
		?>
		<button class="<?php echo getButtonClass($voting_data['voted_up'], 'yes'); ?>" data-votingtype="upvote" data-postid="<?php echo $voting_data['post_id']; ?>">
			😊 <span class="vote-label vote-label-upvote"><?php echo $voting_data['voted_up_label']; ?></span>
		</button>
		<button class="<?php echo getButtonClass($voting_data['voted_down'], 'no'); ?>" data-votingtype="downvote" data-postid="<?php echo $voting_data['post_id']; ?>">
			☹️ <span class="vote-label vote-label-downvote"><?php echo $voting_data['voted_down_label']; ?></span>
		</button>
	</div>
</div>
