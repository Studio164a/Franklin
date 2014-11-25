<?php
/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2
 */

class Franklin_Customizer {

	/**
     * Stores instance of class.
     * @var     Franklin_Customizer
     */
    private static $instance = null;

    /**
     * @var     array
     */
    private $palettes;    

    /**
     * @var     Sofa_Framework
     */
    private $sofa;

    /**
     * @var     Franklin_Theme
     */
    private $theme;

    /**
     * Instantiate the object, but only if this is the start phase. 
     *
     * @static
     * @param   Franklin_Theme          $theme
     * @param   WP_Customize_Manager    $wp_customize 
     * @return  void
     */
    public static function start( Franklin_Theme $theme, WP_Customize_Manager $wp_customize ) {
        if ( $theme->started() ) {
            return;
        }

        new Franklin_Customizer( $theme, $wp_customize );
    }

    /**
     * Instantiate the object. 
     * 
     * @param   Franklin_Theme          $theme
     * @param   WP_Customize_Manager    $wp_customize 
     */
    private function __construct( Franklin_Theme $theme, WP_Customize_Manager $wp_customize ) {
        $this->theme = $theme;
        $this->sofa = get_sofa_framework();
        $this->palettes = array(
            'Orange & Brown'        => array( 'accent_colour' => '#d95b43', 'accent_hover' => '#df745f', 'accent_text' => '#fff', 'accent_text_secondary' => '#574c45',
                                        'body_background' => '#aea198', 'body_text' => '#7d6e63', 'button_text' => '#fff', 
                                        'wrapper_background' => '#f9f8f7', 'posts_background' => '#fff', 'widget_background' => '#f1efee', 
                                        'primary_border' => '#e2dedb', 'secondary_border' => '#dbd5d1', 'meta_colour' => '#bdb2ab', 
                                        'footer_text' => '#fff', 'footer_titles' => '#fff', 'header_buttons' => '#fff', 'header_buttons_hover' => '#d95b43' ), 

            'Teal & Blue/Gray'      => array( 'accent_colour' => '#26c9c4', 'accent_hover' => '#38d9d4', 'accent_text' => '#fff', 'accent_text_secondary' => '#273d3e',
                                        'body_background' => '#3c5c5e', 'body_text' => '#273d3e', 'button_text' => '#fff', 
                                        'wrapper_background' => '#f5f8f9', 'posts_background' => '#fff', 'widget_background' => '#ebf2f2', 
                                        'primary_border' => '#d6e4e5', 'secondary_border' => '#ccddde', 'meta_colour' => '#a2c2c4', 
                                        'footer_text' => '#fff', 'footer_titles' => '#fff', 'header_buttons' => '#fff', 'header_buttons_hover' => '#26c9c4' ),  

            'Orange & Teal'         => array( 'accent_colour' => '#f0b252', 'accent_hover' => '#f3c071', 'accent_text' => '#fff', 'accent_text_secondary' => '#767777',
                                        'body_background' => '#87c7c3', 'body_text' => '#767777', 'button_text' => '#fff', 
                                        'wrapper_background' => '#fbfdfd', 'posts_background' => '#fff', 'widget_background' => '#eff8f7', 
                                        'primary_border' => '#d8edec', 'secondary_border' => '#cce7e6', 'meta_colour' => '#9ed2cf', 
                                        'footer_text' => '#767777', 'footer_titles' => '#fff', 'header_buttons' => '#fff', 'header_buttons_hover' => '#f0b252' ), 

            'Burnt Red & Plum'      => array( 'accent_colour' => '#a73b2d', 'accent_hover' => '#c24434', 'accent_text' => '#fff', 'accent_text_secondary' => '#383438',
                                        'body_background' => '#383438', 'body_text' => '#524d52', 'button_text' => '#fff', 
                                        'wrapper_background' => '#faf9fa', 'posts_background' => '#fff', 'widget_background' => '#f1f0f1', 
                                        'primary_border' => '#e1dfe1', 'secondary_border' => '#d9d6d9', 'meta_colour' => '#b8b3b8', 
                                        'footer_text' => '#fff', 'footer_titles' => '#fff', 'header_buttons' => '#fff', 'header_buttons_hover' => '#a73b2d' ), 

            'Orange & Beige'        => array( 'accent_colour' => '#da9455', 'accent_hover' => '#e0a671', 'accent_text' => '#fff', 'accent_text_secondary' => '#574d3e',
                                        'body_background' => '#dad4cb', 'body_text' => '#848484', 'button_text' => '#fff', 
                                        'wrapper_background' => '#fdfdfd', 'posts_background' => '#fff', 'widget_background' => '#f6f5f3', 
                                        'primary_border' => '#e8e4df', 'secondary_border' => '#e1dcd5', 'meta_colour' => '#959595', 
                                        'footer_text' => '#848484', 'footer_titles' => '#848484', 'header_buttons' => '#fff', 'header_buttons_hover' => '#da9455' ), 

            'Mint & Steel'          => array( 'accent_colour' => '#71ca7a', 'accent_hover' => '#8ad391', 'accent_text' => '#fff', 'accent_text_secondary' => '#354044',
                                        'body_background' => '#435a62', 'body_text' => '#354044', 'button_text' => '#fff', 
                                        'wrapper_background' => '#fbfbfb', 'posts_background' => '#fff', 'widget_background' => '#f2f5f6', 
                                        'primary_border' => '#dee5e8', 'secondary_border' => '#d3dee1', 'meta_colour' => '#abbfc6', 
                                        'footer_text' => '#fff', 'footer_titles' => '#fff', 'header_buttons' => '#fff', 'header_buttons_hover' => '#71ca7a' )
        );

        add_action('after_setup_theme', array( $this, 'setup_callbacks' ) );        
    } 

