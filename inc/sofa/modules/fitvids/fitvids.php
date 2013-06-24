<?php

/**
 * Sofa Fitvids module. 
 * 
 * @class Sofa_Fitvids
 * @since 0.1
 * @package Sofa
 * @author Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Sofa_Fitvids') ) {

class Sofa_Fitvids {

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
        add_action('wp_footer', array(&$this, 'wp_footer'), 11);

    	add_filter('video_embed_html', array(&$this, 'video_embed_html_filter'));
        add_filter('oembed_dataparse', array(&$this, 'oembed_dataparse_filter'), 10, 3);

	}

	public function wp_enqueue_scripts() {	
		wp_register_script('fitvids', sprintf( "%s/modules/fitvids/jquery.fitvids.min.js", $this->plugin_dir ), array( 'jquery' ), 0.1, true );
		wp_enqueue_script('fitvids');
	}

    /**
     * Wrap videos inside fit_video class.
     * 
     * @param string $html 
     * @param WP_oEmbed $data
     * @param string $url
     * @return string
     * @since Sofa 0.1
     */
    public function oembed_dataparse_filter($html, $data, $url) {
        if ( $data->type == 'video'  )
            return $this->video_embed_html_filter($html);

        return $html;
    }

    /**
     * Wrap videos inside fit_video class.
     * 
     * @param string $html
     * @return string
     * @since Sofa 0.1
     */
    public function video_embed_html_filter($html) {
        return '<div class="fit-video">'.$html.'</div>';
    }

    /**
     * Set up fitvids script on wp_footer hook. 
     * 
     * @return void
     * @since Sofa 0.1
     */
    public function wp_footer() {
        ?>
        <script>(function($){ $(document).ready(function(){ $('.fit-video, .video-player').fitVids(); }); })(jQuery);</script>
        <?php 
    }
}

new Sofa_Fitvids();

} // Class exists check