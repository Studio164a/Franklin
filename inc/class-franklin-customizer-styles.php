<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Franklin_Customizer_Styles' ) ) : 

/**
 * Franklin Customizer Styles
 *
 * @class 		Franklin_Customizer_Styles
 * @author 		Studio 164a
 * @category 	Frontend
 * @package 	Franklin/Customizer
 * @since 		1.6.0
 */
class Franklin_Customizer_Styles {

	/**
	 * @var Franklin_Theme $theme
	 */
	private $theme;

	/**
	 * Creates an instance of this class. 
	 * 
	 * This can only be run on the franklin_theme_start hook. You should
	 * never need to instantiate it again (if you do, I'd love to hear
	 * your use case).
	 *
	 * @static
	 * 
	 * @param 	Franklin_Theme 	$theme
	 * @return 	void
	 * @access 	public
	 * @since 	1.6.0
	 */
	public static function franklin_theme_start( Franklin_Theme $theme ) {
		if ( ! $theme->is_start() ) {
			return;
		}

		new Franklin_Customizer_Styles( $theme );
	}

	/**
	 * Object constructor. 
	 *
	 * @param 	Franklin_Theme 	$theme
	 * @return 	void
	 * @access 	private
	 * @since 	1.6.0
	 */
	private function __construct( Franklin_Theme $theme ) {
		$this->theme = $theme;

		add_action( 'wp_head', array( $this, 'wp_head' ) );

        do_action( 'franklin_customizer_styles', $this );
	}

	/**
	 * Return array of colour mods available.
	 *
	 * @static
	 * @return 	array
	 * @access 	public
	 * @since 	1.6.0
	 */
	public static function get_customizer_colours() {
		return array(
            'accent_colour'         => array( 'title' => __( 'Accent colour', 'franklin' ), 'default' => '#d95b43' ), 
            'accent_hover'          => array( 'title' => __( 'Accent hover', 'franklin' ), 'default' => '#df745f' ), 
            'accent_text'           => array( 'title' => __( 'Text on accent', 'franklin' ), 'default' => '#fff' ),
            'accent_text_secondary' => array( 'title' => __( 'Text on accent (secondary)', 'franklin' ), 'default' => '#574c45' ),
            'body_background'       => array( 'title' => __( 'Body background colour', 'franklin' ), 'default' => '#aea198' ),
            'body_text'             => array( 'title' => __( 'Body copy', 'franklin' ), 'default' => '#7d6e63' ),
            'button_text'           => array( 'title' => __( 'Button text colour', 'franklin' ), 'default' => '#fff' ),
            'wrapper_background'    => array( 'title' => __( 'Wrapper background colour', 'franklin' ), 'default' => '#f9f8f7' ),
            'posts_background'      => array( 'title' => __( 'Posts background colour', 'franklin' ), 'default' => '#fff' ),
            'widget_background'     => array( 'title' => __( 'Widget background colour', 'franklin' ), 'default' => '#f1efee' ),
            'primary_border'        => array( 'title' => __( 'Primary border ', 'franklin' ), 'default' => '#e2dedb' ),
            'secondary_border'      => array( 'title' => __( 'Secondary border', 'franklin' ), 'default' => '#dbd5d1' ),
            'meta_colour'           => array( 'title' => __( 'Meta text', 'franklin' ), 'default' => '#bdb2ab' ),
            'footer_text'           => array( 'title' => __( 'Footer text', 'franklin' ), 'default' => '#fff' ),
            'footer_titles'         => array( 'title' => __( 'Footer titles', 'franklin' ), 'default' => '#fff' ), 
            'header_buttons'        => array( 'title' => __( 'Header buttons', 'franklin' ), 'default' => '#fff' ), 
            'header_buttons_hover'  => array( 'title' => __( 'Header buttons hover', 'franklin' ), 'default' => '#d95b43' )  
        );
	}

    /**
     * Return the key used to store customizer styles as a transient.
     *
     * @static
     * @return  string
     * @access  public
     * @since   1.6.0
     */
    public static function get_transient_key() {
        return 'franklin_customizer_styles';
    }

