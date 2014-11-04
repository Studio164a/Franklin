// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){	

	$(document)
	// Initiate foundation
	.foundation()

	// Perform other actions on ready event
	.ready(function() {

		Sofa.init();

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove'); 
			$(this).parent().toggleClass('is-active');			
		});

		$('.accordion').accordion({
			heightStyle: "content"
		});
		
		// Load up lightbox
		if ( typeof sofa_ie_lt9 === 'undefined' ) {
			// $(".entry a").not(".attachment,.tiled-gallery-item a").has('img').attr('data-rel', 'lightbox[]');
			$('.lightbox').attr('data-rel', 'lightbox[]');
			$("[data-rel^='lightbox']").prettyPhoto({ theme: 'pp_sofa', hook: 'data-rel' });
		}				

		$('.share-twitter').sharrre({
			urlCurl: Sofa_Localized.sharrre_url,
			share: {				
				twitter: true
			},
			enableHover : false, 
			enabledTracking : true, 
			buttons : { twitter: { via: '' } }, 
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('twitter');
			}
		});	
		$('.share-facebook').sharrre({
			urlCurl: Sofa_Localized.sharrre_url,
			share: {				
				facebook: true
			},
			enableHover : false, 
			enabledTracking : true, 
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('facebook');
			}
		});	
		$('.share-googleplus').sharrre({
			urlCurl: Sofa_Localized.sharrre_url,
			share: {				
				googlePlus: true
			},
			enableHover : false, 
			enabledTracking : true, 
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('googlePlus');
			}
		});		
		$('.share-linkedin').sharrre({
			urlCurl: Sofa_Localized.sharrre_url,
			share: {				
				linkedin: true
			},
			enableHover : false, 
			enabledTracking : true, 
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('linkedin');
			}
		});	
		$('.share-pinterest').sharrre({
			urlCurl: Sofa_Localized.sharrre_url,
			share: {				
				pinterest: true
			},
			enableHover : false, 
			enabledTracking : true, 
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('pinterest');
			}
		});		
	});

	audiojs.events.ready(function() {
    	var as = audiojs.createAll();
  	});

  	$(window).resize( function() {
  		Sofa.responsiveHide();
  	});

})( jQuery );