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
					if ( $(this).attr('placeholder') != null ) {
						$(this).val('');
						if ( $(this).val() !== $(this).attr('placeholder') ) {
							$(this).removeClass('hasPlaceholder');
						}
					}
				}).blur( function() {
					if ( $(this).attr('placeholder') != null && ($(this).val() === '' || $(this).val() === $(this).attr('placeholder'))) {
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

		if ( ($select).parent().hasClass('select-wrapper') ) {
			return toggleWrapper;
		}

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
		}, 

		fancySelect : function() {
			fancySelect();
		}
	};	
})( jQuery );