	/**
     * Insert output into end of <head></head> section.
     *
     * @return 	void
     */
    public function wp_head() {  
        /**
         * Check for saved customizer styles. 
         */
        $styles = get_transient( self::get_transient_key() );      
        $styles = false;
            
        if ( false === $styles ) {

            $template_directory = get_template_directory_uri();

            // Get the colours, and extract them
            $colours = array();
            foreach ( self::get_customizer_colours() as $key => $colour ) {
                $colours[$key] = get_theme_mod($key, $colour['default']);
            }

            $accent_rgb = $this->get_rgb_from_hex( $colours['accent_colour'] ); 
            $widget_rgb = $this->get_rgb_from_hex( $colours['widget_background'] );
            $body_text_rgb = $this->get_rgb_from_hex( $colours['body_text'] );

            // Get the textures 
            $body_texture_custom = get_theme_mod( 'body_texture_custom', false );        
            $campaign_texture_custom = get_theme_mod( 'campaign_texture_custom', false );
            $blog_banner_texture_custom = get_theme_mod( 'blog_banner_texture_custom', false );
            
            // Logo 
            $logo = get_theme_mod('logo_url', false);   
            $retina_logo = get_theme_mod('retina_logo_url', false); 
            $has_logo = $logo !== false && ( strlen( $logo ) > 0 );
            if ( $has_logo ) {
                $logo_post_id = sofa_get_image_id_from_url( $logo );
                $logo_meta = wp_get_attachment_metadata( $logo_post_id );            
            }

            ob_start();
            ?>
<style media="all" type="text/css">   

<?php if ( $body_texture_custom !== false ) : ?>
/* Body background */
body { background-image: url(<?php echo $body_texture_custom ?>); }
<?php endif ?>

<?php if ( $campaign_texture_custom !== false ) : ?>
/* Campaign background */
.feature-block, .feature-block.page { background-image: url(<?php echo $campaign_texture_custom ?>); }
<?php endif ?>

<?php if ( $blog_banner_texture_custom !== false ) : ?>
/* Blog banner background */
.banner { background-image: url(<?php echo $blog_banner_texture_custom ?>); }
<?php endif ?>

/* Accent colour */
a, .menu-button, .menu-site a:hover, .block-title, .widget-title, .page-title, .post-title, .pledge-level.not-available .pledge-limit, .post-author i, body .button.accent:hover, .button.accent.button-alt, .social a:hover, .menu-site .current-menu-item > a, .campaign .campaign-status .campaign-raised span, .campaign .campaign-status .campaign-pledged span, .campaign .campaign-status .campaign-time-left span, #lang_sel ul ul a, #lang_sel ul ul a:visited, #campaign-widget-sharing h2 { 
    color: <?php echo $colours['accent_colour'] ?>; 
}
.campaign-button, .feature-block.page, .feature-block, .feature-block.page .page-title, .sticky.block, .button.accent, .button.accent.button-alt:hover, .banner, .gallery-icon, .featured-image a, .edd_success { 
    background-color: <?php echo $colours['accent_colour'] ?>; 
    color: <?php echo $colours['accent_text'] ?>; 
}
.feature-block .share, .feature-block .more-link, .feature-block .button.button-alt:hover, .feature-block .share .icon:before { 
    color: <?php echo $colours['accent_text'] ?>; 
}
.button.accent, .campaign-support .button.accent:hover { 
    box-shadow: 0 0 0 0.3rem <?php echo $colours['accent_colour'] ?>; 
}
.menu-site .hovering > a { 
    border-color: <?php echo $colours['accent_colour'] ?>; 
    border-color: <?php echo $this->rgb($accent_rgb, 0.7) ?>; 
}
.page-template-default #header, .is-active > .menu-site { 
    border-color: <?php echo $colours['accent_colour'] ?>; 
}
input[type=text]:focus, input[type=password]:focus, input[type=number]:focus, input[type=email]:focus, textarea:focus, input[type=text]:active, input[type=password]:active, input[type=number]:active, input[type=email]:active, textarea:active, .button.accent.button-alt, .button.button-secondary.accent, .button.accent.button-alt:hover, .button.button-secondary.accent:hover select:active { 
    border-color: <?php echo $colours['accent_colour'] ?>; 
}

/* Accent hover */
a:hover { 
    color: <?php echo $colours['accent_hover'] ?>;
}
.sticky .post-title, .barometer .filled, .button.accent, .feature-block .campaign-image .wp-post-image, .site-navigation ul { 
    border-color: <?php echo $colours['accent_hover'] ?>; 
}

/* Secondary accent text */
.feature-block .campaign-summary h3 a, .feature-block .button.button-alt, .campaign-ended .time-ago { 
    color: <?php echo $colours['accent_text_secondary'] ?>; 
}
.feature-block .button.button-alt, .feature-block .button.button-alt:hover { 
    border-color: <?php echo $colours['accent_text_secondary'] ?>; 
}
.feature-block .button.button-alt:hover { 
    background-color: <?php echo $colours['accent_text_secondary'] ?>; 
}

/* Body background colour */
body, .audiojs .loaded, .edd_errors { 
    background-color: <?php echo $colours['body_background'] ?>; 
}
.audiojs .play-pause { 
    border-right-color: <?php echo $colours['body_background'] ?>; 
}

/* Body copy */
body, .icon, input[type=submit]:hover, input[type=reset]:hover, input[type=submit]:focus, input[type=reset]:focus, input[type=submit]:active, input[type=reset]:active, button:hover, .button:hover, .button.accent:hover, .button.button-alt, .button.button-secondary, .menu-site a, .block-title.with-icon i, .meta a, .format-status .post-title, .countdown_holding span, .widget-title, .with-icon:before, .widget_search #searchsubmit:before, #lang_sel a.lang_sel_sel, #campaign-widget-sharing, .campaign-categories .block-title, .wp-core-ui .wp-media-buttons .button { 
    color: <?php echo $colours['body_text'] ?>; 
}
<?php if ( $colours['body_text'] != $colours['footer_titles'] ) : ?>
.footer-widget .widget-title { 
    text-shadow: 0 1px 0 <?php echo $colours['body_text'] ?>; 
}
<?php endif ?>
.campaign-excerpt, .active-campaign .share .icon:before { 
    text-shadow: 0 1px 1px <?php echo $colours['body_text'] ?>; 
    text-shadow: 0 1px 1px <?php echo $this->rgb($body_text_rgb, 0.7) ?>;
}
.button.button-alt, .button.button-alt:hover, .account-links .button.button-alt, .button.button-secondary, .button.button-secondary:hover, .shadow-wrapper:before, .shadow-wrapper:after { 
    border-color: <?php echo $colours['body_text'] ?>; 
}
input[type=submit], input[type=reset], button, .button, .button.button-alt:hover, .button.button-secondary:hover, .audiojs, .widget .campaign-pledge-levels h3, .account-links .button.button-alt:hover:before, .campaign-preview .edd_errors { 
    background-color: <?php echo $colours['body_text'] ?>; 
}
input[type=submit], input[type=reset], button, .button { 
    box-shadow: 0 0 0 3px <?php echo $colours['body_text'] ?>; 
}
.active-campaign .campaign-image .wp-post-image { 
    box-shadow: 0 0 3px 1px <?php echo $this->rgb($body_text_rgb, 0.3) ?>;}

