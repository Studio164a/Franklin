<?php 
/**
 * This is a helper class. 
 * 
 * Uses the Singleton pattern. 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Sofa_Crowdfunding_Helper {

	/**
     * @var Sofa_Crowdfunding_Helper
     */
    private static $instance = null;

    /**
     * Private constructor. Singleton pattern.
     */
    private function __construct() {
    	add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
        if ( !is_admin() ) 
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
    }

    /**
     * Get class instance.
     *
     * @static
     * @return Sofa_Crowdfunding_Helper
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new Sofa_Crowdfunding_Helper();
        }
        return self::$instance;
    }

    /**
     * Run on after_setup_theme hook
     * 
     * @return void
     * @since Projection 1.0
     */
    public function after_setup_theme() {
    	add_theme_support('appthemer-crowdfunding', apply_filters( 'projection_crowdfunding_supports', array(
    		'campaign-widget' => true, 
    		'campaign-featured-image' => true, 
    		'campaign-video' => true, 
    		'anonymous-backers' => true
    	)));
    }

    /**
     * Enqueue scripts 
     *
     * @return void
     * @since Projection 1.0
     */
    public function wp_enqueue_scripts() {
        // Theme directory
        $theme_dir = get_template_directory_uri();   
        
        wp_register_script('raphael', sprintf( "%s/media/js/raphael-min.js", $theme_dir ), array('jquery'), 0.1, true);
        wp_register_script('countdown', sprintf( "%s/media/js/jquery.countdown.min.js", $theme_dir ), array('jquery'), 0.1, true);
        wp_register_script('projection-crowdfunding', sprintf( "%s/media/js/projection-crowdfunding.js", $theme_dir ), array('raphael', 'countdown'), 0.1, true);
        wp_enqueue_script('projection-crowdfunding');

        wp_register_style('projection-crowdfunding', sprintf( "%s/media/css/projection-crowdfunding.css", $theme_dir ));
        wp_enqueue_style('projection-crowdfunding');
    }
}

// Use this function to retrieve the one true instance of the Sofa_Crowdfunding_Helper class.
function get_sofa_crowdfunding() {
    return Sofa_Crowdfunding_Helper::get_instance();
}

// Run it once to get things going
get_sofa_crowdfunding();