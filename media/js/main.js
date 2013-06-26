// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){

	var Sofa = ( function() {

		var self = this,
		
		// Ensure that browsers which don't support the placeholder attribute will 
		// still display the placeholder value as a preset value inside the element.
		crossBrowserPlaceholder = function() {
			var $form_elements = $(':text,textarea');

			// Make sure there are text inputs
			if ( $form_elements.length ) {

				// Only proceed if placeholder isn't supported
				if ( ! ( 'placeholder' in $form_elements.first()[0] ) ) {
					var active = document.activeElement;

					$form_elements.focus( function() {
						if ( $(this).attr('placeholder') !== '' && $(this).val() === $(this).attr('placeholder') ) {
							$(this).val('').removeClass('hasPlaceholder');
						}
					}).blur( function() {
						if ( $(this).attr('placeholder') !== '' && ($(this).val() === '' || $(this).val() === $(this).attr('placeholder'))) {
							$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
						}
					});
					$form_elements.blur();
					$(active).focus();
					$('form').submit(function () {
						$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
					});
				}
			}
		}, 

		// Dropdown menus
		dropdownMenus = function() {
			$('#site-navigation li')
			.each( function() {
				if ( $(this).find('li').length ) {
					$(this).addClass('has-sub');
				}
			})
			.on('mouseover', function() {
				$(this).addClass('hovering');
			})
			.on('mouseout', function() {
				$(this).removeClass('hovering');
			});
		},

		// Image hover effects
		imageHovers = function() {
			$('.on-hover').each( function() {
				var $parent = $(this).parent(), 
					$image = $parent.find('img');

				// Set the width and offset of the hover to match the image
				$(this).css({ width : $image.width(), left : $image.position().left });
				
				// Set up the parent, along with its event handlers
				$parent
				.addClass('hover-parent')
				.on( 'mouseover', function() {
					$(this).addClass('hovering');
				})
				.on('mouseout', function() {
					$(this).removeClass('hovering');
				});
			});
		};
	
		return ( function() {

			// Remove the no-js class from the html element
			$("html").removeClass('no-js');

			// Set up cross-browser placeholders
			crossBrowserPlaceholder();

			// Dropdown menus
			dropdownMenus();

			// Image hovers
			imageHovers();

			return {};
		})();	
	});

	$(document)
	// Initiate foundation
	.foundation()

	// Perform other actions on ready event
	.ready(function() {

		var s = Sofa();	

		$('.menu-button').on( 'click', function() {			
			$(this).children().toggleClass('icon-th-list').toggleClass('icon-remove');
			$('#site-navigation').toggleClass('is-active');			
		});

		$('.accordion').accordion({
			heightStyle: "content"
		});

		// Load up lightbox
		$(".entry a").not(".attachment,.tiled-gallery-item a").has('img').attr('data-rel', 'lightbox[]');
		$("[data-rel^='lightbox']").prettyPhoto({ theme: 'pp_sofa', hook: 'data-rel' });

		$('.share-twitter').sharrre({
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
	});

	audiojs.events.ready(function() {
    	var as = audiojs.createAll();
  	});

})( jQuery );