/* Button text colour */
input[type=submit], input[type=reset], button, .button, .active-campaign .campaign-button, .button.button-alt:hover, .button.button-secondary:hover, .sticky.block, .sticky.block a, .campaign-support .button:hover, .widget .campaign-pledge-levels h3, .feature-block .button, .feature-block .block-title, .account-links .button.button-alt:hover:before, .feature-block .block-title:before { 
    color: <?php echo $colours['button_text'] ?>; 
}
.campaign-support .button:hover { 
    box-shadow: 0 0 0 3px <?php echo $colours['button_text'] ?>; 
}

/* Widget background colour */
input[type=text], input[type=password], input[type=number], input[type=email], .chrome input[type=file], .safari input[type=file], textarea, select, .featured-image, th, .entry blockquote, hr, pre, .meta, .audiojs .scrubber, .widget, .sidebar-block, .accordion h3, .atcf-multi-select, #login-form .wrapper { 
    background-color: <?php echo $colours['widget_background'] ?>; 
}
input:focus, textarea:focus, select:focus, input:active, textarea:active, select:active { 
    background-color: <?php echo $this->rgb( $widget_rgb, 0.7 ) ?>; 
}

/* Meta text colour */
.meta, .comment-meta, .pledge-limit { 
    color: <?php echo $colours['meta_colour'] ?>; 
}

