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

		echo $before_widget;

		echo franklin_crowdfunding_stats();

		echo $after_widget;
	}

	public function form( $instance ) {
        ?>
        <p><?php _e( 'This widget has no configuration settings.', 'franklin' ) ?></p>
        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        return $instance;
	}
}