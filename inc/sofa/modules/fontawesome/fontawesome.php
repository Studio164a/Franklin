<?php	
/*
* Font Awesome for Wordpress
* Add Font Awesome icons to your Wordpress site with a handy tooltip in the post/page editor.
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

if ( !class_exists( 'Sofa_FontAwesome') ) {

	class Sofa_FontAwesome {

		/**
		 * Fontawesome theme plugin directory
		 * @var string
		 */
		protected $dir;

		/**
		 * Class constructor. Sets up hooks and filters
		 */
		public function __construct() {
			$sofa = get_sofa_framework();

			$this->module_dir = $sofa->get_module_directory_url() . '/fontawesome';

	        if (!is_admin()) {
	            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
	            add_action('wp_head', array(&$this, 'wp_head'));
	        }

	        // Add shortcode
	        add_shortcode( 'fa_icon', array(&$this, 'shortcode') );

	        // Load FontAwesome inside TinyMCE Editor
	        add_filter( 'mce_css', array(&$this, 'mce_css') );

	        // Filters to add FontAwesome plugin to TinyMCE Editor
	        add_filter( 'mce_external_plugins', array(&$this, 'mce_external_plugins') );
	        add_filter( 'mce_buttons_2', array(&$this, 'mce_buttons_2') );
		}

		/**
		 * Load Font Awesome CSS on frontend
		 */
		public function wp_enqueue_scripts() {
			wp_register_style( 'font-awesome', $this->module_dir . '/media/font-awesome.min.css' );
	        wp_enqueue_style( 'font-awesome' );
		}

		/**
		 * Load up IE7 compatibility script
		 */
		public function wp_head() {
			?>
			<!--[if IE 7]>
	            <link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->module_dir . '/media/font-awesome-ie7.min.css' ?>" />
	        <![endif]-->
	        <?php 
		}

		/**
		 * Load FontAwesome CSS inside TinyMCE editor so icons are seen inside the editor		 
		 * @param string $css
		 * @return string
		 */
		public function mce_css( $css ) {
			if ( !empty( $css ) ) 
				$css .= ",";

			$css .= $this->module_dir . '/media/font-awesome.min.css';
			return $css;
		}

		/**
		 * Add FontAwesome button to the second row of buttons
		 * @param array $buttons
		 * @return array
		 */
		public function mce_buttons_2( $buttons ) {
			$buttons[] = 'fontawesome';
			return $buttons;
		}

		/**
		 * Add FontAwesome plugin for TinyMCE
		 * @param array $plugin_array
		 * @return array
		 */
		public function mce_external_plugins( $plugin_array ) {
			$plugin_array['fontawesome'] = $this->module_dir . '/media/tinymce/editor-styles.js';
			return $plugin_array;
		}

		/**
		 * Shortcode
		 * @param array $args
		 * @return string
		 */
		public function shortcode( $args = array()) {
			extract( shortcode_atts( array(
				'icon' => '', 'size' => '', 'colour' => '', 'color' => ''
			), $args ) );

			// Ensure that an icon was set
			if ( strlen($icon) === 0 )
				return $content;

			$html = '<i ';
			
			// Add colour
			$colour = $colour != '' ? $colour : $color; // Allow for alternative spelling of colour
			if ( $colour != '' )
				$html .= sprintf( 'style="color:%s;" ', $colour );

			// Add icon class
			$html .= sprintf( 'class="icon-%s', $icon);

			// Add large class to icon if applicable
			if ( $size == 'large' )
				$html .= ' icon-large';

			// Close icon
			$html .= '"></i>';

			// Wrap icon inside div with font-size set, if required
			if ( !in_array( $size, array( '', 'large' ) ) )
				$html = sprintf( '<div style="font-size:%s">%s</div>', $size, $html ); 

			return $html;
		}
	}
}

// Instantiate the class/plugin
new Sofa_FontAwesome();