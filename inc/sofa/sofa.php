<?php 
/* 
 * Plugin name: SOFA Framework 
 * Plugin URI: http://164a.com
 * Description: The Studio164a theme framework. Not a theme.
 * Version: 0.2
 * Author: Studio164a
 * Author URI: http://164a.com
 * Requires at least: 3.5
 * Tested up to: 3.5
 *
 * Text Domain: sofa
 * Domain Path: /languages/
 *
 * @package Sofa
 * @category Core
 * @author Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Sofa_Framework') ) {

/**
 * Main SOFA framework class. 
 * 
 * @class Sofa_Framework
 * @since 0.1
 * @package Sofa
 * @author Studio164a
 */
class Sofa_Framework {

	/**
	 * @var Sofa_Framework
	 * @access private
	 */
	private static $instance;

	/**
	 * @var string
	 */
	public $version = '0.1';

	/**
	 * @var string
	 */
	public $plugin_dir;

	/**
	 * @var string
	 */
	public $plugin_dir_url;	

	/**
	 * @var array
	 */
	private $enabled_scripts;

	/**
	 * @var array
	 */
	private $enabled_stylesheets;

	/** 
	 * @var array
	 */
	private $modules;

	/** 
	 * @var array
	 */
	private $enabled_modules;

	/**
	 * @var array
	 */
	private $social_sites;

    /**
     * @var boolean
     */
    private $using_ecommerce = false; 

    /**
     * @var array
     */
    private $ecommerce_providers = array(); 

	/** 
	 * Constructor
	 * 
	 * @access private
	 * @return void
	 * @since Sofa 0.1
	 */
	private function __construct() {
		// Don't do anything until init. This allows themes to declare support 
		// for Sofa with add_theme_support on the after_setup_theme hook.
		add_action('init', array(&$this, 'init'));
	}	

