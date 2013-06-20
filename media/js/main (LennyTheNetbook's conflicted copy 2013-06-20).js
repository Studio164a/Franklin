// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){

	var Sofa = function() {

		var self = this,
		methods = {

			// Ensure that browsers which don't support the placeholder attribute will 
			// still display the placeholder value as a preset value inside the element.
			crossBrowserPlaceholder : function() {
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
			dropdownMenus : function() {
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
			imageHovers : function() {
				$('.on-hover').each( function() {
					$(this).parent()
					.addClass('hover-parent')
					.on( 'mouseover', function() {
						$(this).addClass('hovering');
					})
					.on('mouseout', function() {
						$(this).addClass('hovering');
					});
				});
			},

			// Init routine to run when the Sofa object is instantiated.
			init : function() {

				var methods = self.methods;

				// Remove the no-js class from the html element
				$("html").removeClass('no-js');

				// Set up cross-browser placeholders
				methods.crossBrowserPlaceholder();

				// Dropdown menus
				methods.dropdownMenus();

				// Image hovers
				methods.imageHovers();
			}			
		};	

		// Initialize
		methods.init();	
	};

	$(document)
	// Initiate foundation
	.foundation()

	// Perform other actions on ready event
	.ready(function() {

		var Sofa = new Sofa;

		// Sofa.crossBrowserPlaceholder();
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