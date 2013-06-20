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
     * @var Sofa_Framework
     */
    private $sofa;

	private function __construct() {
        $this->sofa = get_sofa_framework();

        // require_once( 'customize-controls/textarea.class.php' );

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
                'label'    => __( 'Logo', 'projection' )
            ) )
        );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'retina_logo_url',
            array(
                'settings' => 'retina_logo_url',
                'section'  => 'title_tagline',
                'label'    => __( 'Retina version of logo (2x)', 'projection' )
            ) )
        );        
        $wp_customize->add_control( 'hide_site_title', array(
            'settings' => 'hide_site_title', 
            'label' => __( 'Hide the site title', 'projection' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );
        $wp_customize->add_control( 'hide_site_tagline', array(
            'settings' => 'hide_site_tagline', 
            'label' => __( 'Hide the tagline', 'projection' ),
            'section' => 'title_tagline', 
            'type' => 'checkbox'            
        ) );

        /** 
         * Skin
         */ 
        
        /** 
         * Colors
         */
        $wp_customize->add_setting( 'accent_colour', array( 'default' => '#d95b43', 'transport'   => 'postMessage' ) );
        $wp_customize->add_setting( 'accent_hover', array( 'default' => '#df745f', 'transport'   => 'postMessage' ) );
        $wp_customize->add_setting( 'accent_text', array( 'default' => '#fff', 'transport' => 'postMessage' ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_colour', array(
            'label'         => __( 'Accent Colour', 'projection' ),
            'section'       => 'colors',
            'settings'      => 'accent_colour',
            'priority'      => 10
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_hover', array(
            'label'         => __( 'Accent Hover', 'projection' ),
            'section'       => 'colors',
            'settings'      => 'accent_hover',
            'priority'      => 12
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_text', array(
            'label'         => __( 'Text on Accent', 'projection' ), 
            'section'       => 'colors',
            'settings'      => 'accent_text', 
            'priority'      => 14 
        ) ) );        

        /** 
         * Campaign
         */    
        $wp_customize->add_section( 'campaign', array( 
            'priority' => 98, 
            'title' => __( "Campaign", 'projection' ), 
            'description' => __( 'description' )
        ) );

        $wp_customize->add_setting( 'campaign', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'campaign', array(
            'settings' => 'campaign',
            'label' => __( 'Select the currently active campaign', 'projection' ), 
            'section' => 'campaign', 
            'type' => 'select', 
            'choices' => $this->get_campaign_options()
        ) );

        /**
         * Social
         */ 
        $wp_customize->add_section( 'social', array( 
            'priority' => 103, 
            'title' => __( 'Social', 'projection' ),
            'description' => __( 'Set up links to your online social presences', 'projection' )
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

        /** 
         * Footer
         */
        $wp_customize->add_section( 'footer', array( 'title' => __('Footer', 'projection'), 'priority' => 120 ) );
        $wp_customize->add_setting( 'footer_notice', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'footer_notice', array( 
            'setting' => 'footer_notice', 
            'label' => __( 'Text for footer notice', 'projection' ), 
            'type' => 'text', 
            'section' => 'footer'
        ));
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

        $options[] = __( '&#8212; Select &#8212;', 'projection' );

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

        // Accent colour
        $accent = get_theme_mod('accent_colour', '#d95b43');
        $accent_hover = get_theme_mod('accent_hover', '#df745f');
        $accent_text = get_theme_mod('accent_text', '#ffffff');

        $accent_rgb = $this->get_rgb_from_hex($accent);        
        ?>

<style media="all" type="text/css">   
/* Accent colour */
a, #site-navigation .menu-button, #site-navigation a:hover, .block-title, .widget-title, .page-title, .post-title, .pledge-level.not-available .pledge-limit, .post-author i, .button.accent:hover { color: <?php echo $accent ?>; }
.campaign-button, .active-campaign { background-color: <?php echo $accent ?>; color: <?php echo $accent_text ?>; }
#site-navigation ul { background-color: <?php echo $accent_text ?>; border-color: <?php echo $accent ?>; }

.barometer .filled { border-color: <?php echo $accent_hover ?>; }
#site-navigation .hovering > a { border-color: <?php echo $accent ?>; border-color: <?php echo $this->rgb($accent_rgb, 0.7) ?>; }
.button.accent { background-color: <?php echo $accent ?>; color: <?php echo $accent_text ?>; border-color: <?php echo $accent_hover ?>; box-shadow: 0 0 0 3px <?php echo $accent ?>;}

input[type=text]:focus, input[type=password]:focus, input[type=number]:focus, input[type=email]:focus, textarea:focus { border-color: <?php echo $accent ?>; }

.hovering .on-hover {  background-color: <?php echo $accent ?>; background-color: <?php echo $this->rgb($accent_rgb, 0.5) ?>; }

/* Text selection */
*::selection { background-color:<?php echo $accent ?>; color: <?php echo $accent_text ?>; } 
*::-moz-selection { background-color:<?php echo $accent ?>; color: <?php echo $accent_text ?>; }
            <?php             
            // Logo 
            $logo = get_theme_mod('logo_url', false);    
            $has_logo = $logo !== false && ( strlen( $logo ) > 0 );
            if ( $has_logo ) : 
                $logo_post_id = osfa_get_image_id_from_url( $logo );
                $logo_meta = wp_get_attachment_metadata( $logo_post_id );                            
                ?>
/* Logo */
.site_title a { background: url(<?php echo $logo ?>) no-repeat left 50%; padding-left: <?php echo $logo_meta['width'] + 10 ?>px; }
                <?php 
                // Get the retina version (if there is one)
                $retina_logo = get_theme_mod('retina_logo_url', false);
                if ( $retina_logo !== false && ( strlen( $retina_logo ) > 0 ) ) : ?>
/* Retina logo */
@media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {                
    .site_title a { background-image:url(<?php echo $retina_logo ?>); background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px; } 
}
                <?php
                endif;
            endif;
            ?>        

</style>

        <?php
    }

    /**
     * Returns an RGB CSS string.
     * 
     * @param array $rgb
     * @param int $alpha
     * @return string
     * @since Projection 1.0
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
     * @since Projection 1.0
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