	/**
	 * Get framework instance. 
	 *
	 * @access public
	 * @return Sofa_Framework
	 * @static
	 * @since Sofa 0.1
	 */
	public static function get_instance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new Sofa_Framework();
		}
		return self::$instance;
	}

	/**
	 * Theme setup
	 * 
	 * @access public
	 * @return void 
	 * @since Sofa 0.1
	 */
	public function init() {

		// If this theme does not support the Sofa Framework, we're done.
		if ( !current_theme_supports('sofa_framework') )
			return;

		// Naming convention: 
		//
		// Action hooks are named exactly the same as the name of the hook. 
		// Filter hooks take the form of hook_name_filter. 
		add_action('wp_head', array(&$this, 'wp_head'), 30);
		add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));

		add_filter('body_class', array(&$this, 'body_class_filter'));
		add_filter('wp_title', array(&$this, 'wp_title_filter'), 10, 2);
		add_filter('use_default_gallery_style', array(&$this, 'use_default_gallery_style_filter'));

		// The scripts that will be loaded by default. Themes can modify this
		// by using the sofa_enabled_scripts filter.
		$this->enabled_scripts = apply_filters( 'sofa_enabled_scripts',
			array( 
				'flexnav' => array( 'file' => 'jquery.flexnav.js', 'dependencies' => array('jquery'), 'load_in_footer' => true )
			)
		);		

		// Default stylesheets. Filter this using sofa_enabled_stylesheets
		$this->enabled_stylesheets = apply_filters( 'sofa_enabled_stylesheets',
			array( 
				'reset' => array( 'file' => 'reset.css' )
			)
		);

		// Social sites
		$this->social_sites = apply_filters( 'sofa_social_sites', 
			array(				
				'bitbucket'		=> __( 'Bitbucket', 'sofa' ), 			    			    			    
			    'dribbble'		=> __( 'Dribbble', 'sofa' ), 
			    'facebook' 		=> __( 'Facebook', 'sofa' ), 			    
			    'flickr'		=> __( 'Flickr', 'sofa' ), 
			    'foursquare'	=> __( 'Foursquare', 'sofa' ), 
			    'github'		=> __( 'Github', 'sofa' ), 
			    'google-plus' 	=> __( 'Google+', 'sofa' ),                     			    
			    'gittip'		=> __( 'Gittip', 'sofa' ),
			    'instagram'		=> __( 'Instagram', 'sofa' ),
			    'linkedin' 		=> __( 'Linkedin', 'sofa'),
			    'pinterest' 	=> __( 'Pinterest', 'sofa' ), 
			    'renren'		=> __( 'Renren', 'sofa' ), 
			    'skype'			=> __( 'Skype', 'sofa' ), 
			    'stackexchange' => __( 'Stackexchange', 'sofa' ), 
			    'trello' 		=> __( 'Trello', 'sofa' ), 
			    'tumblr'		=> __( 'Tumblr', 'sofa' ), 
			    'twitter' 		=> __( 'Twitter', 'sofa' ), 
			    'vk'			=> __( 'VK', 'sofa' ), 
			    'weibo'			=> __( 'Weibo', 'sofa' ), 
			    'windows' 		=> __( 'Windows', 'sofa' ), 
			    'xing'			=> __( 'Xing', 'sofa' ), 
			    'youtube'		=> __( 'YouTube', 'sofa' )
			) 
		);

		// Store the plugin directory
		$this->plugin_dir_url = apply_filters( 'sofa_directory_url', get_template_directory_uri() . '/inc/sofa' );
		$this->plugin_dir = apply_filters( 'sofa_directory', get_template_directory() . '/inc/sofa' );

		// Supported modules. Filter this using sofa_modules
		$module_dir = $this->plugin_dir . '/modules/';
		
		$this->modules = apply_filters( 'sofa_modules', array( 
			'fitvids' 		=> $module_dir . 'fitvids/fitvids.php',
			'fontawesome' 	=> $module_dir . 'fontawesome/fontawesome.php', 
			'prettyPhoto'	=> $module_dir . 'prettyPhoto/prettyPhoto.php'
		) );

		// Enabled modules
		$this->enabled_modules = apply_filters( 'sofa_enabled_modules', array( 'fitvids', 'fontawesome' ) );

		$this->load_modules();		

		// Include files
		include_once('sofa-template-tags.php');
		include_once('sofa-helpers.php');
		include_once('sofa-shortcodes.php');

		do_action('sofa_init');
	}

	/**
	 * Enqueues scripts and stylsheets. 
	 * 
	 * @return void
	 * @access public
	 */
	public function wp_enqueue_scripts() {
		// Javascript default arguments
		$js_defaults = array( 'dependencies' => array(), 'version' => $this->version, 'load_in_footer' => true );

		foreach ( $this->enabled_scripts as $name => $args ) {

			$args = array_merge( $js_defaults, $args );
			wp_register_script( 
				$name, 
				sprintf( "%s/js/%s", $this->plugin_dir_url, $args['file'] ), 
				$args['dependencies'], 
				$args['version'], 
				$args['load_in_footer'] 
			);
			wp_enqueue_script( $name );
		}	

		// CSS default arguments
		$css_defaults = array( 'dependencies' => array(), 'version' => $this->version, 'media' => 'all' );

		foreach ( $this->enabled_stylesheets as $name => $args ) {

			$args = array_merge( $css_defaults, $args );
			wp_register_style( 
				$name, 
				sprintf( "%s/css/%s", $this->plugin_dir_url, $args['file'] ), 
				$args['dependencies'], 
				$args['version'], 
				$args['media'] 
			);
			wp_enqueue_style( $name );
		}	
	}

	/**
	 * Fired on wp_head hook. 
	 * 
	 * @return void
	 * @access public
	 */
	public function wp_head() {
		$lt_ie9 = apply_filters('sofa_load_lt_ie9', 
'<script src="'. $this->plugin_dir_url .'/js/html5shiv.js" type="text/javascript"></script>
<script src="'. $this->plugin_dir_url .'/js/selectivizr-min.js" type="text/javascript"></script>
<script src="'. $this->plugin_dir_url .'/js/PIE.js" type="text/javascript"></script>');
		?>
<!--[if lt IE 9]>
<?php echo $lt_ie9 ?>
<![endif]-->
		<?php
	}

    /**
     * Fires on the admin_menu hook.
     *
     * @return void
     */
    public function admin_menu() {
        add_theme_page(__('Customize', 'sofa'), __('Customize', 'sofa'), 'edit_themes', 'customize.php');
    }

    /** 
     * Filter the body classes. Add browser type to body class.
     * Credit: http://www.nathanrice.net/blog/browser-detection-and-the-body_class-function/
     * 
     * @param array $classes
     * @return array
     * @since Sofa 0.1
     */
    public function body_class_filter($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

        // Browsers
        if($is_lynx) $classes[] = 'lynx';
        elseif($is_gecko) $classes[] = 'gecko';
        elseif($is_opera) $classes[] = 'opera';
        elseif($is_NS4) $classes[] = 'ns4';
        elseif($is_safari) $classes[] = 'safari';
        elseif($is_chrome) $classes[] = 'chrome';
        elseif($is_IE) $classes[] = 'ie';
        else $classes[] = 'unknown';
        if($is_iphone) $classes[] = 'iphone';
        
        return $classes;
    }

    /**
     * Set up the title in the head of document.
     * 
     * @param string $title
     * @param string $sep
     * @return string
     * @since Sofa 0.1
     */
    public function wp_title_filter( $title, $sep ) {
        global $paged, $page;

        if ( is_feed() )
            return $title;

        // Add the site name.
        $title .= ' ' . $sep . ' ' . get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title = "$title $sep $site_description";

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 )
            $title = "$title $sep " . sprintf( __( 'Page %s', 'paperplane' ), max( $paged, $page ) );

        return $title;
    }    

    /**
     * Don't use Wordpress' default gallery shortcode styling. 
     *
     * @return false
     * @since Sofa 0.1
     */ 
    public function use_default_gallery_style_filter() {
    	return false;
    }

	/** 
	 * Load modules. 
	 * 
	 * @access private
	 * @return void
	 * @since Sofa 0.1
	 */
	private function load_modules() {
		foreach ( $this->enabled_modules as $module ) {
			if ( array_key_exists( $module, $this->modules ) ) {
				include_once( $this->modules[$module] );
			}
		}
	}

    /**
     * Enable ecommerce.
     * 
     * @param array $providers
     * @return void
     * @since Sofa 0.1
     */
    public function enable_ecommerce($providers = array()) {

    	// Note the ecommerce providers
    	$this->ecommerce_providers = $providers;

    	if ( in_array( 'woocommerce', $providers ) && class_exists('Woocommerce') ) {

    		// Yep, we're using ecommerce
    		$this->using_ecommerce = true;

    		// Includes
    		// include_once( 'ecommerce/interface.class.php' );
    		// include_once( 'ecommerce/woocommerce/woocommerce.class.php' );

    		// Add theme support
            add_theme_support('woocommerce');
    	}
    	elseif ( in_array( 'jigoshop', $providers ) && class_exists('Jigoshop') ) {

    		// Yep, we're using ecommerce
    		$this->using_ecommerce = true;
    	}
    }

    /**
     * Returns whether the current theme is using ecommerce. 
     * 
     * @return bool
     */
    public function using_ecommerce() {
        return $this->using_ecommerce;
    }

    /**
     * Returns whether the current theme is using woocommerce. 
     * 
     * @return bool
     */
    public function using_woocommerce() {
        if ( $this->using_ecommerce() === false )
            return false;

        return in_array( 'woocommerce', $this->ecommerce_providers );
    }

    /**
     * Returns whether the current theme is using Jigoshop 
     * 
     * @return bool
     */
    public function using_jigoshop() {
        if ( $this->using_ecommerce() === false )
            return false;

        return in_array( 'jigoshop', $this->ecommerce_providers );
    }    

    /**
     * Return array of enabled social sites.
     * 
     * @return array
     * @since Sofa 0.1
     */
    public function get_social_sites() {
    	return $this->social_sites;
    }

    /**
     * Return plugin directory. 
     * 
     * @return string
     * @since Sofa 0.1
     */
    public function get_directory() {
    	return $this->plugin_dir;
    }

    /**
     * Return plugin directory as a URL. 
     * 
     * @return string
     * @since Sofa 0.1
     */
    public function get_directory_url() {
    	return $this->plugin_dir_url;
    }    

    /**
     * Return module directory. 
     * 
     * @return string
     * @since Sofa 0.1
     */
    public function get_module_directory() {
    	return $this->plugin_dir . '/modules';
    }      

    /**
     * Return module directory as a URL.
     * 
     * @return string
     * @since Sofa 0.1
     */
    public function get_module_directory_url() {
    	return $this->plugin_dir_url . '/modules';
    }          
}

/**
 * Init Sofa_Framework class
 */
function get_sofa_framework() {	
	return Sofa_Framework::get_instance();
}

} // class_exists check