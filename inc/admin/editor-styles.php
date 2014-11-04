<?php
/**
 * Custom editor styles for Hitched. 
 * Based on the plugin provided on WPTuts: http://wp.tutsplus.com/tutorials/theme-development/adding-custom-styles-in-wordpress-tinymce-editor/
 * That plugin was in turn based on TinyMCE Kit plug-in for WordPress: http://plugins.svn.wordpress.org/tinymce-advanced/branches/tinymce-kit/tinymce-kit.php
 */

class Sofa_Editor_Styles {

    /**
     * Stores instance of class.
     * @var Sofa_Editor_Styles
     */
    private static $instance = null;

    /**
     * Private constructor. Singleton pattern.      
     */
    private function __construct() {
    	add_editor_style( 'media/css/editor-styles.css?0.1' );
    	
    	add_filter( 'mce_buttons_2', array(&$this, 'mce_buttons_2') );
    	add_filter( 'tiny_mce_before_init', array(&$this, 'tiny_mce_before_init') );     	
    }

    /**
     * Get class instance
     * @static
     * @return Sofa_Editor_Styles
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
          self::$instance = new Sofa_Editor_Styles();
        }
        return self::$instance;
    } 

	/**
	 * Add buttons on the second row
	 * @param array $buttons
	 * @return array
	 */
	function mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}    

	/**
	 * Add styles/classes to the "Styles" drop-down
	 * @param array $settings
	 * @return array
	 */	
	function tiny_mce_before_init( $settings ) {
	    $style_formats = array(
	    	array(
	    		'title' => 'Header font variation',
	    		'selector' => 'h1,h2,h3,h4,h5,h6',
	    		'classes' => 'alt'
	    	),
	        array(
	        	'title' => 'Intro paragraph',
	        	'selector' => 'p', 
	        	'classes' => 'lead'
	        )
	    );
	    $settings['style_formats'] = json_encode( $style_formats );	    
	    return $settings;
	}
}