/* Primary border colour */
.widget_search #s, .menu-site li, .is-active > .menu-site ul, .block, .page-title, .block-title, .post-title, .meta, .meta .author, .meta .comment-count, .meta .tags, .comment, .pingback, .widget, .widget .campaign-pledge-levels h3, .widget .campaign-pledge-levels .pledge-level, #edd_checkout_form_wrap legend, table, td, th, .contact-page .ninja-forms-form-wrap, .atcf-submit-campaign-reward, .campaign .campaign-status, .campaign .campaign-status .campaign-raised, .campaign .campaign-status .campaign-pledged, .campaign .campaign-status .campaign-time-left, .atcf-profile-section, .atcf-submit-section, #lang_sel ul ul, #lang_sel ul ul a, #campaign-widget-sharing h2, .author-links, .author-campaigns-block.block, .author-bio, #login-form .edd-slg-social-wrap { 
    border-color: <?php echo $colours['primary_border'] ?>; 
}
.multi-block .content-block:nth-of-type(1n+2) { 
    border-color: <?php echo $colours['primary_border'] ?>; 
}
.campaigns-grid .campaign { 
    box-shadow: 0 0 0 1px <?php echo $colours['primary_border'] ?>; 
}

/* Secondary border colour */
th { 
    border-right-color: <?php echo $colours['secondary_border'] ?>; 
}
.menu-site { 
    border-top-color: <?php echo $colours['secondary_border'] ?>; 
}
.widget-title { 
    border-color: <?php echo $colours['secondary_border'] ?>;}

/* Main content area background colour */
#main, #header, .menu-site, .menu-site ul, .even td, .widget td, .widget input[type=text], .widget input[type=password], .widget input[type=email], .widget input[type=number], .entry-block.contact-page { 
    background-color: <?php echo $colours['wrapper_background'] ?>; 
}

/* Posts background colour */
.entry-block, .content-block, .reveal-modal.multi-block .content-block, .widget_search #s:focus, .widget input[type=text]:focus, .widget input[type=password]:focus, .widget input[type=email]:focus, .widget input[type=number]:focus, .widget textarea:focus, .widget input[type=text]:active, .widget input[type=password]:active, .widget input[type=email]:active, .widget input[type=number]:active, .widget textarea:active, .widget th, .widget tfoot td, .format-status .meta, .format-quote .entry blockquote, .audiojs .progress, .comments-section, .widget .campaign-pledge-levels .pledge-level, .contact-page .ninja-forms-form-wrap, #login-form .active.tab-title { 
    background-color: <?php echo $colours['posts_background'] ?>; 
}
.entry-block { 
    box-shadow: 0 0 1px <?php echo $colours['posts_background'] ?>; 
}
.sticky.block { 
    border-color: <?php echo $colours['posts_background'] ?>; 
}

/* Footer text */
#site-footer, #site-footer a, .edd_errors, .edd_errors a { 
    color: <?php echo $colours['footer_text'] ?>; 
}    
#rockbottom { 
    border-color: <?php echo $this->rgb( $this->get_rgb_from_hex( $colours['footer_text'] ), 0.5 ) ?>; 
}

/* Footer widget titles */
.footer-widget .widget-title, #rockbottom { 
    color: <?php echo $colours['footer_titles'] ?>; 
}

