<?php

/**
 * Sofa PrettyPhoto module. 
 * 
 * @class Sofa_PrettyPhoto
 * @since 0.1
 * @package Sofa
 * @author Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Sofa_PrettyPhoto') ) {

class Sofa_PrettyPhoto {

	/** 
	 * @var Sofa_Framework 
	 */
	private $sofa;

	/**
	 * @var string
	 */
	private $plugin_dir;	

	/**
	 * Class constructor. Requires Sofa_Framework instance to be passed. 
	 * 
	 * @param Sofa_Framework $sofa
	 * @return void
	 * @since Sofa 0.1
	 */
	public function __construct() {		
		$this->sofa = get_sofa_framework();

		$this->plugin_dir = $this->sofa->get_directory_url();

		add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));        
	}

	public function wp_enqueue_scripts() {	
		wp_register_script('prettyPhoto', sprintf( "%s/modules/prettyPhoto/media/jquery.prettyPhoto.js", $this->plugin_dir ), array( 'jquery' ), 0.1 );
		wp_enqueue_script('prettyPhoto');

        wp_register_style('prettyPhoto', sprintf( "%s/modules/prettyPhoto/media/prettyPhoto.css", $this->plugin_dir ), array(), 0.1 );
        wp_enqueue_style('prettyPhoto');
	}
}

new Sofa_PrettyPhoto();

} // Class exists check