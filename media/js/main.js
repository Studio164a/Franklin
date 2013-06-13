// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){

	$(document)
	// Initiate foundation
	.foundation()

	// Perform other actions on ready event
	.ready(function() {

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove');
			$('#site-navigation').toggleClass('is-active');			
		});
	});

})( jQuery );