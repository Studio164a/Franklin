<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * A widget to display your campaign's backers.
 * 
 * @see WP_Widget
 * @author Studio164a
 */
class Sofa_Crowdfunding_Backers_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'campaign_backers_widget', // Base ID
			__( 'Campaign Backers', 'projection'), // Name
			array( 'description' => __( 'Display a campaign\'s backers.', 'projection' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );		

		// We have to have a campaign id
		if ( !isset( $instance['campaign_id'] ) || $instance['campaign_id'] == '' )
			return;

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( !empty($title) )
			echo $before_title . $title . $after_title;

		echo projection_campaign_backers( new ATCF_Campaign( $instance['campaign_id'] ), $instance );

		echo $after_widget;
	}

	public function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '', 'campaign_id' => '' ));

		$campaigns = new ATCF_Campaign_Query();

        $title = $instance['title'];
        $campaign_id = $instance['campaign_id'];
        $show_location = isset( $instance['show_location'] ) ? $instance['show_location'] : false;
        $show_pledge = isset( $instance['show_pledge'] ) ? $instance['show_pledge'] : false;
        $show_name = isset( $instance['show_name'] ) ? $instance['show_name'] : false;
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'projection') ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('campaign_id'); ?>"><?php _e('Campaign:', 'projection') ?>        
            	<select name="<?php echo $this->get_field_name('campaign_id') ?>">
            		<option value=""><?php _e( 'Select', 'projection' ) ?></option>
            		<?php foreach ( $campaigns->posts as $campaign ) : ?>
            			<option value="<?php echo $campaign->ID ?>" <?php selected( $campaign->ID, $campaign_id ) ?>><?php echo $campaign->post_title ?></option>
            		<?php endforeach ?>
            	</select>    
            </label>      
        </p>

        <p>
			<label for="<?php echo $this->get_field_id('show_name') ?>"><?php _e( 'Show backer\'s name:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_name') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_name') ); ?>" <?php checked( $show_name ) ?>>
		</p>

        <p>
			<label for="<?php echo $this->get_field_id('show_pledge') ?>"><?php _e( 'Show backer\'s pledge amount:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_pledge') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_pledge') ); ?>" <?php checked( $show_pledge ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_location') ?>"><?php _e( 'Show backer\'s location:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_location') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_location') ); ?>" <?php checked( $show_location ) ?>>
		</p>

        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['campaign_id'] = $new_instance['campaign_id'];    
        $instance['show_location'] = isset( $new_instance['show_location'] ) ? true : false;
        $instance['show_pledge'] = isset( $new_instance['show_pledge'] ) ? true : false;
        $instance['show_name'] = isset( $new_instance['show_name'] ) ? true : false;
        return $instance;
	}
}