/* Header buttons */
.social a, .account-links .button.button-alt, .account-links .button.button-alt:before, .logout, .logout:before, .logout:hover { 
    color: <?php echo $colours['header_buttons'] ?>; 
}
.social a:hover:before { 
    color: <?php echo $colours['header_buttons_hover'] ?>; 
}
.account-links .button.button-alt:hover, .account-links .button.button-alt:hover:before { 
    background-color: <?php echo $colours['header_buttons_hover'] ?>; 
    border-color: <?php echo $colours['header_buttons_hover'] ?>; 
}

/* Text selection */
*::selection { 
    background-color:<?php echo $colours['accent_colour'] ?>; 
    color: <?php echo $colours['accent_text'] ?>; 
} 
*::-moz-selection { 
    background-color:<?php echo $colours['accent_colour'] ?>; 
    color: <?php echo $colours['accent_text'] ?>; 
}
.active-campaign::selection, .active-campaign *::selection { 
    background-color:<?php echo $colours['accent_text_secondary'] ?>; 
    color: <?php echo $colours['accent_text'] ?>; 
}
.active-campaign::-moz-selection, .active-campaign *::-moz-selection { 
    background-color:<?php echo $colours['accent_text_secondary'] ?>; 
    color: <?php echo $colours['accent_text'] ?>; 
}


<?php if ( $logo ) : ?>
    /* Logo */
    .site-identity { 
        background: url(<?php echo $logo ?>) no-repeat left 50%; 
        padding-left: <?php echo $logo_meta['width'] + 10 ?>px; 
        min-height: <?php echo $logo_meta['height'] ?>px; 
    }
    .no-tagline .site-title { 
        line-height: <?php echo $logo_meta['height'] ?>px; 
    }
    .no-title .site-tagline { 
        line-height: <?php echo $logo_meta['height'] - 12 ?>px; 
    }
    .no-title.no-tagline .site-identity {
        width: <?php echo $logo_meta['width'] ?>px;
    }
    .no-title.no-tagline .site-navigation .menu-site { 
        margin-top: <?php echo $logo_meta['height'] - 18 ?>px;  
    }
    <?php if ( $logo_meta['height'] > 34 ) : ?>
        .no-tagline .site-navigation .menu-site { 
            margin-top: <?php echo $logo_meta['height'] - 18 ?>px; 
        }
        .no-title .site-navigation .menu-site { 
            margin-top: <?php echo $logo_meta['height'] - 18 ?>px; 
        }
    <?php endif ?>
<?php endif ?>
                
/* Greater than 600px width */
@media all and (min-width: 37.5em) {
    .author-bio { 
        background-color: <?php echo $colours['posts_background'] ?>; 
    }
}

/* Retina stuff */
<?php 
// Echoing this just to preserve proper colorization in sublime 
echo "@media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {" ?>

<?php if ( $retina_logo !== false && ( strlen( $retina_logo ) > 0 ) ) : ?>
    .site-identity { 
        background-image:url(<?php echo $retina_logo ?>); background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px; 
    } 
<?php endif ?>
}
</style>                    
            <?php 
            $styles = ob_get_clean();

            // Cache the styles
            set_transient( self::get_transient_key(), $styles );
        }

        // Print the styles
        echo $styles;
    }

	/**
     * Returns an RGB CSS string.
     * 
     * @param 	array 	$rgb
     * @param 	int 	$alpha
     * @return 	string
     * @since 	1.0.0
     */
    private function rgb($rgb, $alpha = '') {
        return empty( $alpha ) ? sprintf( 'rgb(%s)', implode( ', ', $rgb ) ) : sprintf( 'rgba(%s, %s)', implode( ', ', $rgb ), $alpha);
    }

    /**
     * Return a HEX colour's RGB value as an array.
     * 
     * @credit 	http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
     * @param 	string 	$hex
     * @return 	array
     * @since 	1.0.0
     */
    private function get_rgb_from_hex($hex) {
        $hex = str_replace("#", "", $hex);

        if( strlen($hex) == 3 ) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        return array($r, $g, $b);
    }
}

endif;