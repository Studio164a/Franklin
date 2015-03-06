<?php 
/**
 * Bootstraps the theme and all its associated functionality. 
 * 
 * This class can only be instantiated once and cannot be extended.
 * 
 * @author  Studio 164a
 */

if ( ! class_exists( 'Franklin_Theme' ) ) :

class Franklin_Theme {

    /**
     * Version number.
     */
    const VERSION = '1.7.0';

    /**
     * @var Franklin_Theme
     */
    private static $instance = null;

    /** 
     * @var bool
     */
    public $crowdfunding_enabled = false;

    /**
     * @var bool
     */
    public $wpml_enabled = false;

    /**
     * @var bool
     */
    private $social_login_enabled;

    /**
     * @var int
     */
    private $theme_db_version;

    /**
     * @var string
     */ 
    private $stylesheet;

    /**
     * Private constructor. Singleton pattern.
     *
     * @access  private
     * @since   1.0.0
     */
	private function __construct() {                 
        $this->theme_dir    = trailingslashit( get_template_directory() );
        $this->theme_uri    = trailingslashit( get_template_directory_uri() );
        $this->stylesheet   = get_option('stylesheet');

        $this->start();
  	}

    /**
     * Get class instance.
     *
     * @static
     * @return  Franklin_Theme
     * @access  public
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
          self::$instance = new Franklin_Theme();
        }
        return self::$instance;
    }    

    /**
     * Start the plugin. 
     *
     * @return  void
     */
    private function start() {
        // If the franklin_theme_start action has been run or the action 
        // is currently running, it means that this is being called on a later 
        // instantiation. Do not proceed.
        if ( $this->started() ) {
            return false;
        }

        $this->setup_sofa_framework();    

        $this->load_dependencies();    
        
        $this->maybe_start_admin();

        $this->maybe_start_crowdfunding();

        $this->maybe_start_wpml();

        $this->maybe_start_customizer();

        $this->maybe_update_version();

        $this->maybe_migrate_hide_meta();

        $this->attach_hooks_and_filters(); 

        // Allow child themes to perform actions upon bootstrap.
        // Use this hook to unset any of the event hooks attached in this method.
        do_action('franklin_theme_start', $this);
    }

    /**
     * Checks whether the theme has already started. 
     *
     * @return  bool
     * @access  public
     * @since   1.6.0
     */
    public function started() {
        return did_action('franklin_theme_start') || current_filter() == 'franklin_theme_start';
    }

    /**
     * Checks whether we are currently on the franklin_theme_start hook. 
     *
     * @return  bool
     * @access  public
     * @since   1.6.0
     */
    public function is_start() {
        return current_filter() == 'franklin_theme_start';
    }
    /**
     * Include required files. 
     *
     * @return  void
     * @access  private
     */
    private function load_dependencies() {
        require_once( $this->theme_dir . 'inc/class-franklin-customizer-styles.php' );
        require_once( $this->theme_dir . 'inc/functions-franklin-comments.php' );
        require_once( $this->theme_dir . 'inc/widgets/sofa-posts.php' );
    }

    /**
     * Load up the Sofa Framework.
     *
     * @return  void
     * @access  private
     */
    private function setup_sofa_framework() {
        require_once( $this->theme_dir . 'inc/sofa/sofa.php' );

        $this->sofa = get_sofa_framework();
    }

    /**
     * Check if crowdfunding is ready, and if so, start it up.
     *
     * @return  void
     * @access  private
     */
    private function maybe_start_crowdfunding() {
        if ( class_exists('Easy_Digital_Downloads') && class_exists('ATCF_CrowdFunding')) {

            $this->crowdfunding_enabled = true;

            include_once( $this->theme_dir . 'inc/crowdfunding/crowdfunding.class.php' );            
        }
    }

