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
     * @var bool
     */
    private $crowdfunding_enabled = false;

    /**
     * Private constructor. Singleton pattern.
     */
	public function __construct() {         
        $this->sofa = get_sofa_framework();


        // Include other files
  //       require_once('inc/comments.php');
  //       require_once('inc/shortcodes.php');
        include_once('inc/helpers.php');
  //       require_once('inc/template-tags.php');    

        // Admin classes
        include_once('inc/admin/customize.php');

        if ( class_exists('Easy_Digital_Downloads') && class_exists('ATCF_CrowdFunding')) {

            $this->crowdfunding_enabled = true;
            include_once('inc/crowdfunding/crowdfunding.php');
            include_once('inc/crowdfunding/template.php');
        }

        add_action('widgets_init', array(&$this, 'widgets_init'));
        add_action('wp_footer', array(&$this, 'wp_footer'), 1000);
        add_action('after_setup_theme', array(&$this, 'after_setup_theme'));        

        if ( !is_admin() )
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);

        add_filter('sofa_enabled_modules', array(&$this, 'sofa_enabled_modules_filter'));

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
     * Enqueue stylesheets and scripts
     * @return void
     */
    public function wp_enqueue_scripts() {      
    	// Theme directory
        $theme_dir = get_template_directory_uri();        

        // Stylesheets
        wp_register_style('main', get_bloginfo('stylesheet_url'));
        wp_enqueue_style('main');

        wp_register_style( 'foundation', sprintf( "%s/media/css/foundation.css", $theme_dir));
        wp_enqueue_style( 'foundation' );

        // Skin (light or dark)
        // wp_register_style('skin', sprintf( "%s/media/css/%s.css", $theme_dir, get_theme_mod('skin', 'skin-dark') ) ); 
        // wp_enqueue_style('skin');

        // Load prettyPhoto stylesheet
        // wp_register_style('prettyPhoto', sprintf( "%s/media/css/prettyPhoto.css", get_template_directory_uri() ));
        // wp_enqueue_style('prettyPhoto');
        
        // Scripts    
        // wp_register_script('transit', sprintf( "%s/media/js/jquery.transit.min.js", $theme_dir ), array('jquery'), 0.1, true );       
        // wp_register_script('jquery-event-move', sprintf( "%s/media/js/jquery.event.move.js", $theme_dir ), array( 'jquery' ), 0.1, true );
        // wp_register_script('jquery-event-swipe', sprintf( "%s/media/js/jquery.event.swipe.js", $theme_dir ), array( 'jquery', 'jquery-event-move' ), 0.1, true );
        // wp_register_script('contentCarousel', sprintf( "%s/media/js/jquery.contentCarousel.js", get_template_directory_uri() ), array('jquery-event-swipe', 'transit'), 0.1, true );

        wp_register_script('foundation', sprintf( "%s/media/js/foundation.min.js", $theme_dir ), array(), 0.1, true);
        wp_register_script('foundation-reveal', sprintf( "%s/media/js/foundation.reveal.js", $theme_dir ), array('foundation'), 0.1, true);
        wp_register_script('raphael', sprintf( "%s/media/js/raphael-min.js", $theme_dir ), array(), 0.1, true);
        wp_register_script('main', sprintf( "%s/media/js/main.js", $theme_dir ), array('hoverIntent', 'raphael', 'foundation-reveal', 'jquery'), 0.1, true);        
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
        echo apply_filters( 'projection_font_link', "<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700|PT+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>" );
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
        // add_post_type_support('download', 'thumbnail');
        set_post_thumbnail_size(699, 0, true);
        add_image_size('carousel-thumbnail', 252, 9999, false);

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
        if ( $this->crowdfunding_enabled ) {
            $campaign = projection_get_campaign();
            ?>            
            <div id="campaign-form" class="reveal-modal">
                <a class="close-reveal-modal"><i class="icon-remove"></i></a>
                <?php echo edd_get_purchase_link( array( 'download_id' => $campaign->ID ) ); ?>
            </div>
            <?php
        }     

        ?>
        <!-- <div class="loading-overlay"></div> -->
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
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));  

        register_sidebar( array(
            'id' => 'footer',            
            'name' => __( 'Footer', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s column column_25">',
            'after_widget' => '</aside>',
            'before_title' => '<h4 class="widget_title">',
            'after_title' => '</h4>'
        ));

        register_sidebar( array(
            'id' => 'homepage-1',            
            'name' => __( 'Homepage 1', 'projection' ),
            'description' => __( 'First block of widgets on the homepage. Each widget is 33% width.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));  

        register_sidebar( array(
            'id' => 'homepage-2',            
            'name' => __( 'Homepage 2', 'projection' ),
            'description' => __( 'Second block of widgets on the homepage. Each widget is 50% width.', 'projection' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));  
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
     * Filters the post_class output. Add class to incdicate if there is a featured image.
     * 
     * @param array $classes
     * @return array
     */
    public function post_class($classes) {
        if (has_post_thumbnail())
            $classes[] = 'has_featured_image';

        return $classes;
    }    

    /**
     * Filters the "more" link on post archives
     * @return string
     */
    public function the_content_more_link($more_link, $more_link_text) {
        return str_replace( $more_link_text, __( 'Continue Reading', 'projection' ), $more_link );
    }

    /**
     * Set enabled modules for this theme
     * 
     * @param array $modules
     * @return array
     * @since projection 1.0
     */
    public function sofa_enabled_modules_filter($modules) {
        if ( !in_array('partials', $modules))
            $modules[] = 'partials';
        return $modules;
    }
}

$GLOBALS[''] = new Projection_Theme();

// Set the content_width global
$content_width = 1077;