// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){


	$(window).load(function() {
		var r = Raphael('barometer', 140, 140), 
			circle = r.circle(70, 70, 60);

		circle.attr({ stroke: '#fff', 'stroke-width' : 10 });
	});

	$(document).ready(function() {

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove');
			$('#site-navigation').toggleClass('is-active');			
		});

		$('.campaign-button').on( 'click', function() {
			$(this).toggleClass('icon-remove');
			$(this).parent().toggleClass('is-active');
		});

	});

})( jQuery );