    /**
     * Check if WPML is installed and if so, load the helper files.
     *
     * @return  void
     * @access  private
     */
    private function maybe_start_wpml() {
        if ( defined('ICL_SITEPRESS_VERSION') ) {
            
            $this->wpml_enabled = true;
            
            require_once( $this->theme_dir . 'inc/wpml/wpml.class.php' );
            require_once( $this->theme_dir . 'inc/wpml/helpers.php' );
        }
    }

    /**
     * Start up admin-only functionality.
     *
     * @return  void
     * @access  private
     */
    private function maybe_start_admin() {
        if ( ! is_admin() ) {
            return;
        }

        // Load custom editor styles
        require_once( $this->theme_dir . 'inc/admin/editor-styles.php' );

        $editor = Sofa_Editor_Styles::get_instance();
    }

    /**
     * Set up the customizer's backend interface. 
     *
     * @global  $wp_customize
     * 
     * @return  void
     * @access  private
     * @since   1.6.0
     */
    private function maybe_start_customizer() {
        global $wp_customize;

        if ( $wp_customize ) {

            $this->in_customizer = true;
    
            require_once( $this->theme_dir . 'inc/admin/class-franklin-customizer.php' );
            require_once( $this->theme_dir . 'inc/admin/customize-controls/textarea.class.php' );

            Franklin_Customizer::start( $this, $wp_customize );
        }
    }

    /**
     * Check for theme version update.
     * 
     * @return  void
     * @access  private
     */
    private function maybe_update_version() {
        $this->theme_db_version = mktime(0,0,0,11,24,2014);
        
        // Check whether we are updated to the most recent version
        $db_version = get_option('franklin_db_version', false);

        if ( $db_version === false || $db_version < $this->theme_db_version ) {
            require_once( $this->theme_dir . 'inc/class-franklin-upgrade-helper.php' );        

            Franklin_Upgrade_Helper::do_upgrade($this->theme_db_version, $db_version);

            update_option('franklin_db_version', $this->theme_db_version);
        }    
    }    

    /**
     * If the Hide Meta plugin is installed, migrate to that. 
     *
     * @return  void
     * @access  private
     * @since   1.6.0
     */
    private function maybe_migrate_hide_meta() {
        if ( ! function_exists( 'hide_meta_start' ) ) {
            return;
        }

        $has_migrated = get_option( 'franklin_hide_meta_migrated', false );

        if ( $has_migrated ) {
            return;
        }

        require_once( $this->theme_dir . 'inc/class-franklin-upgrade-helper.php' );

        Franklin_Upgrade_Helper::do_hide_meta_upgrade();

        update_option( 'franklin_hide_meta_migrated', true );
    }

    /**
     * Set up hooks and filters. 
     *
     * @return  void
     * @access  private
     */
    private function attach_hooks_and_filters() {
        add_action('franklin_theme_start', array('Franklin_Customizer_Styles', 'franklin_theme_start'));        

        add_action('wp_head', array(&$this, 'wp_head'), 20);
        add_action('wp_footer', array(&$this, 'wp_footer'));
        add_action('widgets_init', array(&$this, 'widgets_init'));
        add_action('after_setup_theme', array(&$this, 'after_setup_theme'));        
        add_action('edd_after_install', array(&$this, 'edd_after_install'));
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'));
        
        if ( !is_admin() ) {
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 20);
        }

