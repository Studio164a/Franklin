<?php 
/**
 * Bootstraps the theme and all its associated functionality. 
 * 
 * This class can only be instantiated once and cannot be extended.
 * 
 * @author Eric Daams <eric@ericnicolaas.com>
 * @final
 */

class Projection_Theme {

    /**
     * @var Projection_Theme
     */
    private static $instance = null;

    /** 
     * @var bool
     */
    public $crowdfunding_enabled = false;

    /**
     * Private constructor. Singleton pattern.
     */
	private function __construct() {         

        // Include Sofa and get its instance
        require_once('inc/sofa/sofa.php');
        $this->sofa = get_sofa_framework();

        // Include other files
        require_once('inc/comments.php');
  //       require_once('inc/shortcodes.php');
        require_once('inc/helpers.php');
        require_once('inc/template-tags.php');    
        require_once('inc/widgets/sofa-posts.php');

        // Admin classes
        require_once('inc/admin/customize.php');

        if ( class_exists('Easy_Digital_Downloads') && class_exists('ATCF_CrowdFunding')) {

            $this->crowdfunding_enabled = true;
            include_once('inc/crowdfunding/crowdfunding.class.php');            
        }

        add_action('wp_head', array(&$this, 'wp_head'));
        add_action('widgets_init', array(&$this, 'widgets_init'));
        add_action('wp_footer', array(&$this, 'wp_footer'), 1000);
        add_action('after_setup_theme', array(&$this, 'after_setup_theme'));        
        add_action('edd_after_install', array(&$this, 'edd_after_install'));
        
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'));

        if ( !is_admin() )
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);

        add_filter('sofa_enabled_scripts', array(&$this, 'sofa_enabled_scripts_filter'));
        add_filter('sofa_load_lt_ie9', array(&$this, 'sofa_load_lt_ie9'));
        add_filter('get_pages',  array(&$this, 'get_pages_filter'));    
        add_filter('post_class', array(&$this, 'post_class_filter'));
        add_filter('the_content_more_link', array(&$this, 'the_content_more_link_filter'), 10, 2);
        add_filter('next_posts_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('previous_posts_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('next_comments_link_attributes', array(&$this, 'posts_navigation_link_attributes'));
        add_filter('previous_comments_link_attributes', array(&$this, 'posts_navigation_link_attributes'));

  //       add_action('wp_head', array(&$this, 'wp_head'));
  //       add_action('after_setup_theme', array(&$this, 'after_setup_theme'));        
  //       add_action('admin_menu', array(&$this, 'admin_menu'));        
  //       add_action('admin_init', array(&$this, 'admin_init'));
  //       add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
  //       add_action('save_post', array(&$this, 'save_post'), 10, 2);
  //       add_action('wp_footer', array(&$this, 'wp_footer'));
  //       add_action('init', array(&$this, 'init'));		

  //       add_filter('wp_title', array(&$this, 'wp_title'), 10, 2);
  //       add_filter('body_class', array( &$this, 'body_class' ));
  //       add_filter('post_class', array(&$this, 'post_class'));
  //       add_filter('the_content_more_link', array(&$this, 'the_content_more_link'), 10, 2);        
  	}

    /**
     * Get class instance.
     *
     * @static
     * @return Projection_Theme
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new Projection_Theme();
        }
        return self::$instance;
    }    

    /**
     * Enqueue stylesheets and scripts
     * @return void
     */
    public function wp_enqueue_scripts() {      
    	// Theme directory
        $theme_dir = get_template_directory_uri();        

        // Stylesheets
        wp_register_style('main', get_bloginfo('stylesheet_url'));
        wp_enqueue_style('main');

        // wp_register_style('colours', $theme_dir . '/colors.css' );
        // wp_enqueue_style('colours');

        wp_register_style( 'foundation', sprintf( "%s/media/css/foundation.css", $theme_dir));
        wp_enqueue_style( 'foundation' );

        // Load prettyPhoto stylesheet
        // wp_register_style('prettyPhoto', sprintf( "%s/media/css/prettyPhoto.css", get_template_directory_uri() ));
        // wp_enqueue_style('prettyPhoto');
        
        // Scripts    
        // wp_register_script('sharrre', sprintf( "%s/media/js/jquery.sharrre-1.3.4.js", $theme_dir ), array('jquery'), 0.1, true);
        wp_register_script('audio-js', sprintf( "%s/media/js/audiojs/audio.min.js", $theme_dir ), array(), 0.1, true);
        wp_register_script('foundation', sprintf( "%s/media/js/foundation.min.js", $theme_dir ), array(), 0.1, true);
        wp_register_script('foundation-reveal', sprintf( "%s/media/js/foundation.reveal.js", $theme_dir ), array('foundation'), 0.1, true);        
        wp_register_script('main', sprintf( "%s/media/js/main.js", $theme_dir ), array( 'jquery-ui-accordion', 'audio-js', 'hoverIntent', 'foundation-reveal', 'jquery-ui-accordion', 'jquery'), 0.1, true);
	    wp_enqueue_script('main');

        // If Symple Shortcodes is installed, dequeue its stylesheet
        // if (function_exists('symple_shortcodes_scripts')) {
        //     wp_register_style('projection-symple-shortcodes', sprintf( "%s/media/css/symple-shortcodes.css", $theme_dir ) );
        //     wp_enqueue_style('projection-symple-shortcodes');
        // }
    } 

