/*global jQuery */
/*!	
* FlexNav.js 0.3
*
* Copyright 2012, Jason Weaver http://jasonweaver.name
* Released under the WTFPL license 
* http://sam.zoy.org/wtfpl/
*
* Date: Sunday July 8
*/

(function($) {
	$.fn.flexNav = function(options) {
	    var settings = $.extend({
		        'breakpoint': '800',
		        'animationSpeed': 'fast',
		        'toggleCallback' : function($button){}
		    },
		    options),
	    is_small = false,	    
		$this = $(this);

	    var resizer = function() {
	        if ($(window).width() < settings.breakpoint && is_small === false) {
	        	is_small = true;
	            $("body").removeClass("lg-screen").addClass("sm-screen");
	            $this.find('a').on('click', function() {
	            	$this.hide();
	            });
	        }
	        else if ( $(window).width() > settings.breakpoint && is_small === true) {
	        	is_small = false;
	            $("body").removeClass("sm-screen").addClass("lg-screen");	            
	        }
	        if ($(window).width() >= settings.breakpoint) {	        	
	            $this.show();
	        }
	    };

	    // Call once to set.
	    resizer();

	    // Function for testing touch screens
	    function is_touch_device() {
	        return !! ('ontouchstart' in window);
	    }

	    // Set class on html element for touch/no-touch
	    if (is_touch_device()) {
	        $('html').addClass('flexNav-touch');
	    } else {
	        $('html').addClass('flexNav-no-touch');
	    }

	    // Toggle for nav menu
	    $('.menu-button').click(function() {
	    	var $button = $(this);
	        $this.slideToggle(settings.animationSpeed, settings.toggleCallback.call($this, $button));
	    });
	
	    // Toggle click for sub-menus on touch and or small screens
	    $('.item-with-ul').click(function() {
	        $(this).find('.sub-menu').slideToggle(settings.animationSpeed);
	    });

	    // Call on resize.
	    $(window).on('resize', resizer);

	};

})(jQuery);