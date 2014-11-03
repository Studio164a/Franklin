<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * A widget to display your site's stats.
 * 
 * @see WP_Widget
 * @author Studio164a
 */
class Sofa_Crowdfunding_Stats_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'crowdfunding_stats_widget', // Base ID
			__( 'Crowdfunding Stats', 'franklin'), // Name
			array( 'description' => __( 'Display a the website\'s crowdfunding stats.', 'franklin' ) ) // Args
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );

		// Title, with default 
		$title = apply_filters('widget_title', isset( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base);

		echo $before_widget;

		// Widget tilte
		if ( $title ) echo $before_title . $title . $after_title; 

		echo franklin_crowdfunding_stats();

		echo $after_widget;
	}

	public function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		
        ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'franklin' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
	}
}