    /**
     * Executes on the wp_head hook
     * @return void
     */
    public function wp_head () {
        echo apply_filters( 'projection_font_link', "<link href='http://fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300|Oswald:400,300' rel='stylesheet' type='text/css'>" );

        ?>
        <script>var PROJECTION = {
            messages : {
                need_minimum_pledge : "<?php _e( 'Your pledge must be at least the minimum pledge amount.', 'projection' ) ?>"
            }
        }
        </script>
        <?php
    }

    /**
     * Executes on the after_setup_theme hook
     * @return void
     */
    public function after_setup_theme () {
        // Set up localization
        load_theme_textdomain( 'projection', get_template_directory() . '/languages' );

        // Set up the Sofa Framework
        add_theme_support('sofa_framework');

        // Post formats
        add_theme_support( 'post-formats', array( 'image', 'quote', 'gallery', 'video', 'aside', 'link', 'status', 'audio', 'chat' ) );

        // Automatic feed links
        add_theme_support( 'automatic-feed-links' );

        // Enable post thumbnail support 
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(699, 0, true);
        add_image_size('widget-thumbnail', 292, 9999, false);

        // Register menu
        register_nav_menus( array(
            'primary_navigation' => 'Primary Navigation'
        ) );        
    }

    /**
     * Executes on the wp_footer hook
     * 
     * @return void
     */
    public function wp_footer() {
        ?>
        <!-- <div class="loading-overlay"></div> -->

        <!-- Load scripts -->
        <script type="text/javascript">
        
            /* Twitter share button */
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

            /* Google Plus share button */
            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        </script>
        <?php   
    }

