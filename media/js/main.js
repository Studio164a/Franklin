// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){	

	// Start Foundation.
	Foundation.global.namespace = '';

	$(document).foundation();
	
	// Perform other actions on ready event
	$(document).ready( function() {

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
			urlCurl: Franklin.sharrre_url,
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
			urlCurl: Franklin.sharrre_url,
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
			urlCurl: Franklin.sharrre_url,
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
			urlCurl: Franklin.sharrre_url,
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
			urlCurl: Franklin.sharrre_url,
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

		if ( Franklin.using_crowdfunding ) {

			Franklin.Countdown.init();		

			Franklin.Pledging.init();

			$('.campaign-button').on( 'click', function() {
				$(this).toggleClass('icon-remove');
				$(this).parent().toggleClass('is-active');
			});

			$('[name=shipping_country], [name=shipping_state_ca], [name=shipping_state_us]').on( 'change', Sofa.toggleSelectWrapper($(this)))

			$('.atcf-multi-select .children').hide();
			$('.atcf-multi-select input[type="checkbox"]').on( 'change', function() {
				var parent_category = $(this).parent().parent('li'), 
					child = parent_category.children('.children');
				if ( $(this).attr("checked") ) {
					child.show();
					if( child.length > 0 ) {
						parent_category.addClass("selected");
					}
				} else {
					child.hide();
					parent_category.removeClass("selected");
					parent_category.find('input[type="checkbox"]').prop('checked', false);
				}
			});
		}	
	});

	audiojs.events.ready(function() {
    	var as = audiojs.createAll();
  	});

  	$(window).resize( function() {
  		Sofa.responsiveHide();

  		if ( Franklin.using_crowdfunding ) {
  			Franklin.Grid.resizeGrid();
  		}
  	});

  	$(window).load( function() {
  		if ( Franklin.using_crowdfunding ) {
  			Franklin.Grid.init();
			Franklin.Barometer.init();
  		}
  	})

})( jQuery );