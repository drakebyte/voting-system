jQuery(document).ready(function ($) {

	$('.user-can-vote .vote-button').each(function () {
		var button = $(this);
		var triggerEvent = button.data('event') || 'click';  // Set default event to 'click'

		button.on(triggerEvent, function () {

			if (!button.hasClass('disabled')) {

				var votingData = $(this).data();

				$.ajax({
					url: wordpressplugincodingtest.ajax_url, // WordPress AJAX URL
					type: 'POST',
					data: {
						action: 'add_vote',
						votingData: votingData
					},
					success: function (response) {
						if (response.success === true) {
							button.addClass('selected');
						} else {
							console.log('Already voted. Updating results.');
						}
						button.parent('.user-can-vote').removeClass('user-can-vote');

						// Round percentages and update labels
						var upvotePercentage = Math.round(response.count.upvote.percentage);
						var downvotePercentage = Math.round(response.count.downvote.percentage);
						$('.vote-label-upvote').html(upvotePercentage + ' %');
						$('.vote-label-downvote').html(downvotePercentage + ' %');
						$('.vote-introtext').text('THANK YOU FOR YOUR VOTE');
						$('.vote-button').addClass('disabled');

						console.log(response);
					}
				});
			} else {
				console.log('Already voted');
			}
		});
	});
});

