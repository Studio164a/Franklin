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
     * @var ATCF_Campaign|false
     */
    private $active_campaign;

    /**
     * Private constructor. Singleton pattern.
     */
    private function __construct() {
    	add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
        add_action('wp_footer', array(&$this, 'wp_footer'));

        if ( !is_admin() ) 
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);

        add_filter('edd_purchase_link_defaults', array(&$this, 'edd_purchase_link_defaults_filter'));
        add_filter('edd_templates_dir', array(&$this, 'edd_templates_dir_filter'));
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

    /**
     * Add pledge modal on wp_footer hook
     * 
     * @return void
     * @since Projection 1.0
     */
    public function wp_footer() {
        ?>             
        <div id="campaign-form" class="reveal-modal content-block block">
            <a class="close-reveal-modal"><i class="icon-remove"></i></a>
            <?php echo edd_get_purchase_link( array( 'download_id' => $this->get_active_campaign()->ID ) ); ?>
        </div>
        <?php
    }

    /**
     * Filter the default arguments for the purchase link.
     * 
     * @param array $args
     * @return array
     * @since Projection 1.0
     */
    public function edd_purchase_link_defaults_filter($args) {
        global $edd_options;
        $args['color'] = 'accent';
        return $args;
    }

    /**
     * Filter the default template directory. 
     * 
     * @param string $default
     * @return string
     * @since Projection 1.0
     */
    function edd_templates_dir_filter($default) {   
        return 'templates/edd';
    }

    /** 
     * Get the active campaign. 
     * 
     * @return ATCF_Campaign|false
     * @since Projection 1.0 
     */
    public function get_active_campaign() {
        // If we haven't already set the active campaign, set it now
        if (!isset( $this->active_campaign ) ) {
            $campaign_id = get_theme_mod('campaign', false);
            $this->active_campaign = false === $campaign_id ? false : new ATCF_Campaign($campaign_id);
        }

        return $this->active_campaign;
    }
}

// Use this function to retrieve the one true instance of the Sofa_Crowdfunding_Helper class.
function get_sofa_crowdfunding() {
    return Sofa_Crowdfunding_Helper::get_instance();
}

// Run it once to get things going
get_sofa_crowdfunding();