var Sofa = ( function( $ ) {

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
		$('.menu li')
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
	}, 

	// Hide/show elements responsive
	responsiveHide = function() {
		// IE8 and below are dished up the mobile version, so this is always applied to them		
		$('body').toggleClass('is-tiny', $(window).width() < 600 || $('html').hasClass('lt-ie9') );
	}, 

	// Wraps select elements in a wrapper class
	fancySelect = function() {
		var $select = $('select'), 
			toggleWrapper = function($el) {
				$el.parent().css('display', $el.css('display'))
			};

		$select.wrap('<div class="select-wrapper" />')
		.on('change', function() {
			toggleWrapper($(this))
		});

		$select.each( function() {
			toggleWrapper($(this)); 
		});

		return toggleWrapper;
	};		

	return {			

		init : function() {
			// Remove the no-js class from the html element
			$("html").removeClass('no-js');

			// Set up cross-browser placeholders
			crossBrowserPlaceholder();

			// Dropdown menus
			dropdownMenus();

			// Image hovers
			imageHovers();

			responsiveHide();

			// Fancy select
			fancySelect();
		}, 

		responsiveHide : function() {
			responsiveHide();
		}, 

		toggleSelectWrapper: function($el) {
			fancySelect($el)
		} 
	};	
})( jQuery );

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
			$(".entry a").not(".attachment,.tiled-gallery-item a").has('img').attr('data-rel', 'lightbox[]');
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
  	})

})( jQuery );