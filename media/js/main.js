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
				$(this).parent()
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

	// console.log( Sofa );

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
	});

	audiojs.events.ready(function() {
    	var as = audiojs.createAll();
  	});

})( jQuery );