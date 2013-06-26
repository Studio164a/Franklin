/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
( function( $ ) {	

	var

	updateAccentColour = function(value) {
		$('a:not(.button, #site-navigation a, .social a), #site-navigation .menu-button, .block-title, .widget-title, .page-title, .post-title, .pledge-level.not-available .pledge-limit, .post-author i, .button.accent.button-alt, .hovering .on-hover').css('color', value);
		$('.campaign-button, .active-campaign, .sticky, .button.accent').css('background-color', value);
		$('.button.accent').css('boxShadow', '0 0 0 0.3rem ' + value);
		$('#site-navigation .hovering > a, .button.accent.button-alt').css('border-color', value);
	},

	updateAccentHover = function(value) {
		$('.sticky .post-title, .barometer .filled, .button.accent, .active-campaign .campaign-image').css('border-color', value);
		$('.active-campaign .campaign-image').css('boxShadow', '0 0 3px 1px ' + value);
	},

	updateAccentText = function(value) {
		$('.campaign-button, .active-campaign, .sticky, .button.accent').css('color', value);
	}, 

	updateBodyBackground = function(value) {
		$('body, .audiojs .loaded').css('background-color', value);
		$('.audiojs .play-pause').css('border-right-color', value);
	}, 

	updateBodyText = function(value) {
		$('body, .with-icon:before, .icon, .widget_search #searchsubmit::before, .button.button-alt, #site-navigation a, .block-title.with-icon i, .meta a, .format-status .post-title, .countdown_holding span').not('.account-links a').css('color', value)
		$('.footer-widget .widget-title').css('text-shadow', '0 1px 0 ' + value);
		$('.button.button-alt, .shadow-wrapper::before, .shadow-wrapper::after').not('.account-links a').css('border-color', value);
		$('input[type=submit], input[type=reset], button, .button:not(.account-links a), .audiojs').css('background-color', value);
		$('input[type=submit], input[type=reset], button, .button:not(.account-links a)').css('boxShadow', '0 0 0 3px ' + value);
	},

	updateButtonText = function(value) {
		$('input[type=submit], input[type=reset], button, .button, .active-campaign .campaign-button, #site-navigation .menu-button, .sticky.block, .sticky.block a').css('color', value);
		$('.campaign-support').css('boxShadow', '0 0 0 3px' + value);
	},
	
	updateWrapperBackground = function (value) {
		$('#main, #header, #site-navigation ul, .even td, .widget td, .widget input[type=text], .widget input[type=password], .widget input[type=email], .widget input[type=number]').css('background-color', value);
	},

	updatePostsBackground = function (value) {
		$('.entry-block, .content-block, .reveal-modal.multi-block .content-block, .widget th, .widget tfoot td, .format-status .meta, .format-quote .entry blockquote, .audiojs .progress, .comments-section, .campaign-pledge-levels.accordion .pledge-level').css('background-color', value);
		$('.entry-block').css('boxShadow', '0 0 1px ' + value);
		$('.sticky.block').css('border-color', value);
	},

	updateWidgetBackground = function (value) {
		$('input[type=text], input[type=password], input[type=number], input[type=email], textarea, .featured-image, th, .entry blockquote, hr, pre, .meta, .audiojs .scrubber, .widget, .sidebar-block, .accordion h3').css('background-color', value);
	},

	updatePrimaryBorder = function (value) {
		$('#header, .widget_search #s, #site-navigation li, .block, .page-title, .block-title, .post-title, .meta, .meta .author, .meta .comment-count, .meta .tags, .comment, .pingback, .widget, .campaign-pledge-levels.accordion h3, .campaign-pledge-levels.accordion .pledge-level, .multi-block .content-block:nth-of-type(1n+2), #edd_checkout_form_wrap legend, table, td, th').css('border-color', value);
	},

	updateSecondaryBorder = function (value) {
		$('th').css('border-right-color', value);
		$('#site-navigation ul').css('border-top-color', value);
		$('.widget-title').css('border-color', value);
	},

	updateMetaColour = function (value) {
		$('.meta, .comment-meta, .pledge-limit').css('color', value);
	},

	updateFooterText = function (value) {
		$('#site-footer, #site-footer a').css('color', value);
	},

	updateFooterTitles = function (value) {
		$('.footer-widget .widget-title').css('color', value);
	}, 

	updateHeaderButtons = function (value) {
		$('.social a, .account-links .button.button-alt, .account-links .button.button-alt::before').css('color', value);
	}, 

	updateBodyTexture = function(value) {
		$('body').css('background-image', 'url(' + value + ')');
	}, 

	updateCampaignTexture = function(value) {
		$('.active-campaign').css('background-image', 'url(' + value + ')');
	}, 

	updateBannerTexture = function(value) {
		$('.banner').css('background-image', 'url(' + value + ')');
	}, 

	updateSocial = function(value, network) {
		var $el = $('.social .'+network);

		// If this button isn't in there yet, create it now
		if ($el.length === 0 && value.length > 0) {
			$('.social').append('<li><a class="'+network+'" href="'+value+'"><i class="icon-'+network+'"></i></a></li>');
		}
		// Update the link
		else if ($el.length > 0 && value.length > 0) {
			$el.find('a').attr('href', value);
		} 
		// Remove the link
		else {
			$el.remove();
		}
	};

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.site_title a' ).html( newval );
		} );
	} );
	
	// Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.tagline' ).html( newval );
		} );
	} );

	// Update the logo in real time...
	wp.customize( 'logo_url', function( value ) {		
		value.bind( function( newval ) {			

			// Get the image dimensions
			var img = new Image();
			img.src = newval;
			img.onload = function() {
				if ( newval.length > 0 ) {
					$('.site_title a').css( {
						'background' : 'url('+newval+') no-repeat left 50%', 
						'padding-left' : img.width + 10
					} );
				}
			}			
		} ); 
	} );

	// Hide the site title
	wp.customize( 'hide_site_title', function( value ) {
		value.bind( function( newval ) {
			$( '.site_title' ).toggleClass('hidden', newval);
		} );
	} );

	// Hide the site description
	wp.customize( 'hide_site_tagline', function( value ) {
		value.bind( function( newval ) {
			$( '.tagline' ).toggleClass('hidden', newval);
		} );
	} );

	//  Update colours
	wp.customize( 'accent_colour', function( value ) {
		value.bind( function( newval ) {			
			updateAccentColour( newval );			
		} );
	} );
	wp.customize( 'accent_hover', function( value ) {
		value.bind( function( newval ) {
			updateAccentHover( newval );
		} );
	} );
	wp.customize( 'accent_text', function( value ) {
		value.bind( function( newval ) {
			updateAccentText( newval );
		} );  
	} );
	wp.customize( 'body_background', function( value ) {
		value.bind( function( newval ) {
			updateBodyBackground( newval );
		});
	} );
	wp.customize( 'body_text', function( value ) {
		value.bind( function( newval ) {
			updateBodyText( newval );
		});
	} );
	wp.customize( 'button_text', function( value ) {
		value.bind( function( newval ) {
			updateButtonText( newval );
		});
	} );
	wp.customize( 'wrapper_background', function( value ) {
		value.bind( function( newval ) {
			updateWrapperBackground( newval );
		});
	} );
	wp.customize( 'posts_background', function( value ) {
		value.bind( function( newval ) {
			updatePostsBackground( newval );
		});
	} );
	wp.customize( 'widget_background', function( value ) {
		value.bind( function( newval ) {
			updateWidgetBackground( newval );
		});
	} );
	wp.customize( 'primary_border', function( value ) {
		value.bind( function( newval ) {
			updatePrimaryBorder( newval );
		});
	} );
	wp.customize( 'secondary_border', function( value ) {
		value.bind( function( newval ) {
			updateSecondaryBorder( newval );
		});
	} );
	wp.customize( 'meta_colour', function( value ) {
		value.bind( function( newval ) {
			updateMetaColour( newval );
		});
	} );
	wp.customize( 'footer_text', function( value ) {
		value.bind( function( newval ) {
			updateFooterText( newval );
		});
	} );
	wp.customize( 'footer_titles', function( value ) {
		value.bind( function( newval ) {
			updateFooterTitles( newval );
		});
	} );
	wp.customize( 'header_buttons', function( value ) {
		value.bind( function( newval ) {
			updateHeaderButtons( newval );
		});
	} );

	// Textures
	wp.customize( 'body_texture', function( value ) {
		value.bind( function( newval ) {
			console.log( newval );
			updateBodyTexture( newval );
		});
	} );
	wp.customize( 'body_texture_custom', function( value ) {
		value.bind( function( newval ) {
			updateBodyTexture( newval );
		});
	} );
	wp.customize( 'campaign_texture', function( value ) {
		value.bind( function( newval ) {
			updateCampaignTexture( newval );
		});
	} );
	wp.customize( 'campaign_texture_custom', function( value ) {
		value.bind( function( newval ) {
			updateCampaignTexture( newval );
		});
	} );
	wp.customize( 'blog_banner_texture', function( value ) {
		value.bind( function( newval ) {
			updateBannerTexture( newval );
		});
	} );
	wp.customize( 'blog_banner_texture_custom', function( value ) {
		value.bind( function( newval ) {
			updateBannerTexture( newval );
		});
	} );

	//
	// Social networks
	//

	wp.customize( 'bitbucket', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'bitbucket' );
		});
	});
	wp.customize( 'dribbble', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'dribbble' );
		});
	});
	wp.customize( 'facebook', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'facebook' );
		});
	});
	wp.customize( 'flickr', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'flickr' );
		});
	});
	wp.customize( 'foursquare', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'foursquare' );
		});
	});
	wp.customize( 'github', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'github' );
		});
	});
	wp.customize( 'google-plus', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'google-plus' );
		});
	});
	wp.customize( 'gittip', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'gittip' );
		});
	});
	wp.customize( 'instagram', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'instagram' );
		});
	});
	wp.customize( 'linkedin', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'linked' );
		});
	});
	wp.customize( 'pinterest', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'pinterest' );
		});
	});
	wp.customize( 'renren', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'renren' );
		});
	});
	wp.customize( 'skype', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'skype' );
		});
	});
	wp.customize( 'stackexchange', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'stackexchange' );
		});
	});
	wp.customize( 'trello', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'trello' );
		});
	});
	wp.customize( 'tumblr', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'tumblr' );
		});
	});
	wp.customize( 'twitter', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'twitter' );
		});
	});
	wp.customize( 'vk', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'vk' );
		});
	});
	wp.customize( 'weibo', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'weibo' );
		});
	});
	wp.customize( 'windows', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'windows' );
		});
	});
	wp.customize( 'xing', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'xing' );
		});
	});
	wp.customize( 'youtube', function( value ) {
		value.bind( function( newval ) {
			updateSocial( newval, 'youtube' );
		});
	});

} )( jQuery ); 