        add_filter('get_pages',  array(&$this, 'get_pages_filter'));    
        add_filter('post_class', array(&$this, 'post_class_filter'));
        add_filter('the_content_more_link', array(&$this, 'the_content_more_link_filter'), 10, 2);
        add_filter('next_posts_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('previous_posts_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('next_comments_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('previous_comments_link_attributes', array(&$this, 'posts_navigation_link_attributes'));        
        add_filter('sofa_enabled_modules', array(&$this, 'sofa_enabled_modules'));
        add_filter('sofa_load_lt_ie9', array(&$this, 'sofa_load_lt_ie9'));
        add_filter('sofa_link_format_title', array(&$this, 'sofa_link_format_title_filter'));

        // Stop LayerSlider's scripts from being added to every page.
        remove_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res');
    }

    /**
     * Enqueue stylesheets and scripts.
     *
     * @return  void
     */
    public function wp_enqueue_scripts() {      
        // Stylesheets
        wp_register_style('franklin-main', $this->theme_uri . "/style.css", array(), $this->get_theme_version());
        wp_enqueue_style('franklin-main');

        wp_register_style( 'foundation', sprintf( "%s/media/css/foundation.css", $this->theme_uri ), array(), $this->get_theme_version());
        wp_enqueue_style( 'foundation' );

        // Load up Ninja Forms CSS if the plugin is on
        if ( defined( 'NINJA_FORMS_VERSION' ) ) {
            wp_register_style( 'franklin-ninja-forms', sprintf( "%s/media/css/franklin-ninja-forms.css", $this->theme_uri ), array('franklin-main'), $this->get_theme_version() );
            wp_enqueue_style( 'franklin-ninja-forms' );
        }
        
        // Scripts    
        $in_footer = function_exists('edd_is_checkout') && edd_is_checkout() ? false : true;

        wp_register_script('sofa', sprintf( "%s/media/js/sofa.js", $this->theme_uri ), array('jquery'), $this->get_theme_version(), $in_footer);

        wp_register_script('audio-js', sprintf( "%s/media/js/audiojs/audio.min.js", $this->theme_uri ), array(), $this->get_theme_version(), true);
        wp_register_script('foundation', sprintf( "%s/media/js/foundation.min.js", $this->theme_uri ), array('jquery'), $this->get_theme_version(), true);
        // wp_register_script('sharrre', sprintf( "%s/media/js/jquery.sharrre-1.3.5.js", $this->theme_uri ), array('jquery'), $this->get_theme_version(), true );        
        wp_register_script('rrssb', sprintf( "%s/media/js/rrssb.min.js", $this->theme_uri ), array('jquery'), $this->get_theme_version(), true );
        
        // Allow other scripts to add their scripts to the dependencies.
        $franklin_script_dependencies = apply_filters( 'franklin_script_dependencies', array( 
            'sofa', 
            'prettyPhoto', 
            'jquery-ui-accordion', 
            'audio-js', 
            'rrssb',
            // 'sharrre', 
            'hoverIntent', 
            'foundation', 
            'jquery' 
        ) );

        wp_register_script('franklin', sprintf( "%s/media/js/main.js", $this->theme_uri ), $franklin_script_dependencies, $this->get_theme_version(), true);

        wp_enqueue_script('jquery');
        wp_enqueue_script('sofa');
	    wp_enqueue_script('franklin');

        wp_localize_script('sofa', 'Franklin', array(
            'sharrre_url'           => get_template_directory_uri() . '/inc/sharrre/sharrre.php', 
            'need_minimum_pledge'   => __( 'Your pledge must be at least the minimum pledge amount.', 'franklin' ), 
            'years'                 => __( 'Years', 'franklin' ), 
            'months'                => __( 'Months', 'franklin' ), 
            'weeks'                 => __( 'Weeks', 'franklin' ), 
            'days'                  => __( 'Days', 'franklin' ), 
            'hours'                 => __( 'Hours', 'franklin' ), 
            'minutes'               => __( 'Minutes', 'franklin' ), 
            'seconds'               => __( 'Seconds', 'franklin' ), 
            'year'                  => __( 'Year', 'franklin' ), 
            'month'                 => __( 'Month', 'franklin' ), 
            'day'                   => __( 'Day', 'franklin' ), 
            'hour'                  => __( 'Hour', 'franklin' ), 
            'minute'                => __( 'Minute', 'franklin' ), 
            'second'                => __( 'Second', 'franklin' ), 
            'timezone_offset'       => $this->get_timezone_offset(),
            'using_crowdfunding'    => $this->crowdfunding_enabled
        ) ); 
    } 

    /**
     * Executes on the wp_head hook
     * 
     * @return  void
     * @since   1.0.0
     */
    public function wp_head () {
        // If this is the contact page, don't append the Ninja Forms form with the_content filter
        if ( is_page_template('page-contact.php') ) {
            remove_filter( 'the_content', 'ninja_forms_append_to_page', 9999 );
        }
    }  

    /**
     * Loading the fonts in the footer instead of the header to improve loading times. 
     * 
     * @return  void
     * @since   1.5.8
     */
    public function wp_footer() {
        echo apply_filters( 'franklin_font_link', "<link href='//fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300|Oswald:400,300' rel='stylesheet' type='text/css'>" );
    }  

    /**
     * Executes on the after_setup_theme hook. 
     *
     * @return  void
     */
    public function after_setup_theme () {
        // Set up localization
        load_theme_textdomain( 'franklin', $this->theme_dir . '/languages' );

        // Set up the Sofa Framework
        add_theme_support('sofa_framework');

        // Add support for the Hide Meta plugin.
        add_theme_support( 'hide-meta' );

        // Post formats
        add_theme_support( 'post-formats', array( 'image', 'quote', 'gallery', 'video', 'aside', 'link', 'status', 'audio', 'chat' ) );

        // Automatic feed links
        add_theme_support( 'automatic-feed-links' );

        // Enable post thumbnail support 
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 706, 0, false );
        add_image_size( 'widget-thumbnail', 294, 882, false );

        // Register menu
        register_nav_menus( array(
            'primary_navigation' => 'Primary Navigation'
        ) );        
    }

