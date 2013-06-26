<?php
/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2
 */

class OSFA_Customizer {

	/**
     * Stores instance of class.
     * @var OSFA_Custuomizer
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $colours;

    /**
     * @var array
     */
    private $textures;    

    /**
     * @var Sofa_Framework
     */
    private $sofa;

	private function __construct() {
        $this->sofa = get_sofa_framework();

        $this->colours =  array(
            'accent_colour'         => array( 'title' => __( 'Accent colour', 'franklin' ), 'default' => '#d95b43' ), 
            'accent_hover'          => array( 'title' => __( 'Accent hover', 'franklin' ), 'default' => '#df745f' ), 
            'accent_text'           => array( 'title' => __( 'Text on accent', 'franklin' ), 'default' => '#fff' ),
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
            'footer_titles'         => array( 'title' => __( 'Footer titles', 'franklin' ), 'default' => '#fff' )
        );

        $this->textures = array(
            ''                                                                   => __( '— Select —', 'franklin' ),
            get_template_directory_uri() . '/media/images/diagonal-grain.png'    => __( 'Diagonal grain', 'franklin' ), 
            get_template_directory_uri() . '/media/images/fabric.png'            => __( 'Fabric', 'franklin' ), 
            get_template_directory_uri() . '/media/images/grain.png'             => __( 'Grain', 'franklin' ), 
            get_template_directory_uri() . '/media/images/grid.png'              => __( 'Grid', 'franklin' ), 
            get_template_directory_uri() . '/media/images/grunge.png'            => __( 'Grunge', 'franklin' ), 
            get_template_directory_uri() . '/media/images/lined-paper.png'       => __( 'Lined paper', 'franklin' ),
            get_template_directory_uri() . '/media/images/textured-paper.png'    => __( 'Textured paper', 'franklin' ), 
            get_template_directory_uri() . '/media/images/tweed.png'             => __( 'Tweed', 'franklin' )
        );

		add_action('customize_register', array(&$this, 'customize_register'));        
        add_action('customize_preview_init', array(&$this, 'customize_preview_init') ); 
        add_action('customize_controls_print_scripts', array(&$this, 'customize_controls_print_scripts'), 100);

        add_action('wp_head', array(&$this, 'wp_head'));
	}

