( function( $ ){	 

	Sofa.Barometer = ( function() {

		// Barometers collection
		var $barometers = $('.barometer'), 

		// Check whether the element is in view
		isInView = function($el) {
		    var docViewTop = $(window).scrollTop(), 
		    	docViewBottom = docViewTop + $(window).height(), 
				elemTop = $el.offset().top,
				elemBottom = elemTop + $el.height();

		    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
	  			&& (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
		}, 

		// A custom arc to apply to the barometer's paths.
		// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
		customArc = function (xloc, yloc, value, total, R) {
			var alpha = 360 / total * value,
				a = (90 - alpha) * Math.PI / 180,
				x = xloc + R * Math.cos(a),
				y = yloc - R * Math.sin(a),
				path;

			if (total == value) {
				path = [
					["M", xloc, yloc - R],
					["A", R, R, 0, 1, 1, xloc - 0.01, yloc - R]
				];
			} else {
				path = [
					["M", xloc, yloc - R],
					["A", R, R, 0, +(alpha > 180), 1, x, y]
				];
			}
			return {
				path: path
			};
		}

		// Draws a barometer
		drawBarometer = function($barometer, r, width, height, progress_val) {			
			var progress;

			// Draw the percentage filled arc
			progress = r.path().attr({ 
				stroke: $barometer.data('progress-stroke'), 
				'stroke-width' : $barometer.data('strokewidth')+1, 
				arc: [width/2, height/2, 0, 100, (width/2)-8]
			});

			// Animate it
			progress.animate({
				arc: [width/2, height/2, progress_val, 100, (width/2)-8]
			}, 1500, "easeInOut", function() {
				$barometer.find('span').animate( { opacity: 1}, 300, 'linear');
			});
		}, 

		// Init barometer
		initBarometer = function($barometer) {
			var width = $barometer.data('width'), 
				height = $barometer.data('height'),					
				r = Raphael( $barometer[0], width, height),
				drawn = false,							
				progress_val = $barometer.data('progress'),
				circle;

			// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
			r.customAttributes.arc = customArc;

			// Draw the main circle
			circle = r.path().attr({
				stroke: $barometer.data('stroke'), 
				'stroke-width' : $barometer.data('strokewidth'), 
				arc: [width/2, height/2, 0, 100, (width/2)-8]
			});

			// Fill the main circle
			$barometer.parent().addClass('barometer-added');
			circle.animate({ arc: [width/2, height/2, 100, 100, (width/2)-8] }, 1000, function() {
				if ( progress_val === 0 ) {
					$barometer.find('span').animate( { opacity: 1}, 500, 'linear' );
				}					
			});

			if ( isInView($barometer) ) {
				drawBarometer($barometer, r, width, height, progress_val);

				drawn = true;
			}
			else {
				$(window).scroll( function() {
					if ( drawn === false && isInView($barometer) ) {
						drawBarometer($barometer, r, width, height, progress_val);

						drawn = true;
					}
				});
			}
		};

		return {

			init : function() {

				$barometers.each( function() {
					initBarometer( $(this) );
				});					

			},

			getBarometers : function() {
				return $barometers;
			}

		}

	})();

	Sofa.Countdown = ( function() {

		var 
		// Start the countdown script
		startCountdown = function() {
			var $countdown = $('.countdown'), 
				enddate;

			if ($countdown.length) {
				enddate = $countdown.data().enddate;

				$countdown.countdown({until: new Date( enddate.year, enddate.month-1, enddate.day ), format: 'dHMs'});
			}		

			return $countdown;
		}

		return {

			init : function() {
				startCountdown();
			}
			
		};

	})();	

	Sofa.Grid = ( function() {

		var $grids = $('.masonry-grid');

		initGrid = function($grid) {
			$grid.masonry();
		};

		return {

			init : function() {

				if ( $(window).width() > 400 ) {
					$grids.each( function() {
						initGrid( $(this) );
					});
				}
							
			}, 

			getGrids : function() {
				return $grids;
			}, 

			resizeGrid : function() {
				$grids.each( function(){
					initGrid( $(this) );
				})
			}			
		}

	})();

	// Set up Raphael on window load event
	$(window).load(function() {
		Sofa.Grid.init();
		Sofa.Barometer.init();

	}). 
	resize( function() {
		Sofa.Grid.resizeGrid();
	})

	$(document).ready( function() {

		Sofa.Countdown.init();		

		$('.campaign-button').on( 'click', function() {
			$(this).toggleClass('icon-remove');
			$(this).parent().toggleClass('is-active');
		});

		$('.pledge-button').on( 'click', function() {
			var price = $(this).data('price');
			
			$('.edd_download_purchase_form').find('[data-price='+$(this).data('price')+'] input').prop('checked', true).trigger('change');
		});

		$('.edd_download_purchase_form').on('change', '.pledge-level', function() {
			$('input[name=franklin_custom_price]').val( $(this).data().price );
		})
		.on('change', 'input[name=franklin_custom_price]', function() {
			var pledge = $(this).val(), 
				$minpledge = $('.edd_download_purchase_form .pledge-level').first(),				
				$maxpledge;

			// The pledge has to equal or exceed the minimum pledge amount
			if ( $minpledge.data('price') > pledge ) {

				// Explain that the pledge has to be at least the minimum
				alert( PROJECTION.messages.need_minimum_pledge );

				// Select the minimum pledge amount
				$minpledge.find('input').prop('checked', true);
				$minpledge.change();

				// Exit
				return;
			}

			$('.edd_download_purchase_form .pledge-level').each( function() {

				if ( $(this).data('price') <= pledge && $(this).hasClass('not-available') === false ) {
					$maxpledge = $(this);
				} 
				// This pledge's amount is greater than the amount set
				else {										
					return false;
				}
			});

			// Select the maximum pledge
			$maxpledge.find('input').prop('checked', true);
		});
	});	

})(jQuery);