    /**
     * Executes on the widgets_init hook
     * @return void
     */
    public function widgets_init() {
        register_sidebar( array(
            'id'            => 'default',            
            'name'          => __( 'Default sidebar', 'franklin' ),
            'description'   => __( 'The default sidebar.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'sidebar_campaign',            
            'name'          => __( 'Campaign sidebar', 'franklin' ),
            'description'   => __( 'The campaign sidebar.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'campaign_after_content',            
            'name'          => __( 'Campaign below content', 'franklin' ),
            'description'   => __( 'Displayed below the campaign\'s content, but above the comment section.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget block content-block cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h2 class="block-title">',
            'after_title'   => '</h2></div>'
        ));        

        register_sidebar( array(
            'id'            => 'footer_left',            
            'name'          => __( 'Footer left', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        )   );

        register_sidebar( array(
            'id'            => 'footer_right',            
            'name'          => __( 'Footer right', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));

        register_widget( 'Sofa_Posts_Widget' );
    }    

    /**
     * Filter the query to allow us to use a download (i.e. campaign) as the front page.
     * 
     * @param   WP_Query $query           Passed by reference. Any changes made here are made to the global query.
     * @return  void
     * @since   1.0.0
     */
    function pre_get_posts($query) {
        if ( $query->is_main_query() ) {
            if ( 'page' == get_option( 'show_on_front') && get_option( 'page_on_front' ) && $query->query_vars['page_id'] == get_option( 'page_on_front' ) ) {
                if ( get_post_type( $query->query_vars['page_id'] ) == 'download' ) {
                    $query->query_vars['post_type'] = array( 'download', 'page' );
                }
            }
        }      
    }

    /**
     * Runs after Easy Digital Downloads installation. 
     * 
     * @see     edd_after_install
     * @param   array $activation_pages       Pages created by EDD upon activation.
     * @return  void
     * @since   1.0.0
     */
    public function edd_after_install( $activation_pages ) {
        foreach ( $activation_pages as $page ) {
            update_post_meta( $page, '_wp_page_template', 'page-fullwidth.php' );
        }
    }

    /**
     * Filters the "more" link on post archives.
     *
     * @return  string
     * @since   1.0.0
     */
    public function the_content_more_link_filter($more_link, $more_link_text = null) {
        $post = get_post();
        return '<span class="aligncenter"><a href="'.get_permalink().'" class="more-link button button-alt" title="'.sprintf( __('Keep reading %s', 'franklin'), "&#8220;".get_the_title()."&#8221;" ).'">'.__( 'Continue Reading', 'franklin' ).'</a></span>';
    }

    /**
     * Filters the next & previous posts links.
     * 
     * @return  string
     * @since   1.0.0
     */
    public function posts_navigation_link_attributes() {
        return 'class="button button-alt button-small"';
    }

    /**
     * Filters the pages to display when showing a list of pages.
     *
     * @param   array   $pages
     * @return  array
     * @since   1.0.0
     */
    public function get_pages_filter($pages) {
        $campaigns = new WP_Query( array( 'post_type' => 'download' ) );
        
        if ( $campaigns->post_count > 0 ) {
            $pages = array_merge($campaigns->posts, $pages);
        }

        return $pages;
    }

    /**
     * Filters the post class.
     * 
     * @param   array   $classes
     * @return  array
     * @since   1.0.0
     */
    public function post_class_filter($classes) {
        if (has_post_thumbnail()) {
            $classes[] = 'has-featured-image';
        }

        return array_merge( $classes, array('block', 'entry-block') );
    }

    /**
     * Filters the enabled modules to be set up by Sofa. 
     *
     * @param   array   $modules
     * @return  array
     * @since   1.0.0
     */
    public function sofa_enabled_modules($modules) {
        array_push( $modules, 'prettyPhoto' );
        return $modules;
    }

    /**
     * Filters the conditional scripts output for IE8 and lower.
     * 
     * @param   string  $default
     * @return  string
     * @since   1.0.0
     */
    public function sofa_load_lt_ie9($default) {
        return '<link rel="stylesheet" type="text/css" media="all" href="' . $this->theme_uri . '/media/css/ie8.css" />' 
        . '<script>var sofa_ie_lt9 = true;</script>' 
        . $default;
    }

    /**
     * Filter the title of the link post format. 
     * 
     * @param   string  $title
     * @return  string
     * @since   1.0.0
     */
    public function sofa_link_format_title_filter($title) {
        return '<a class="with-icon" data-icon="&#xf0C1;"' . substr( $title, 2 );
    }

    /**
     * Gets the timezone offset. 
     * 
     * @return  string
     * @since   1.5.5
     */
    public function get_timezone_offset() {        
        if ( franklin_using_crowdfunding() === false ){
            return;
        }
        
        $timezone = edd_get_timezone_id();
        $date_timezone = new DateTimeZone($timezone);
        $date_time = new DateTime('now', $date_timezone);
        $offset_secs = $date_time->format('Z');
        $offset = $date_time->format('P');
        $offset = str_replace( ':', '.', $offset );

        if ( $offset_secs >= 0 ) {
            return $offset;
        }
        return str_replace( '+', '-', $offset );
    }

    /**
     * Returns whether Social Login is activated and has login options set up.
     *
     * @return  boolean
     * @since   1.6.0
     */
    public function is_using_social_login() {
        if ( ! isset( $this->social_login_enabled ) ) {
            if ( ! class_exists( 'EDD_Slg_Shortcodes' ) ) {
                $this->social_login_enabled = false;
            }
            else {
                $class = new EDD_Slg_Shortcodes();
                $login_form = $class->edd_slg_social_login( array( 'title' => ' ' ), '' );

                if ( strlen( $login_form ) ) {
                    $this->social_login_enabled = true;
                } 
                else {
                    $this->social_login_enabled = false;
                }
            }
        }

        return $this->social_login_enabled;
    }

    /**
     * Returns the current version number. 
     * 
     * @return  int
     * @since   1.5.8
     */
    public function get_theme_version() {
        if ( defined('FRANKLIN_DEBUG') && FRANKLIN_DEBUG ) {
            return time();
        }

        return self::VERSION;
    }
}

endif; // End class_exists check