	/**
     * Get class instance
     * @static
     * @return OSF_Customizer
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new OSFA_Customizer();
        }
        return self::$instance;
    }

    /**
     * Theme customization
     * @return void
     */
    public function customize_register($wp_customize) {
        /** 
         * Title & tagline section
         */
        $wp_customize->add_setting( 'logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'retina_logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_title', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_tagline', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_url',
            array(
                'settings' => 'logo_url',
                'section'  => 'title_tagline',
                'label'    => __( 'Logo', 'franklin' )
            ) )
        );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'retina_logo_url',
            array(
                'settings' => 'retina_logo_url',
                'section'  => 'title_tagline',
                'label'    => __( 'Retina version of logo (2x)', 'franklin' )
            ) )
        );        
        $wp_customize->add_control( 'hide_site_title', array(
            'settings' => 'hide_site_title', 
            'label' => __( 'Hide the site title', 'franklin' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );
        $wp_customize->add_control( 'hide_site_tagline', array(
            'settings' => 'hide_site_tagline', 
            'label' => __( 'Hide the tagline', 'franklin' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );
        
        /** 
         * Colors
         */
        $priority = 10;

        foreach ( $this->colours as $key => $colour ) {          
            $wp_customize->add_setting( $key, array( 'default' => $colour['default'], 'transport' => 'postMessage' ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 
                'label' => $colour['title'], 
                'section' => 'colors', 
                'settings' => $key, 
                'priority' => $priority )
            ));

            $priority += 1;
        }

        /**
         * Textures
         */
        $wp_customize->add_section( 'textures', array( 
            'priority' => $priority, 
            'title' => __( 'Background Textures', 'franklin' ), 
            'description' => __( 'Choose background textures for the body and campaign section', 'franklin' )
        ) );

        $priority += 1;

        $wp_customize->add_setting( 'body_texture', array( 'default' => '', 'transport' => 'postMessage' ) );        
        $wp_customize->add_setting( 'body_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'campaign_texture', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'campaign_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'blog_banner_texture', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'blog_banner_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );

        $wp_customize->add_control( 'body_texture', array(
            'settings'      => 'body_texture',
            'label'         => __( 'Background texture for the body:', 'franklin' ), 
            'section'       => 'textures', 
            'type'          => 'select', 
            'priority'      => $priority,
            'choices'       => $this->textures
        ) );   

        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'body_texture_custom',
            array(
                'settings' => 'body_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Upload your own background texture', 'franklin' )
            ) )
        );

        $priority += 1;

        $wp_customize->add_control( 'campaign_texture', array(
            'settings'      => 'campaign_texture',
            'label'         => __( 'Background texture for the campaign section:', 'franklin' ), 
            'section'       => 'textures', 
            'type'          => 'select', 
            'priority'      => $priority,
            'choices'       => $this->textures
        ) );   

        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'campaign_texture_custom',
            array(
                'settings' => 'campaign_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Upload your own background texture', 'franklin' )
            ) )
        );
        
        $priority += 1;

        $wp_customize->add_control( 'blog_banner_texture', array(
            'settings'      => 'blog_banner_texture',
            'label'         => __( 'Background texture for the blog & fullwidth page banner:', 'franklin' ), 
            'section'       => 'textures', 
            'type'          => 'select', 
            'priority'      => $priority,
            'choices'       => $this->textures
        ) );   

        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blog_banner_texture_custom',
            array(
                'settings' => 'blog_banner_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Upload your own background texture', 'franklin' )
            ) )
        );
        
        $priority += 1;

        /**
         * Blog
         */
        $wp_customize->add_section( 'blog', array(
            'priority'      => $priority, 
            'title'         => __( 'Blog', 'franklin' ), 
            'description'   => __( 'Set your blog title', 'franklin' )
        ) );

        $priority += 1;

        $wp_customize->add_setting( 'blog_banner_title', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'blog_banner_title', array(
            'setting'       => 'blog_banner_title', 
            'label'         => __( 'Blog title', 'franklin' ), 
            'section'       => 'blog', 
            'type'          => 'text', 
            'priority'      => $priority
        ) );

        $priority += 1;

        /**
         * 404
         */
        $wp_customize->add_section( '404', array(
            'priority'      => $priority, 
            'title'         => __( '404', 'franklin' ), 
            'description'   => __( 'Set your 404 page title', 'franklin' )
        ) );

        $priority += 1;

        $wp_customize->add_setting( '404_banner_title', array( 'transport' => 'postMessage', 'default' => '404' ) );
        $wp_customize->add_control( '404_banner_title', array(
            'setting'       => '404_banner_title', 
            'label'         => __( '404 title', 'franklin' ), 
            'section'       => '404', 
            'type'          => 'text', 
            'priority'      => $priority
        ) );

        $priority += 1;        

        /** 
         * Campaign
         */    
        // if ( get_franklin_theme()->crowdfunding_enabled ) {

        //     $wp_customize->add_section( 'campaign', array( 
        //         'priority' => $priority, 
        //         'title' => __( "Campaign", 'projection' ), 
        //         'description' => __( 'description' )
        //     ) );

        //     $priority += 1; 

        //     $wp_customize->add_setting( 'campaign', array( 'transport' => 'postMessage' ) );
        //     $wp_customize->add_control( 'campaign', array(
        //         'settings' => 'campaign',
        //         'label' => __( 'Select the currently active campaign', 'projection' ), 
        //         'section' => 'campaign', 
        //         'type' => 'select', 
        //         'priority' => $priority,
        //         'choices' => $this->get_campaign_options()
        //     ) );

        //     $priority += 1;             
        // }

        /** 
         * Footer
         */
        $wp_customize->add_section( 'footer', array( 'title' => __('Footer', 'projection'), 'priority' => $priority ) );

        $priority += 1; 

        $wp_customize->add_setting( 'footer_notice', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'footer_notice', array( 
            'setting' => 'footer_notice', 
            'label' => __( 'Text for footer notice', 'projection' ), 
            'type' => 'text', 
            'section' => 'footer', 
            'priority' => $priority
        ));

        $priority += 1; 

        /**
         * Social
         */ 
        $wp_customize->add_section( 'social', array( 
            'priority' => 103, 
            'title' => __( 'Social', 'franklin' ),
            'description' => __( 'Set up links to your online social presences', 'franklin' )
        ) );

        // Loop over all the social sites the theme supports, creating settings and controls for each one
        foreach ( $this->sofa->get_social_sites() as $setting_key => $label ) {
            $wp_customize->add_setting( $setting_key, array( 'transport' => 'postMessage' ) );
            $wp_customize->add_control( $setting_key, array( 
                'settings' => $setting_key,
                'label' => $label, 
                'section' => 'social', 
                'type' => 'text'
            ) );
        }
    }   

    /**
     * Get available campaigns 
     * 
     * @return array
     */
    protected function get_campaign_options() {
        $options = array();        
        $campaigns = new ATCF_Campaign_Query();

        if ( empty( $campaigns->posts ) )
            return array();

        $options[] = __( '&#8212; Select &#8212;', 'franklin' );

        foreach ( $campaigns->posts as $campaign ) {
            $options[$campaign->ID] = $campaign->post_title;
        }
        
        return $options;
    }

    /**
     * Used by hook: 'customize_preview_init'
     * 
     * @see add_action('customize_preview_init',$func)
     */
    public function customize_preview_init() {
        ?>
        <script>
        </script>

        <?php
        wp_register_script( 'theme-customizer', get_template_directory_uri().'/media/js/theme-customize.js', array( 'jquery', 'customize-preview' ), 0.1, true );
        wp_enqueue_script( 'theme-customizer' );        
    }     

    /**
     * customize_controls_print_scripts
     * 
     * 
     */
     public function customize_controls_print_scripts() {
        ?>
        <script>
        ( function($){

            $(window).load(function() {                

            });
        })(jQuery);        
        </script>
        <?php
     }

    /**
     * Insert output into end of <head></head> section
     * @return void
     */
    public function wp_head() {        
        $template_directory = get_template_directory_uri();

        // Get the colours, and extract them
        $colours = array();

        foreach ( $this->colours as $key => $colour ) {
            $colours[$key] = get_theme_mod($key, $colour['default']);
        }

        extract($colours);

        $accent_rgb = $this->get_rgb_from_hex($accent_colour); 
        $widget_rgb = $this->get_rgb_from_hex($widget_background);
        $body_text_rgb = $this->get_rgb_from_hex($body_text);

        // Get the textures 
        $body_texture = get_theme_mod( 'body_texture', false );
        $body_texture_custom = get_theme_mod( 'body_texture_custom', false );        
        $body_texture_use = $body_texture_custom ? $body_texture_custom : $body_texture;
        if ( $body_texture_custom === false && $body_texture !== false ) {
            list($width, $height) = getimagesize($body_texture);   
            $body_texture_retina = substr( $body_texture, 0, -4 ) . '@2x.png';
            $body_texture_retina_dimensions = $width/2 . 'px ' . $height/2 . 'px';
        }

        $campaign_texture = get_theme_mod( 'campaign_texture', false );
        $campaign_texture_custom = get_theme_mod( 'campaign_texture_custom', false );
        $campaign_texture_use = $campaign_texture_custom ? $campaign_texture_custom : $campaign_texture;
        if ( $campaign_texture_custom === false && $campaign_texture !== false ) {
            list($width, $height) = getimagesize($campaign_texture);   
            $campaign_texture_retina = substr( $campaign_texture, 0, -4 ) . '@2x.png';
            $campaign_texture_retina_dimensions = $width/2 . 'px ' . $height/2 . 'px';
        }

        $blog_banner_texture = get_theme_mod( 'blog_banner_texture', false );
        $blog_banner_texture_custom = get_theme_mod( 'blog_banner_texture_custom', false );
        $blog_banner_texture_use = $blog_banner_texture_custom ? $blog_banner_texture_custom : $blog_banner_texture;
        if ( $blog_banner_texture_custom === false && $blog_banner_texture !== false ) {
            list($width, $height) = getimagesize($blog_banner_texture);   
            $blog_banner_texture_retina = substr( $blog_banner_texture, 0, -4 ) . '@2x.png';
            $blog_banner_texture_retina_dimensions = $width/2 . 'px ' . $height/2 . 'px';
        }

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

<?php if ( $body_texture_use !== false ) : ?>
/* Body background */
body { background-image: url(<?php echo $body_texture_use ?>); }
<?php endif ?>

<?php if ( $campaign_texture_use !== false ) : ?>
/* Campaign background */
.active-campaign { background-image: url(<?php echo $campaign_texture_use ?>); }
<?php endif ?>

<?php if ( $blog_banner_texture_use !== false ) : ?>
/* Blog banner background */
.banner { background-image: url(<?php echo $blog_banner_texture_use ?>); }
<?php endif ?>

/* Accent colour */
a, #site-navigation .menu-button, #site-navigation a:hover, .block-title, .widget-title, .page-title, .post-title, .pledge-level.not-available .pledge-limit, .post-author i, body .button.accent:hover, .button.accent.button-alt, .social a:hover, #site-navigation .current-menu-item a { color: <?php echo $accent_colour ?>; }
.campaign-button, .active-campaign, .sticky.block, .button.accent, .button.accent.button-alt:hover, .banner, .gallery-icon, .featured-image a { background-color: <?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; }
.button.accent, .campaign-support .button.accent:hover { box-shadow: 0 0 0 0.3rem <?php echo $accent_colour ?>; }
#site-navigation .hovering > a { border-color: <?php echo $accent_colour ?>; border-color: <?php echo $this->rgb($accent_rgb, 0.7) ?>; }
input[type=text]:focus, input[type=password]:focus, input[type=number]:focus, input[type=email]:focus, textarea:focus, .button.accent.button-alt, .button.accent.button-alt:hover { border-color: <?php echo $accent_colour ?>; }

/* Accent hover */
a:hover { color: <?php echo $accent_hover ?>;}
.sticky .post-title, .barometer .filled, .button.accent, .active-campaign .campaign-image { border-color: <?php echo $accent_hover ?>; }

/* Body background colour */
body, .audiojs .loaded { background-color: <?php echo $body_background ?>; }
.audiojs .play-pause { border-right-color: <?php echo $body_background ?>; }

/* Body copy */
body, .with-icon:before, .icon, input[type=submit]:hover, input[type=reset]:hover, button:hover, .button:hover, .button.accent:hover, .widget_search #searchsubmit::before, .button.button-alt, #site-navigation a, .block-title.with-icon i, .meta a, .format-status .post-title, .countdown_holding span, .widget-title { color: <?php echo $body_text ?>; }
.footer-widget .widget-title { text-shadow: 0 1px 0 <?php echo $body_text ?>; }
.campaign-excerpt { text-shadow: 0 1px 1px <?php echo $this->rgb($body_text_rgb, 0.7) ?>;}
.button.button-alt, .button.button-alt:hover, .account-links .button.button-alt:hover::before, .shadow-wrapper::before, .shadow-wrapper::after { border-color: <?php echo $body_text ?>; }
input[type=submit], input[type=reset], button, .button, .button.button-alt:hover, .account-links .button.button-alt:hover::before, .audiojs, .campaign-pledge-levels.accordion h3 { background-color: <?php echo $body_text ?>; }
input[type=submit], input[type=reset], button, .button { box-shadow: 0 0 0 3px <?php echo $body_text ?>; }
.active-campaign .campaign-image { box-shadow: 0 0 3px 1px <?php echo $this->rgb($body_text_rgb, 0.3) ?>;}

/* Button text colour */
input[type=submit], input[type=reset], button, .button, .active-campaign .campaign-button, .button.button-alt:hover, .account-links .button.button-alt:hover::before, .sticky.block, .sticky.block a, .campaign-support .button:hover, .campaign-pledge-levels.accordion h3 { color: <?php echo $button_text ?>; }
.campaign-support .button:hover { box-shadow: 0 0 0 3px <?php echo $button_text ?>; }

/* Widget background colour */
input[type=text], input[type=password], input[type=number], input[type=email], textarea, .featured-image, th, .entry blockquote, hr, pre, .meta, .audiojs .scrubber, .widget, .sidebar-block, .accordion h3 { background-color: <?php echo $widget_background ?>; }
.price-wrapper .currency, input:focus, textarea:focus, select:focus { background-color: <?php echo $this->rgb( $widget_rgb, 0.7 ) ?>; }

/* Meta text colour */
.meta, .comment-meta, .pledge-limit { color: <?php echo $meta_colour ?>; }

/* Primary border colour */
#header, .widget_search #s, #site-navigation li, #site-navigation.is-active ul ul, #site-navigation ul ul, .block, .page-title, .block-title, .post-title, .meta, .meta .author, .meta .comment-count, .meta .tags, .comment, .pingback, .widget, .campaign-pledge-levels.accordion h3, .campaign-pledge-levels.accordion .pledge-level, .multi-block .content-block:nth-of-type(1n+2), #edd_checkout_form_wrap legend, table, td, th { border-color: <?php echo $primary_border ?>; }

/* Secondary border colour */
th { border-right-color: <?php echo $secondary_border ?>; }
#site-navigation ul { border-top-color: <?php echo $secondary_border ?>; }
.widget-title { border-color: <?php echo $secondary_border ?>;}

/* Main content area background colour */
#main, #header, #site-navigation ul, #site-navigation ul ul, .even td, .widget td, .widget input[type=text], .widget input[type=password], .widget input[type=email], .widget input[type=number] { background-color: <?php echo $wrapper_background ?>; }

/* Posts background colour */
.entry-block, .content-block, .reveal-modal.multi-block .content-block, .widget_search #s:focus, .widget input[type=text]:focus, .widget input[type=password]:focus, .widget input[type=email]:focus, .widget input[type=number]:focus, .widget th, .widget tfoot td, .format-status .meta, .format-quote .entry blockquote, .audiojs .progress, .comments-section, .campaign-pledge-levels.accordion .pledge-level { background-color: <?php echo $posts_background ?>; }
.entry-block { box-shadow: 0 0 1px <?php echo $posts_background ?>; }
.sticky.block { border-color: <?php echo $posts_background ?>; }

/* Footer text */
#site-footer, #site-footer a, .social a { color: <?php echo $footer_text ?>; }    

/* Footer widget titles */
.footer-widget .widget-title { color: <?php echo $footer_titles ?>; }

@media all and (min-width: 50em) {
    #login-form .register-block { background-color: <?php echo $widget_background ?>; }
    #login-form .register-block input[type=text], #login-form .register-block input[type=password] { background-color: <?php echo $wrapper_background ?>; }
}

/* Text selection */
*::selection { background-color:<?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; } 
*::-moz-selection { background-color:<?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; }

<?php if ( $logo ) : ?>            
/* Logo */
.site_title a { background: url(<?php echo $logo ?>) no-repeat left 50%; padding-left: <?php echo $logo_meta['width'] + 10 ?>px; }
<?php endif ?>
                
/* Retina stuff */
<?php 
// Echoing this just to preserve proper colorization in sublime 
echo "@media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {" ?>

<?php if ( $body_texture_custom === false && $body_texture !== false ) : ?>
/* Body background */
body { background-image: url(<?php echo $body_texture_retina ?>); background-size: <?php echo $body_texture_retina_dimensions ?>;}
<?php endif ?>

<?php if ( $campaign_texture_custom === false && $campaign_texture !== false ) : ?>
/* Campaign background */
.active-campaign { background-image: url(<?php echo $campaign_texture_retina ?>); background-size: <?php echo $campaign_texture_retina_dimensions ?>;}
<?php endif ?>

<?php if ( $blog_banner_texture_custom === false && $blog_banner_texture !== false ) : ?>
/* Campaign background */
.banner { background-image: url(<?php echo $blog_banner_texture_retina ?>); background-size: <?php echo $blog_banner_texture_retina_dimensions ?>;}
<?php endif ?>

<?php if ( $retina_logo !== false && ( strlen( $retina_logo ) > 0 ) ) : ?>
.site_title a { background-image:url(<?php echo $retina_logo ?>); background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px; } 
<?php endif ?>
}
</style>
        <?php

        $html = ob_get_clean();

        echo $html;

        // Cache the output

    }

    /**
     * Returns an RGB CSS string.
     * 
     * @param array $rgb
     * @param int $alpha
     * @return string
     * @since Franklin 1.0
     */
    function rgb($rgb, $alpha = '') {
        return empty( $alpha ) ? sprintf( 'rgb(%s)', implode( ', ', $rgb ) ) : sprintf( 'rgba(%s, %s)', implode( ', ', $rgb ), $alpha);
    }

    /**
     * Return a HEX colour's RGB value as an array.
     * 
     * @credit http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
     * @param string $hex
     * @return array
     * @since Franklin 1.0
     */
    function get_rgb_from_hex($hex) {
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

OSFA_Customizer::get_instance();