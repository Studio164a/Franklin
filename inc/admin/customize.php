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
    private $palettes;    

    /**
     * @var array
     */
    private $textures;    

    /**
     * @var Sofa_Framework
     */
    private $sofa;

    /**
     * @var bool
     */
    public $in_customizer = false;    

	private function __construct() {
        $this->sofa = get_sofa_framework();

            // Check whether we're currently using the customizer
        if ( isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] == admin_url('customize.php') ) { 
            $this->in_customizer = true;
        } 

        add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 12);

        $this->colours =  array(
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

        $this->textures = array(
            ''                                                                   => sprintf( '- %s -', __( 'Select', 'franklin' ) ),
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
        add_action('customize_controls_print_styles', array(&$this, 'customize_controls_print_styles'), 100);
        add_action('customize_controls_print_scripts', array(&$this, 'customize_controls_print_scripts'), 100);

        add_action('wp_head', array(&$this, 'wp_head'));

        include_once('customize-controls/textarea.class.php');
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
     * Custom styles printed only on the theme customizer.
     * 
     * @see customize_controls_print_styles action
     */
    public function customize_controls_print_styles() {
        ?>
        <?php 
    }

    /**
     * Executed on wp_enqueue_scripts hook. 
     *
     * @see wp_enqueue_scripts action
     */
    public function wp_enqueue_scripts() {
        if ( $this->in_customizer ) {
            // wp_localize_script('franklin', 'sofa_in_customizer', array( 'sofa_in_customizer' => true) );
        }        
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
.feature-block, .feature-block.page { background-image: url(<?php echo $campaign_texture_use ?>); }
<?php endif ?>

<?php if ( $blog_banner_texture_use !== false ) : ?>
/* Blog banner background */
.banner { background-image: url(<?php echo $blog_banner_texture_use ?>); }
<?php endif ?>

/* Accent colour */
a, .menu-button, .menu-site a:hover, .block-title, .widget-title, .page-title, .post-title, .pledge-level.not-available .pledge-limit, .post-author i, body .button.accent:hover, .button.accent.button-alt, .social a:hover, .menu-site .current-menu-item > a, .campaign .campaign-status .campaign-raised span, .campaign .campaign-status .campaign-pledged span, .campaign .campaign-status .campaign-time-left span, #lang_sel ul ul a, #lang_sel ul ul a:visited, #campaign-widget-sharing h2 { color: <?php echo $accent_colour ?>; }
.campaign-button, .feature-block.page, .feature-block, .feature-block.page .page-title, .sticky.block, .button.accent, .button.accent.button-alt:hover, .banner, .gallery-icon, .featured-image a, .edd_success { background-color: <?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; }
.feature-block .share, .feature-block .more-link, .feature-block .button.button-alt:hover, .feature-block .share .icon:before { color: <?php echo $accent_text ?>; }
.button.accent, .campaign-support .button.accent:hover { box-shadow: 0 0 0 0.3rem <?php echo $accent_colour ?>; }
.menu-site .hovering > a { border-color: <?php echo $accent_colour ?>; border-color: <?php echo $this->rgb($accent_rgb, 0.7) ?>; }
.page-template-default #header, .is-active > .menu-site { border-color: <?php echo $accent_colour ?>; }
input[type=text]:focus, input[type=password]:focus, input[type=number]:focus, input[type=email]:focus, textarea:focus, input[type=text]:active, input[type=password]:active, input[type=number]:active, input[type=email]:active, textarea:active, .button.accent.button-alt, .button.button-secondary.accent, .button.accent.button-alt:hover, .button.button-secondary.accent:hover select:active { border-color: <?php echo $accent_colour ?>; }

/* Accent hover */
a:hover { color: <?php echo $accent_hover ?>;}
.sticky .post-title, .barometer .filled, .button.accent, .feature-block .campaign-image .wp-post-image, .site-navigation ul { border-color: <?php echo $accent_hover ?>; }

/* Secondary accent text */
.feature-block .campaign-summary h3 a, .feature-block .button.button-alt, .campaign-ended .time-ago { color: <?php echo $accent_text_secondary ?>; }
.feature-block .button.button-alt, .feature-block .button.button-alt:hover { border-color: <?php echo $accent_text_secondary ?>; }
.feature-block .button.button-alt:hover { background-color: <?php echo $accent_text_secondary ?>; }

/* Body background colour */
body, .audiojs .loaded, .edd_errors { background-color: <?php echo $body_background ?>; }
.audiojs .play-pause { border-right-color: <?php echo $body_background ?>; }

/* Body copy */
body, .icon, input[type=submit]:hover, input[type=reset]:hover, input[type=submit]:focus, input[type=reset]:focus, input[type=submit]:active, input[type=reset]:active, button:hover, .button:hover, .button.accent:hover, .button.button-alt, .button.button-secondary, .menu-site a, .block-title.with-icon i, .meta a, .format-status .post-title, .countdown_holding span, .widget-title, .with-icon:before, .widget_search #searchsubmit:before, #lang_sel a.lang_sel_sel, #campaign-widget-sharing, .campaign-categories .block-title, .wp-core-ui .wp-media-buttons .button { color: <?php echo $body_text ?>; }
<?php if ( $body_text != $footer_titles ) : ?>
.footer-widget .widget-title { text-shadow: 0 1px 0 <?php echo $body_text ?>; }
<?php endif ?>
.campaign-excerpt, .active-campaign .share .icon:before { text-shadow: 0 1px 1px <?php echo $body_text ?>; text-shadow: 0 1px 1px <?php echo $this->rgb($body_text_rgb, 0.7) ?>;}
.button.button-alt, .button.button-alt:hover, .account-links .button.button-alt, .button.button-secondary, .button.button-secondary:hover, .shadow-wrapper:before, .shadow-wrapper:after { border-color: <?php echo $body_text ?>; }
input[type=submit], input[type=reset], button, .button, .button.button-alt:hover, .button.button-secondary:hover, .audiojs, .campaign-pledge-levels.accordion h3, .account-links .button.button-alt:hover:before, .campaign-preview .edd_errors { background-color: <?php echo $body_text ?>; }
input[type=submit], input[type=reset], button, .button { box-shadow: 0 0 0 3px <?php echo $body_text ?>; }
.active-campaign .campaign-image .wp-post-image { box-shadow: 0 0 3px 1px <?php echo $this->rgb($body_text_rgb, 0.3) ?>;}

/* Button text colour */
input[type=submit], input[type=reset], button, .button, .active-campaign .campaign-button, .button.button-alt:hover, .button.button-secondary:hover, .sticky.block, .sticky.block a, .campaign-support .button:hover, .campaign-pledge-levels.accordion h3, .feature-block .button, .feature-block .block-title, .account-links .button.button-alt:hover:before, .feature-block .block-title:before { color: <?php echo $button_text ?>; }
.campaign-support .button:hover { box-shadow: 0 0 0 3px <?php echo $button_text ?>; }

/* Widget background colour */
input[type=text], input[type=password], input[type=number], input[type=email], .chrome input[type=file], .safari input[type=file], textarea, select, .featured-image, th, .entry blockquote, hr, pre, .meta, .audiojs .scrubber, .widget, .sidebar-block, .accordion h3, .atcf-multi-select { background-color: <?php echo $widget_background ?>; }
input:focus, textarea:focus, select:focus, input:active, textarea:active, select:active { background-color: <?php echo $this->rgb( $widget_rgb, 0.7 ) ?>; }

/* Meta text colour */
.meta, .comment-meta, .pledge-limit { color: <?php echo $meta_colour ?>; }

/* Primary border colour */
.widget_search #s, .menu-site li, .is-active > .menu-site ul, .block, .page-title, .block-title, .post-title, .meta, .meta .author, .meta .comment-count, .meta .tags, .comment, .pingback, .widget, .campaign-pledge-levels.accordion h3, .campaign-pledge-levels.accordion .pledge-level, #edd_checkout_form_wrap legend, table, td, th, .contact-page .ninja-forms-form-wrap, .atcf-submit-campaign-reward, .campaign .campaign-status, .campaign .campaign-status .campaign-raised, .campaign .campaign-status .campaign-pledged, .campaign .campaign-status .campaign-time-left, .atcf-profile-section, .atcf-submit-section, #lang_sel ul ul, #lang_sel ul ul a, #campaign-widget-sharing h2, .author-links, .author-campaigns-block.block, .author-bio { border-color: <?php echo $primary_border ?>; }
.multi-block .content-block:nth-of-type(1n+2) { border-color: <?php echo $primary_border ?>; }
.campaigns-grid .campaign { box-shadow: 0 0 0 1px <?php echo $primary_border ?>; }

/* Secondary border colour */
th { border-right-color: <?php echo $secondary_border ?>; }
.menu-site { border-top-color: <?php echo $secondary_border ?>; }
.widget-title { border-color: <?php echo $secondary_border ?>;}

/* Main content area background colour */
#main, #header, .menu-site, .menu-site ul, .even td, .widget td, .widget input[type=text], .widget input[type=password], .widget input[type=email], .widget input[type=number], .entry-block.contact-page { background-color: <?php echo $wrapper_background ?>; }
/*.menu-site ul { box-shadow: 0 -2px 0 <?php echo $wrapper_background ?>; }*/

/* Posts background colour */
.entry-block, .content-block, .reveal-modal.multi-block .content-block, .widget_search #s:focus, .widget input[type=text]:focus, .widget input[type=password]:focus, .widget input[type=email]:focus, .widget input[type=number]:focus, .widget textarea:focus, .widget input[type=text]:active, .widget input[type=password]:active, .widget input[type=email]:active, .widget input[type=number]:active, .widget textarea:active, .widget th, .widget tfoot td, .format-status .meta, .format-quote .entry blockquote, .audiojs .progress, .comments-section, .campaign-pledge-levels.accordion .pledge-level, .contact-page .ninja-forms-form-wrap { background-color: <?php echo $posts_background ?>; }
.entry-block { box-shadow: 0 0 1px <?php echo $posts_background ?>; }
.sticky.block { border-color: <?php echo $posts_background ?>; }

/* Footer text */
#site-footer, #site-footer a, .edd_errors, .edd_errors a { color: <?php echo $footer_text ?>; }    
#rockbottom { border-color: <?php echo $this->rgb( $this->get_rgb_from_hex( $footer_text ), 0.5 ) ?>; }

/* Footer widget titles */
.footer-widget .widget-title, #rockbottom { color: <?php echo $footer_titles ?>; }

/* Header buttons */
.social a, .account-links .button.button-alt, .account-links .button.button-alt:before, .logout, .logout:before, .logout:hover { color: <?php echo $header_buttons ?>; }
.social a:hover:before { color: <?php echo $header_buttons_hover ?>; }
.account-links .button.button-alt:hover, .account-links .button.button-alt:hover:before { background-color: <?php echo $header_buttons_hover ?>; border-color: <?php echo $header_buttons_hover ?>; }

@media all and (min-width: 50em) {
    #login-form .register-block { background-color: <?php echo $widget_background ?>; }
    #login-form .register-block input[type=text], #login-form .register-block input[type=password], #login-form .register-block input[type=email] { background-color: <?php echo $wrapper_background ?>; }
}

/* Text selection */
*::selection { background-color:<?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; } 
*::-moz-selection { background-color:<?php echo $accent_colour ?>; color: <?php echo $accent_text ?>; }
.active-campaign::selection, .active-campaign *::selection { background-color:<?php echo $accent_text_secondary ?>; color: <?php echo $accent_text ?>; }
.active-campaign::-moz-selection, .active-campaign *::-moz-selection { background-color:<?php echo $accent_text_secondary ?>; color: <?php echo $accent_text ?>; }


<?php if ( $logo ) : ?>            
/* Logo */
.site-identity { background: url(<?php echo $logo ?>) no-repeat left 50%; padding-left: <?php echo $logo_meta['width'] + 10 ?>px; min-height: <?php echo $logo_meta['height'] ?>px; }
.no-tagline .site-title { line-height: <?php echo $logo_meta['height'] ?>px; }
.no-title .site-tagline { line-height: <?php echo $logo_meta['height'] - 12 ?>px; }
.no-title.no-tagline .site-navigation .menu-site { margin-top: <?php echo $logo_meta['height'] - 18 ?>px;  }
    <?php if ( $logo_meta['height'] > 34 ) : ?>
    .no-tagline .site-navigation .menu-site { margin-top: <?php echo $logo_meta['height'] - 18 ?>px; }
    .no-title .site-navigation .menu-site { margin-top: <?php echo $logo_meta['height'] - 18 ?>px; }
    <?php endif ?>
<?php endif ?>
                
/* Greater than 600px width */
@media all and (min-width: 37.5em) {
.author-bio { background-color: <?php echo $posts_background ?>; }
}

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
.site-identity { background-image:url(<?php echo $retina_logo ?>); background-size: <?php echo $logo_meta['width'] ?>px <?php echo $logo_meta['height'] ?>px; } 
<?php endif ?>
}
        <?php
        // Extra stuff to print if we're inside the customizer -- some styles are getting whacked
        if ( $this->in_customizer ) : ?>
.site-navigation .menu-site > li, .menu-site li.hovering { height: 1em; }
        <?php
        endif;

        // That's all folks
        ?>
</style>        
        
        <?php 
        $html = ob_get_clean();

        echo $html;

        // TODO: Cache the output
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