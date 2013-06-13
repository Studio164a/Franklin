( function( $ ){

	// Start the countdown
	var $countdown = $('.countdown'), 
		enddate = $countdown.data().enddate;

	console.log( enddate );
	$countdown.countdown({until: new Date( enddate.year, enddate.month-1, enddate.day ), format: 'dHMs'});

	// Set up Raphael on window load event
	$(window).load(function() {
		var r = Raphael( $('.barometer')[0], 200, 200), 
			circle = r.circle(100, 100, 90);

		circle.attr({ stroke: '#fff', 'stroke-width' : 14 });
	});

	$(document).ready( function() {
		$('.campaign-button').on( 'click', function() {
			$(this).toggleClass('icon-remove');
			$(this).parent().toggleClass('is-active');
		});
	});	

})(jQuery);