    /**
     * Set up callbacks for the class.
     *
     * @return  void
     * @since   1.6.0
     */
    public function setup_callbacks() {
        add_action('customize_save_after',              array( $this, 'customize_save_after' ) );
        add_action('customize_register',                array( $this, 'customize_register' ) );        
        add_action('customize_preview_init',            array( $this, 'customize_preview_init' ) ); 
        add_action('customize_controls_print_scripts',  array( $this, 'customize_controls_print_scripts' ), 100 );
        add_action('wp_head',                           array( $this, 'preview_styles' ) );
    }

    /**
     * After the customizer has finished saving each of the fields, delete the transient.
     *
     * @see     customize_save_after hook
     * 
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     * @access  public
     * @since   1.6.0
     */
    public function customize_save_after(WP_Customize_Manager $wp_customize) {
        /** 
         * The saved styles may no longer be valid, so delete them. They 
         * will be re-created on the next page load.
         */
        delete_transient( Franklin_Customizer_Styles::get_transient_key() );
    }

    /**
     * Theme customization. 
     *
     * @return  void
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

        $wp_customize->add_setting( 'palette', array( 'default' => json_encode($this->palettes['Orange & Brown']), 'transport' => 'postMessage' ) );
        
        $priority += 1;

        $wp_customize->add_control( 'palette', array(
            'settings'      => 'palette',
            'label'         => __( 'Palette', 'franklin' ), 
            'section'       => 'colors', 
            'type'          => 'radio', 
            'priority'      => $priority,
            'choices'       => array(
                json_encode($this->palettes['Orange & Brown'])    => __( 'Orange & Brown', 'franklin' ),
                json_encode($this->palettes['Teal & Blue/Gray'])  => __( 'Teal & Blue/Gray', 'franklin' ),                 
                json_encode($this->palettes['Orange & Teal'])     => __( 'Orange & Teal', 'franklin' ),
                json_encode($this->palettes['Burnt Red & Plum'])  => __( 'Burnt Red & Plum', 'franklin' ),
                json_encode($this->palettes['Orange & Beige'])    => __( 'Orange & Beige', 'franklin' ),
                json_encode($this->palettes['Mint & Steel'])      => __( 'Mint & Steel', 'franklin' )
            )
        ) ); 

        $priority += 1;

        foreach ( Franklin_Customizer_Styles::get_customizer_colours() as $key => $colour ) {          
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
            'description' => __( 'Upload background textures for the body and campaign section', 'franklin' )
        ) );

        $priority += 1;

        $wp_customize->add_setting( 'body_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'campaign_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'blog_banner_texture_custom', array( 'default' => '', 'transport' => 'postMessage' ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'body_texture_custom',
            array(
                'settings' => 'body_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Body', 'franklin' )
            ) )
        );

        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'campaign_texture_custom',
            array(
                'settings' => 'campaign_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Featured Campaign Block', 'franklin' )
            ) )
        );
        
        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blog_banner_texture_custom',
            array(
                'settings' => 'blog_banner_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Blog & Page Banners', 'franklin' )
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
        if ( get_franklin_theme()->crowdfunding_enabled ) {

            $sharing_options = array(
                'campaign_share_twitter' => __( 'Share on Twitter', 'franklin' ), 
                'campaign_share_facebook' => __( 'Share on Facebook', 'franklin' ), 
                'campaign_share_googleplus' => __( 'Share on Google+', 'franklin' ), 
                'campaign_share_linkedin' => __( 'Share on LinkedIn', 'franklin' ), 
                'campaign_share_pinterest' => __( 'Share on Pinterest', 'franklin' ),
                'campaign_share_widget' => __( 'Share with embed code', 'franklin' )
            );

            $wp_customize->add_section( 'campaign', array( 
                'priority' => $priority, 
                'title' => __( "Campaigns", 'franklin' ), 
                'description' => __( 'Configure your campaign pages' )
            ) );

            $priority += 1; 

            foreach ( $sharing_options as $setting_key => $label ) {
                $wp_customize->add_setting( $setting_key, array( 'transport' => 'postMessage' ) );
                $wp_customize->add_control( $setting_key, array(
                    'settings' => $setting_key, 
                    'label' => $label,
                    'section' => 'campaign', 
                    'type' => 'checkbox', 
                    'priority' => $priority
                ) );

                $priority += 1;
            }

            $wp_customize->add_setting( 'campaign_sharing_text', array( 'transport' => 'postMessage' ) );
            $wp_customize->add_control( new Sofa_Customize_Textarea_Control( $wp_customize, 'campaign_sharing_text', array(
                'settings' => 'campaign_sharing_text',
                'label' => __( 'Text to display on campaign sharing widget', 'franklin' ), 
                'section' => 'campaign', 
                'type' => 'text', 
                'default' => __( 'Spread the word about this campaign by sharing this widget. Copy the snippet of HTML code below and paste it on your blog, website or anywhere else on the web.', 'franklin' ),
                'priority' => $priority
            ) ) );

            $priority += 1;             
        }

        /** 
         * Footer
         */
        $wp_customize->add_section( 'footer', array( 'title' => __('Footer', 'franklin'), 'priority' => $priority ) );

