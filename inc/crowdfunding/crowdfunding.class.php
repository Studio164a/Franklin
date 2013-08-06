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
     * @var bool
     */
    private $viewing_widget = false;

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
        include_once('widgets/stats.php');

    	add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
        add_action('wp_footer', array(&$this, 'wp_footer'));
        add_action('widgets_init', array(&$this, 'widgets_init'));
        add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        add_action('save_post', array(&$this, 'save_post'), 10, 2);

        if ( !is_admin() ) 
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);

        add_action('atcf_found_widget', array(&$this, 'atcf_found_widget'));

        add_filter('page_template', array(&$this, 'page_template_filter'));
        add_filter('edd_purchase_link_defaults', array(&$this, 'edd_purchase_link_defaults_filter'));
        add_filter('edd_templates_dir', array(&$this, 'edd_templates_dir_filter'));
        add_filter('edd_add_to_cart_item', array(&$this, 'edd_add_to_cart_item_filter'));
        add_filter('edd_cart_item_price', array(&$this, 'edd_cart_item_price_filter'), 10, 3);
        add_filter('edd_checkout_image_size', array(&$this, 'edd_checkout_image_size_filter'));
        add_filter('edd_checkout_button_purchase', array(&$this, 'edd_checkout_button_purchase_filter'));
        add_filter('franklin_pledge_levels_wrapper_atts', array(&$this, 'franklin_pledge_levels_wrapper_atts_filter'));
        add_filter('atcf_submit_field_description_editor_args', array(&$this, 'atcf_submit_field_updates_editor_args_filter'));
        add_filter('atcf_submit_field_updates_editor_args', array(&$this, 'atcf_submit_field_updates_editor_args_filter'));
        add_filter('the_content', array(&$this, 'the_content_filter'));

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
    		'campaign-widget'         => true, 
    		'campaign-featured-image' => true, 
    		'campaign-video'          => true, 
    		'anonymous-backers'       => true, 
            'campaign-edit'           => true
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

        // Array of required scripts
        $req = array('raphael', 'franklin');

        wp_register_script('raphael', sprintf( "%s/media/js/raphael-min.js", $theme_dir ), array('jquery'), 0.1, true);

        // Add scripts that are only applied if we're not looking at a widget
        if ( $this->viewing_widget === false ) {    

            $req[] = 'jquery-masonry';

            if ( get_post_type() == 'download' ) {
                wp_register_script('countdown', sprintf( "%s/media/js/jquery.countdown.min.js", $theme_dir ), array('jquery'), 0.1, true);
                $req[] = 'countdown';
            }
        }

        wp_register_script('franklin-crowdfunding', sprintf( "%s/media/js/franklin-crowdfunding.js", $theme_dir ), $req, 0.1, true);    

        // Load the Franklin crowdfunding script
        wp_enqueue_script('franklin-crowdfunding');
    
        wp_register_style('franklin-crowdfunding', sprintf( "%s/media/css/franklin-crowdfunding.css", $theme_dir ));
        wp_enqueue_style('franklin-crowdfunding');

        // We're viewing the widget, so there are a bunch of scripts that we don't need to load
        if ( $this->viewing_widget ) {
            wp_dequeue_script('franklin');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('fitVids');
            wp_dequeue_script('flexnav');
        }
    }

    /**
     * Add pledge modal on wp_footer hook
     * 
     * @return void
     * @since Franklin 1.0
     */
    public function wp_footer() {
        if ( $this->viewing_widget === false ) : 
        ?>                     
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
        endif;
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
        register_widget( 'Sofa_Crowdfunding_Stats_Widget' );
    }

    /**
     * Executes on the add_meta_boxes hook. 
     * 
     * @return void
     * @since Franklin 1.1
     */
    public function add_meta_boxes() {
        global $post;

        if ( get_page_template_slug( $post->ID ) == 'page-campaigns.php' ) {
            add_meta_box('franklin_campaign_homepage_options', __( 'Page Options', 'franklin' ), array( &$this, 'homepage_options' ), 'page' );
        }

        if ( get_page_template_slug( $post->ID ) == 'page-single-campaign.php' ) {
            add_meta_box('franklin_campaign_single_options', __( 'Campaign', 'franklin' ), array( &$this, 'single_campaign_options' ), 'page' );
        }        
    }

    /**
     * Homepage options.
     *
     * @return void
     * @since Franklin 1.1
     */
    public function homepage_options($post) {        

        // Use nonce for verification
        wp_nonce_field( 'franklin_campaign', '_franklin_campaign_nonce' );

        $campaigns = get_sofa_crowdfunding()->get_featured_campaigns();
        $featured_mode = get_post_meta( $post->ID, '_franklin_featured_campaigns_option', true );            
        ?>

        <h4><?php _e( 'Featured Campaigns', 'franklin' ) ?></h4>
        <p>
            <label for="_franklin_featured_campaigns_option" id="franklin_featured_campaigns_option">
                <?php _e( 'Campaign(s) to show:', 'franklin' ) ?>
                <select name="_franklin_featured_campaigns_option">
                    <option value="ending_soonest" <?php selected( $featured_mode, 'ending_soonest' ) ?>><?php _e( 'Ending soonest', 'franklin' ) ?></option>
                    <option value="most_recent" <?php selected( $featured_mode, 'most_recent' ) ?>><?php _e( 'Most recent', 'franklin' ) ?></option>
                    <option value="random" <?php selected( $featured_mode, 'random' ) ?>><?php _e( 'Random', 'franklin' ) ?></option>
                    <?php if ( $campaigns->have_posts() ) : ?>
                        <optgroup label="<?php _e( 'Specific campaign', 'franklin' ) ?>">
                        <?php while ( $campaigns->have_posts() ) : ?>
                            <?php $campaigns->the_post() ?>
                            <option value="<?php the_ID() ?>" <?php selected(get_the_ID(), $featured_mode) ?>><?php the_title() ?></option>
                        <?php endwhile ?>                                
                        </optgroup>
                    <?php endif ?>
                </select>
            </label>
        </p>

        <?php 
        wp_reset_postdata();

        $featured_campaigns_pages = apply_filters( 'sofa_crowdfunding_featured_campaign_pages', new WP_Query( 
            array( 
                'post_type' => 'page', 
                'meta_query' => array( 
                    array( 'key' => '_wp_page_template', 'value' => 'page-featured-campaigns.php' )
                ) 
            ) 
        ) );

        $featured_page = get_post_meta( $post->ID, '_franklin_featured_campaigns_page', true );
        $featured_page_text = get_post_meta( $post->ID, '_franklin_featured_campaigns_page_text', true );

        if ( $featured_campaigns_pages->have_posts() ) : ?>
            <p>
                <label for="_franklin_featured_campaigns_page">
                    <?php _e( 'Link to featured campaigns page', 'franklin' ) ?>
                    <select name="_franklin_featured_campaigns_page">
                    <option value=""><?php _e('- Select -', 'franklin') ?></option>
                    <?php while ( $featured_campaigns_pages->have_posts() ) : ?>
                        <?php $featured_campaigns_pages->the_post() ?>
                        <option value="<?php the_ID() ?>" <?php selected(get_the_ID(), $featured_page) ?>><?php the_title() ?></option>
                    <?php endwhile ?>

                    </select>
                </label>
            </p>
            <p>
                <label for="_franklin_featured_campaigns_page_text">
                    <?php _e( 'Featured campaigns page link text', 'franklin' ) ?>
                    <input type="text" name="_franklin_featured_campaigns_page_text" value="<?php echo $featured_page_text ?>" />
                </label>
            </p>

        <?php endif;

        // Restore order to the universe
        wp_reset_postdata();
    }

    /**
     * Hide post meta meta box
     *
     * @return void
     * @since Franklin 1.1
     */
    public function single_campaign_options($post) {

        // Use nonce for verification
        wp_nonce_field( 'franklin_campaign', '_franklin_campaign_nonce' );

        $campaigns = new ATCF_Campaign_Query();            
        $selected = get_post_meta( $post->ID, '_franklin_single_campaign_id', true );

        if ( $campaigns->have_posts() ) : 
        ?>

        <p>
            <label for="_franklin_single_campaign_id">
                <?php _e( 'Select a campaign:', 'franklin' ) ?>
                <select name="_franklin_single_campaign_id">
                    <?php while ( $campaigns->have_posts() ) : ?>
                        <?php $campaigns->the_post() ?>
                        <option value="<?php the_ID() ?>" <?php selected(get_the_ID(), $selected) ?>><?php the_title() ?></option>
                    <?php endwhile ?>
                </select>
            </label>
        </p>

        <?php 
        endif;

        wp_reset_postdata();

    }


    /**
     * Executes on the save_post hook. Used to save the custom meta. 
     * 
     * @return void
     * @since Franklin 1.1
     */
    public function save_post($post_id, $post) {

        // Verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;        

        if ( isset( $_POST['post_type'] ) && in_array( $_POST['post_type'], array('page') )  ) {
            // Verify this came from the our screen and with proper authorization,
            // because save_post can be triggered at other times
            if ( !array_key_exists('_franklin_campaign_nonce', $_POST ) || !wp_verify_nonce( $_POST['_franklin_campaign_nonce'], 'franklin_campaign' ) )
                return;

             // Ensure current user can edit pages
            if ( !current_user_can( 'edit_page', $post_id ) && !current_user_can( 'edit_post', $post_id ) )
                return;

            // Save custom fields found in our $settings variable
            if ( get_page_template_slug( $post->ID ) == 'page-campaigns.php' ) {
                update_post_meta( $post_id, '_franklin_featured_campaigns_option', $_POST['_franklin_featured_campaigns_option'] );
                update_post_meta( $post_id, '_franklin_featured_campaigns_page', $_POST['_franklin_featured_campaigns_page'] );
                update_post_meta( $post_id, '_franklin_featured_campaigns_page_text', $_POST['_franklin_featured_campaigns_page_text'] );
            }            

            if ( get_page_template_slug( $post->ID ) == 'page-single-campaign.php' ) {
                update_post_meta( $post_id, '_franklin_single_campaign_id', $_POST['_franklin_single_campaign_id'] );
            }
        }
    }

    /**
     * Hooked when we're on the campaign widget template.
     * 
     * @since Franklin 1.3
     */
    public function atcf_found_widget() {
        $this->viewing_widget = true;

        add_action('wp_head', array(&$this, 'wp_head_widget'));
    }

    /**
     * Called on wp_head on the widget template. 
     * 
     * @since Franklin 1.3
     */
    public function wp_head_widget() {
        ?>
        <style>
        body { background: transparent !important; position: absolute; top: 0; left: 0;} 
        #wpadminbar { display: none; }
        </style>
        <?php
    }

    /**
     * Filter the template file loaded for profile and campain submission pages. 
     * 
     * @uses sofa_application_page_template     Child themes can use this to use a different page template, or restore default behaviour.
     *
     * @global $post
     * @global $edd_options
     * @param string $page_template
     * @return string          
     * @since Franklin 1.1
     */
    public function page_template_filter($page_template) {
        global $post, $edd_options;

        // If we're using WPML, we need to apply the same templates to the other language versions
        $language_copy = get_post_meta($post->ID, '_icl_lang_duplicate_of', true);

        if ( $post->ID == $edd_options['submit_page'] 
            || $post->ID == $edd_options['profile_page']
            || $post->ID == $edd_options['purchase_page'] 
            || $language_copy == $edd_options['submit_page']
            || $language_copy == $edd_options['profile_page']
            || $language_copy == $edd_options['purchase_page'] 
        ) {
            $page_template = apply_filters( 'sofa_application_page_template', get_template_directory() . '/page-app.php', $page_template );
        }        
        
        return $page_template;
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
     * Show the campaign submit page feedback. 
     * 
     * @see the_content
     * @return bool
     * @since Franklin 1.1
     */
    public function the_content_filter($content) {
        global $post, $edd_options;

        if ( $post->ID == $edd_options['submit_page'] ) {
            ob_start();

            do_action('atcf_campaign_before', new ATCF_Campaign( $post->ID ) );

            $content = ob_get_clean() . $content;
        }

        return $content;
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

    /**
     * Get featured campaigns. 
     * 
     * @return WP_query
     * @since Franklin 1.1
     */
    public function get_featured_campaigns() {
        return new ATCF_Campaign_Query( array(
            'meta_query' => array(
                array( 'key' => '_campaign_featured', 'value' => 1 ) 
                ) 
            ) 
        );
    }

    /**
     * Get the featured campaign to show in the featured campaigns block.
     * 
     * @param int $post_id
     * @return WP_Query
     * @since Franklin 1.1
     */
    public function get_featured_campaign( $post_id ) {
        $mode = get_post_meta( $post_id, '_franklin_featured_campaigns_option', true );

        // Set ending_soonest as the default mode
        if ( empty( $mode ) )
            $mode = 'ending_soonest';        

        switch ( $mode ) {
            case 'ending_soonest' : 
                return new ATCF_Campaign_Query( array( 
                    'meta_key' => 'campaign_end_date',
                    'meta_query' => array(
                        array( 'key' => '_campaign_featured', 'value' => 1 ) 
                    ), 
                    'posts_per_page' => 1, 
                    'orderby' => 'meta_value', 
                    'order' => 'ASC'
                ) );
                break;

            case 'most_recent' : 
                return new ATCF_Campaign_Query( array( 
                    'meta_query' => array(
                        array( 'key' => '_campaign_featured', 'value' => 1 ) 
                    ), 
                    'posts_per_page' => 1, 
                    'orderby' => 'date', 
                    'order' => 'DESC'
                ) );
                break;

            case 'random' : 
                return new ATCF_Campaign_Query( array(
                    'meta_query' => array(
                        array( 'key' => '_campaign_featured', 'value' => 1 ) 
                    ), 
                    'posts_per_page' => 1, 
                    'orderby' => 'rand'
                ) );
                break;

            default : 
                return new ATCF_Campaign_Query( array( 'p' => $mode ) );
        }
    }
}

// Use this function to retrieve the one true instance of the Sofa_Crowdfunding_Helper class.
function get_sofa_crowdfunding() {
    return Sofa_Crowdfunding_Helper::get_instance();
}

// Run it once to get things going
get_sofa_crowdfunding();