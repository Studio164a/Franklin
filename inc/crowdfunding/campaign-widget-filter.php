<?php
$a = new Campaign_Widget();
//$a->go();
class Campaign_Widget {

    function __construct() {
        $this->attach_hooks_and_filters();
    }

    function attach_hooks_and_filters() {
        // If this is the campaign widget, remove various unnecessary js/css
        //edd
        remove_action( 'wp_enqueue_scripts', 'edd_load_scripts' );
        //apthemer-crowdfunding
        $crowdfunding = crowdfunding();
        remove_action( 'wp_enqueue_scripts', array( $crowdfunding, 'frontend_scripts' ) );
        //other
        add_action('wp_enqueue_scripts', array($this,'dequeue_scripts'), 1111111111);
        add_action('wp_enqueue_scripts', array($this,'requeue_barometer_script'), 1111111112);
    }

    function debug(){
        echo "\n\nbreak\n\n";
    }

    function requeue_barometer_script(){
        $theme_dir = get_template_directory_uri();
        wp_register_script('franklin-crowdfunding', sprintf( "%s/media/js/franklin-crowdfunding.js", $theme_dir ), array('raphael','sofa'), get_franklin_theme()->get_theme_version(), true);    
        wp_enqueue_script('franklin-crowdfunding');
    }

    function dequeue_scripts(){

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
        }

        foreach ($styles as $style){
            wp_dequeue_style($style);
        }

    }
}