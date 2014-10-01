<?php
/**
 * Filters out unnecessary scripts and stylesheets on the campaign widget. 
 *
 * @since 1.5.11
 */
class Campaign_Widget_Filter {

    /**
     * Sets up callbacks for action hooks on the campaign widget.
     *
     * @since Franklin 1.5.11
     */
    public function __construct() {
        remove_action( 'wp_enqueue_scripts', 'edd_load_scripts' );
        remove_action( 'wp_enqueue_scripts', array( crowdfunding(), 'frontend_scripts' ) );
        
        add_action('wp_enqueue_scripts', array($this,'dequeue_scripts'), 100);
        add_action('wp_enqueue_scripts', array($this,'requeue_barometer_script'), 101);
        add_action('wp_head', array($this, 'wp_head'));
    }

    /**
     * Dequeues unneccessary scripts.
     *
     * @since Franklin 1.5.11
     */
    public function dequeue_scripts(){
        $scripts = array(
            'franklin',
            'franklin-crowdfunding',
            'fitvids'
        );

        $styles = array(
            'edd-styles',
            'font-awesome',
            'prettyPhoto',
            'foundation',
            'franklin-ninja-forms'
        );

        foreach ($scripts as $script){
            wp_deregister_script($script);
            wp_dequeue_script($script);
        }

        foreach ($styles as $style){
            wp_deregister_style($style);
            wp_dequeue_style($style);
        }
    }

    /**
     * Queues the barometer script up after dequeueing it previously.
     *
     * @since Franklin 1.5.11
     */
    public function requeue_barometer_script(){
        $theme_dir = get_template_directory_uri();
        wp_register_script('franklin-crowdfunding', sprintf( "%s/media/js/franklin-crowdfunding.js", $theme_dir ), array('raphael','sofa'), get_franklin_theme()->get_theme_version(), true);    
        wp_enqueue_script('franklin-crowdfunding');
    }

    /**
     * Called on wp_head on the widget template. 
     * 
     * @since Franklin 1.3
     */
    public function wp_head() {
        ?>
        <style>
        body { background: transparent !important; position: absolute; top: 0; left: 0;} 
        #wpadminbar { display: none; }
        </style>
        <?php
    }
}

new Campaign_Widget_Filter();