    /**
     * Executes on the widgets_init hook
     * @return void
     */
    public function widgets_init() {
        register_sidebar( array(
            'id' => 'default',            
            'name' => __( 'Default sidebar', 'projection' ),
            'description' => __( 'The default sidebar.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title' => '</h3></div>'
        ));  

        register_sidebar( array(
            'id' => 'sidebar_campaign',            
            'name' => __( 'Campaign sidebar', 'projection' ),
            'description' => __( 'The campaign sidebar.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title' => '</h3></div>'
        ));  

        register_sidebar( array(
            'id' => 'campaign_after_content',            
            'name' => __( 'Campaign below content', 'projection' ),
            'description' => __( 'Displayed below the campaign\'s content, but above the comment section.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget block content-block cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h2 class="block-title">',
            'after_title' => '</h2></div>'
        ));  

        register_sidebar( array(
            'id' => 'default',            
            'name' => __( 'Default sidebar', 'projection' ),
            'description' => __( 'The default sidebar.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title' => '</h3></div>'
        ));          

        register_sidebar( array(
            'id' => 'footer_left',            
            'name' => __( 'Footer left', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title' => '</h3></div>'
        ));

        register_sidebar( array(
            'id' => 'footer_right',            
            'name' => __( 'Footer right', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title' => '</h3></div>'
        ));

        register_widget( 'Sofa_Posts_Widget' );
    }    

    /**
     * Runs on admin_init hook
     *
     * @return void
     * @since projection 1.0
     */
    public function admin_init() {
        // Load custom editor styles
        require_once('inc/projection/admin/editor-styles.php');
        $editor = OSFEditorStyles::get_instance();
    }

    /**
     * Executes on the add_meta_boxes hook. 
     * 
     * @return void
     * @since projection 1.0
     */
    public function add_meta_boxes() {
        add_meta_box('projection_hide_post_meta', __( 'Hide post/page meta', 'projection' ), array( &$this, 'hide_post_meta' ), 'page' );
        add_meta_box('projection_hide_post_meta', __( 'Hide post/page meta', 'projection' ), array( &$this, 'hide_post_meta' ), 'post' );
    }

    /**
     * Hide post meta meta box
     *
     * @return void
     * @since projection 1.0
     */
    public function hide_post_meta($post) {
        // Use nonce for verification
        wp_nonce_field( 'projection_theme', '_projection_theme_nonce' );

        $value = get_post_meta( $post->ID, '_projection_hide_post_meta', true );
        ?>
        <label for="_projection_hide_post_meta">
            <?php _e( 'Hide post/page meta?', 'projection' ) ?>
            <input type="checkbox" id="projection_hide_post_meta" name="_projection_hide_post_meta" <?php checked($value) ?>>
        </label>
        <?php
    }

    /**
     * Executes on the save_post hook. Used to save the custom meta. 
     * 
     * @return void
     * @since projection 1.0
     */
    public function save_post($post_id, $post) {
        // Verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;        

        if ( isset( $_POST['post_type'] ) && in_array( $_POST['post_type'], array('post', 'page') )  ) {
            // Verify this came from the our screen and with proper authorization,
            // because save_post can be triggered at other times
            if ( !array_key_exists('_projection_theme_nonce', $_POST ) || !wp_verify_nonce( $_POST['_projection_theme_nonce'], 'projection_theme' ) )
                return;

             // Ensure current user can edit pages
            if ( !current_user_can( 'edit_page', $post_id ) && !current_user_can( 'edit_post', $post_id ) )
                return;

            // Save custom fields found in our $settings variable
            update_post_meta( $post_id, '_projection_hide_post_meta', ( $_POST['_projection_hide_post_meta'] == 'on' ? 1 : 0 ) );
        }
    }

    /**
     * Filter the query to allow us to use a download (i.e. campaign) as the front page.
     * 
     * @param WP_Query $query           Passed by reference. Any changes made here are made to the global query.
     * @return void
     * @since Projection 1.0
     */
    function pre_get_posts($query) {
        if ( $query->is_main_query() ) {
            if( !isset( $query->query_vars['post_type'] ) || '' == $query->query_vars['post_type'] && 0 != $query->query_vars['page_id'] )
                $query->query_vars['post_type'] = array( 'page', 'download' );
        }        
    }

    /**
     * Runs after Easy Digital Downloads installation. 
     * 
     * @see edd_after_install
     * @param array $activation_pages       Pages created by EDD upon activation.
     * @return void
     * @since Projection 1.0
     */
    public function edd_after_install( $activation_pages ) {
        foreach ( $activation_pages as $page ) {
            update_post_meta( $page, '_wp_page_template', 'page-fullwidth.php' );
        }
    }

    /**
     * Filters the "more" link on post archives.
     *
     * @return string
     * @since Projection 1.0
     */
    public function the_content_more_link_filter($more_link, $more_link_text = null) {
        $post = get_post();
        $text = $more_link_text == '(more&hellip;)' ? __( 'Continue Reading', 'projection' ) : $more_link_text;
        return '<span class="aligncenter"><a href="'.get_permalink().'" class="more-link button button-alt" title="'.sprintf( __('Keep reading &#8220;%s&#8221;', 'projection'), get_the_title() ).'">'.$text.'</a></span>';
    }

    /**
     * Filters the next & previous posts links.
     * 
     * @return string
     * @since Projection 1.0
     */
    public function posts_navigation_link_attributes() {
        return 'class="button button-alt button-small"';
    }

    /**
     * Filters the pages to display when showing a list of pages.
     *
     * @param array $pages
     * @return array
     * @since Projection 1.0
     */
    public function get_pages_filter($pages) {
        $campaigns = new WP_Query( array( 'post_type' => 'download' ) );
        
        if ( $campaigns->post_count > 0 )
            $pages = array_merge($campaigns->posts, $pages);

        return $pages;
    }

    /**
     * Filters the post class.
     * 
     * @param array $classes
     * @return array
     * @since Projection 1.0
     */
    public function post_class_filter($classes) {
        if (has_post_thumbnail())
            $classes[] = 'has-featured-image';

        return array_merge( $classes, array('block', 'entry-block') );
    }

    /**
     * Filters the scripts to be enqueued.
     * 
     * @param array $scripts
     * @return array
     * @since Projection 1.0
     */
    public function sofa_enabled_scripts_filter($scripts) {
        unset($scripts['prettyPhoto']);
        return $scripts;
    }

    /**
     * Filters the conditional scripts output for IE8 and lower.
     * 
     * @param string $default
     * @return string
     * @since Projection 1.0
     */
    public function sofa_load_lt_ie9($default) {
        return '<script src="'. $this->sofa->plugin_dir_url .'/js/respond.min.js" type="text/javascript"></script>' . PHP_EOL . $default;
    }
}

// Get the theme instance
function get_projection_theme() {
    return Projection_Theme::get_instance();
}

// Start 'er up
get_projection_theme();

// Set the content_width global
$content_width = 1077;