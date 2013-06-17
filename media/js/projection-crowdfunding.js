( function( $ ){

	// Start the countdown
	var $countdown = $('.countdown'), 
		enddate = $countdown.data().enddate;

	console.log( enddate );
	$countdown.countdown({until: new Date( enddate.year, enddate.month-1, enddate.day ), format: 'dHMs'});

	// Set up Raphael on window load event
	$(window).load(function() {
		var r = Raphael( $('.barometer')[0], 146, 146), 
			circle = r.circle(73, 73, 63);

		circle.attr({ stroke: '#fff', 'stroke-width' : 12 });
	});

	$(document).ready( function() {
		$('.campaign-button').on( 'click', function() {
			$(this).toggleClass('icon-remove');
			$(this).parent().toggleClass('is-active');
		});

		$('.edd_download_purchase_form').on('change', '.pledge-level', function() {
			$('input[name=projection_custom_price]').val( $(this).data().price );
		})
		.on('change', 'input[name=projection_custom_price]', function() {
			var pledge = $(this).val(), 
				$maxpledge;

			$('.pledge-level').each( function() {
				if ( $(this).data().price < pledge ) {
					$maxpledge = $(this);
				} 
				// This pledge's amount is greater than the amount set
				else {										
					return false;
				}
			});

			$maxpledge.attr('checked', true);
		});
	});	

})(jQuery);