<?php 
/**
 * This is a helper class. 
 * 
 * Uses the Singleton pattern. 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Sofa_WPML_Helper {

	/**
     * @var Sofa_WPML_Helper
     */
    private static $instance = null;

    /**
     * Private constructor. Singleton pattern.
     */
    private function __construct() {
    	include_once('helpers.php');

    	if ( !is_admin() ) {
            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 11);
    	}

        add_filter('the_content', array(&$this, 'the_content_filter'), 4000);
    }

    /**
     * Get class instance.
     *
     * @static
     * @return Sofa_WPML_Helper
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new Sofa_WPML_Helper();
        }
        return self::$instance;
    }

    /**
     * Enqueue scripts 
     *
     * @return void
     * @since Franklin 1.3
     */
    public function wp_enqueue_scripts() {
        wp_register_style('franklin-wpml', sprintf( "%s/media/css/franklin-wpml.css", get_template_directory_uri() ));
        wp_enqueue_style('franklin-wpml');
    }

    /**
     * Adds a list of content languages if we're on a single post. 
     * 
     * @param string $content
     * @return string
     * @since Franklin 1.3
     */
    public function the_content_filter($content) {
    	if ( is_singular() && !is_front_page() ) {
    		$content = $content . wpml_content_languages();
    	}
    	return $content;
    }
}

// Use this function to retrieve the one true instance of the Sofa_WPML_Helper class.
function get_sofa_wpml() {
    return Sofa_WPML_Helper::get_instance();
}

// Run it once to get things going
get_sofa_wpml();