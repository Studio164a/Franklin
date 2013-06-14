// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){

	$(document)
	// Initiate foundation
	.foundation()

	// Perform other actions on ready event
	.ready(function() {

		// $(".twitter").sharrre({
		// 	share: {
		// 		twitter: true
		// 	},
		// 	enableHover: false,
		// 	enableTracking: true,
		// 	buttons: { twitter: {via: '_JulienH'}},
		// 	click: function(api, options){
		// 		api.simulateClick();
		// 		api.openPopup('twitter');
		// 	}
		// });
		// $(".facebook").sharrre({
		// 	share: {
		// 		facebook: true
		// 	},
		// 	enableHover: false,
		// 	enableTracking: true,
		// 	click: function(api, options){
		// 		api.simulateClick();
		// 		api.openPopup('facebook');
		// 	}
		// });
		// $(".googleplus").sharrre({
		// 	share: {
		// 		googlePlus: true
		// 	},
		// 	enableHover: false,
		// 	urlCurl: '/wp-content/themes/projection/inc/sharrre.php',
		// 	enableTracking: true,
		// 	click: function(api, options){
		// 		api.simulateClick();
		// 		api.openPopup('googlePlus');
		// 	}
		// });		

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove');
			$('#site-navigation').toggleClass('is-active');			
		});
	});

})( jQuery );