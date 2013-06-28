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
        
        include_once('helpers.php');
        include_once('template.php');
        include_once('widgets/campaign-pledge-levels.php');
        include_once('widgets/campaign-updates.php');
        include_once('widgets/campaign-backers.php');
        include_once('widgets/campaign-video.php');

    	add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
        add_action('wp_footer', array(&$this, 'wp_footer'));
        add_action('widgets_init', array(&$this, 'widgets_init'));
        // remove_action('atcf_shortcode_profile', 'atcf_shortcode_profile_campaigns', 20, 1 );

        if ( !is_admin() ) 
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);

        add_filter('edd_purchase_link_defaults', array(&$this, 'edd_purchase_link_defaults_filter'));
        add_filter('edd_templates_dir', array(&$this, 'edd_templates_dir_filter'));
        add_filter('edd_add_to_cart_item', array(&$this, 'edd_add_to_cart_item_filter'));
        add_filter('edd_cart_item_price', array(&$this, 'edd_cart_item_price_filter'), 10, 3);
        add_filter('edd_checkout_image_size', array(&$this, 'edd_checkout_image_size_filter'));
        add_filter('edd_checkout_button_purchase', array(&$this, 'edd_checkout_button_purchase_filter'));
        add_filter('franklin_pledge_levels_wrapper_atts', array(&$this, 'franklin_pledge_levels_wrapper_atts_filter'));
        add_filter('atcf_submit_field_description_editor_args', array(&$this, 'atcf_submit_field_updates_editor_args_filter'));
        add_filter('atcf_submit_field_updates_editor_args', array(&$this, 'atcf_submit_field_updates_editor_args_filter'));

        add_shortcode('campaign_pledge_levels', array(&$this, 'campaign_pledge_levels_shortcode'));
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
     * @since Franklin 1.0
     */
    public function after_setup_theme() {
    	add_theme_support('appthemer-crowdfunding', apply_filters( 'franklin_crowdfunding_supports', array(
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
     * @since Franklin 1.0
     */
    public function wp_enqueue_scripts() {
        // Theme directory
        $theme_dir = get_template_directory_uri();   
        
        wp_register_script('raphael', sprintf( "%s/media/js/raphael-min.js", $theme_dir ), array('jquery'), 0.1, true);
        wp_register_script('countdown', sprintf( "%s/media/js/jquery.countdown.min.js", $theme_dir ), array('jquery'), 0.1, true);
        wp_register_script('franklin-crowdfunding', sprintf( "%s/media/js/franklin-crowdfunding.js", $theme_dir ), array('raphael', 'countdown'), 0.1, true);
        wp_enqueue_script('franklin-crowdfunding');

        wp_localize_script('franklin-crowdfunding', 'SofaCrowdfunding', array(
            'button_colour' => get_theme_mod('body_text', '#7D6E63')));

        wp_register_style('franklin-crowdfunding', sprintf( "%s/media/css/franklin-crowdfunding.css", $theme_dir ));
        wp_enqueue_style('franklin-crowdfunding');
    }

    /**
     * Add pledge modal on wp_footer hook
     * 
     * @return void
     * @since Franklin 1.0
     */
    public function wp_footer() {
        ?>             
        <div id="campaign-form" class="reveal-modal content-block block">
            <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
            <?php echo edd_get_purchase_link( array( 'download_id' => $this->get_active_campaign()->ID ) ); ?>
        </div>

        <div id="login-form" class="reveal-modal block multi-block">            
            <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
            <div class="content-block login-block">
                <div class="title-wrapper"><h3 class="block-title accent"><?php _e( 'Login', 'franklin') ?></h3></div> 
                <?php echo atcf_shortcode_login() ?>
            </div>
            <div class="register-block  block last">
                <div class="title-wrapper"><h3 class="block-title accent"><?php _e( 'Register', 'franklin') ?></h3></div> 
                <?php echo atcf_shortcode_register() ?>
            </div>
        </div>
        <?php
    }

    /**
     * Register widgets. 
     * 
     * @return void
     * @since Franklin 1.0
     */
    public function widgets_init() {
        register_widget( 'Sofa_Crowdfunding_Pledge_Levels_Widget' );
        register_widget( 'Sofa_Crowdfunding_Backers_Widget' );
        register_widget( 'Sofa_Crowdfunding_Updates_Widget' );
        register_widget( 'Sofa_Crowdfunding_Video_Widget' );
    }

    /**
     * Filter the default arguments for the purchase link.
     * 
     * @param array $args
     * @return array
     * @since Franklin 1.0
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
     * @since Franklin 1.0
     */
    public function edd_templates_dir_filter($default) {   
        return 'templates/edd';
    }

    /**
     * Filter the item array that is stored in the user session.
     *
     * @param array $item
     * @return array
     * @since Franklin 1.0
     */
    public function edd_add_to_cart_item_filter($item) {
        // If post_data is not set, return. Not sure this would ever happen, but saves any notices occuring.
        if ( !isset($_POST['post_data']))
            return $item;

        // Parse the post_data array 
        parse_str( urldecode( $_POST['post_data'] ), $query_args );

        if ( isset( $query_args['franklin_custom_price'] ) ) {
            $item['options']['custom_price'] = $query_args['franklin_custom_price'];
        }
        
        return $item;
    }

    /**
     * Filter the item's price based on the custom price set in the options array
     * 
     * @param 
     * @param
     * @param
     * @return 
     * @since Franklin 1.0
     */
    public function edd_cart_item_price_filter($price, $item_id, $options) {
        if ( isset( $options['custom_price']) && $options['custom_price'] != $price )
            return $options['custom_price'];

        return $price;        
    }

    /**
     * Filter the cart item picture's size. 
     * 
     * @param array $default
     * @return array
     * @since Franklin 1.0
     */
    public function edd_checkout_image_size_filter($default) {
        return array(80, 80);
    }

    /**
     * Filter the checkout purchase button. 
     * 
     * @param string $button
     * @return string
     * @since Franklin 1.0
     */
    public function edd_checkout_button_purchase_filter($button) {
        return str_replace( 'edd-submit', 'edd-submit accent button-large', $button);
    }

    /** 
     * Filter the wrapper attributes for the pledge levels
     * 
     * @param string $atts
     * @return string
     * @since Franklin 1.0
     */
    public function franklin_pledge_levels_wrapper_atts_filter($atts) {
        return 'class="campaign-pledge-levels accordion"';
    }

    /** 
     * Filter the editor arguments on the campaign submission page. 
     * 
     * @param string $args
     * @return array
     * @since Franklin 1.1
     */
    public function atcf_submit_field_updates_editor_args_filter($args) {
        $args['editor_css'] = '<style></style>';
        $args['media_buttons'] = false;
        return $args;
    }

    /** 
     * Shortcode wrapper for franklin_pledge_levels template function.
     *
     * @see franklin_pledge_levels
     * @param array $atts
     * @return string|void
     * @since Franklin 1.0
     */
    public function campaign_pledge_levels_shortcode( $atts ) {
        global $post;

        if ( isset( $atts['campaign_id']) ) {
            $campaign_id = $atts['campaign_id'];
        }
        elseif ( $post->post_type == 'download' ) {     
            $campaign_id = $post->ID;
        }
        else {
            $active_campaign = get_sofa_crowdfunding()->get_active_campaign();

            if ( $active_campaign == false )
                return;

            $campaign_id = $active_campaign->ID;
        }

        return franklin_pledge_levels($campaign_id);
    }

    /** 
     * Get the active campaign. 
     * 
     * @return ATCF_Campaign|false
     * @since Franklin 1.0 
     */
    public function get_active_campaign() {
        // If we haven't already set the active campaign, set it now
        if (!isset( $this->active_campaign ) ) {
            $campaign_id = get_theme_mod('campaign', false);

            // Active campaign isn't set, so we'll check if the static front page has 
            // been set to a campaign. If it has, set the activate campaign token.
            if ( $campaign_id === false ) {
                if ( 'page' == get_option( 'show_on_front') && get_option( 'page_on_front' ) && get_post_type( get_option( 'page_on_front' ) ) == 'download' ) {
                    $campaign_id = get_option( 'page_on_front' );
                    set_theme_mod('campaign', $campaign_id);
                }
            }

            $this->active_campaign = false == $campaign_id ? false : new ATCF_Campaign($campaign_id);
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