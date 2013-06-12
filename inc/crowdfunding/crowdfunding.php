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
}

Sofa_Crowdfunding_Helper::get_instance();