        $priority += 1; 

        $wp_customize->add_setting( 'footer_notice', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( 'footer_notice', array( 
            'setting' => 'footer_notice', 
            'label' => __( 'Text for footer notice', 'franklin' ), 
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

        $options[] = sprintf( '- %s -', __( 'Select', 'franklin' ) );

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

            // Variables
            var $accent_colour, $accent_hover, $accent_text, $accent_text_secondary, $body_background, $body_text, $button_text, 
            $wrapper_background, $posts_background, $widget_background, $primary_border, $secondary_border, 
            $meta_colour, $footer_text, $footer_titles, $header_buttons, $header_buttons_hover, $palette,

            // Swaps a palette
            switchPalette = function() {                
                var colours = JSON.parse( $palette.find('input:checked').val() );
                
                // General link styling
                $accent_colour.wpColorPicker('color', colours.accent_colour);
                $accent_hover.wpColorPicker('color', colours.accent_hover);
                $accent_text.wpColorPicker('color', colours.accent_text);
                $accent_text_secondary.wpColorPicker('color', colours.accent_text_secondary);
                $body_background.wpColorPicker('color', colours.body_background);
                $body_text.wpColorPicker('color', colours.body_text);
                $button_text.wpColorPicker('color', colours.button_text);
                $wrapper_background.wpColorPicker('color', colours.wrapper_background);
                $posts_background.wpColorPicker('color', colours.posts_background);
                $widget_background.wpColorPicker('color', colours.widget_background);
                $primary_border.wpColorPicker('color', colours.primary_border);
                $secondary_border.wpColorPicker('color', colours.secondary_border);
                $meta_colour.wpColorPicker('color', colours.meta_colour);
                $footer_text.wpColorPicker('color', colours.footer_text);
                $footer_titles.wpColorPicker('color', colours.footer_titles);    
                $header_buttons.wpColorPicker('color', colours.header_buttons);    
                $header_buttons_hover.wpColorPicker('color', colours.header_buttons_hover);    
            };

            $(window).load(function() {             

                $accent_colour = $('.color-picker-hex', '#customize-control-accent_colour');
                $accent_hover = $('.color-picker-hex', '#customize-control-accent_hover');
                $accent_text = $('.color-picker-hex', '#customize-control-accent_text');
                $accent_text_secondary = $('.color-picker-hex', '#customize-control-accent_text_secondary');
                $body_background = $('.color-picker-hex', '#customize-control-body_background');
                $body_text = $('.color-picker-hex', '#customize-control-body_text');
                $button_text = $('.color-picker-hex', '#customize-control-button_text');
                $wrapper_background = $('.color-picker-hex', '#customize-control-wrapper_background');
                $posts_background = $('.color-picker-hex', '#customize-control-posts_background');
                $widget_background = $('.color-picker-hex', '#customize-control-widget_background');
                $primary_border = $('.color-picker-hex', '#customize-control-primary_border');
                $secondary_border = $('.color-picker-hex', '#customize-control-secondary_border');
                $meta_colour = $('.color-picker-hex', '#customize-control-meta_colour');
                $footer_text = $('.color-picker-hex', '#customize-control-footer_text');
                $footer_titles = $('.color-picker-hex', '#customize-control-footer_titles');
                $header_buttons = $('.color-picker-hex', '#customize-control-header_buttons');
                $header_buttons_hover = $('.color-picker-hex', '#customize-control-header_buttons_hover');
            
                $palette = $('#customize-control-palette'); 

                // When one of the preset palettes is selected, change the relevant colours
                $palette.on('change', function() {
                    switchPalette();
                });
            });
        })(jQuery);        
        </script>
        <?php
    }

    public function preview_styles() {
        ?>
        <style>
        .site-navigation .menu-site > li, 
        .menu-site li.hovering { 
            height: 1em; 
        }
        </style>
        <?php
    }
}