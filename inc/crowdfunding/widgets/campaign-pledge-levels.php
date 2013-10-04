<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A widget to display your campaign's pledge levels.
 * 
 * @see WP_Widget
 * @author Studio164a
 */
class Sofa_Crowdfunding_Pledge_Levels_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'pledge_levels_widget', // Base ID
			__( 'Campaign Pledge Levels', 'franklin'), // Name
			array( 'description' => __( 'Display a campaign\'s pledge levels.', 'franklin' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		
		extract( $args );

		// We have to have a campaign id
		if ( !isset( $instance['campaign_id'] ) || $instance['campaign_id'] == '' )
			return;

		$title = apply_filters( 'widget_title', $instance['title'] );

		$campaign_id = $instance['campaign_id'] == 'current' ? get_the_ID() : $instance['campaign_id'];

		echo $before_widget;

		if ( !empty($title) )
			echo $before_title . $title . $after_title;

		echo franklin_pledge_levels( $campaign_id );

		echo $after_widget;
	}

	public function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '', 'campaign_id' => '' ));

		$campaigns = new ATCF_Campaign_Query();

        $title = $instance['title'];
        $campaign_id = $instance['campaign_id'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'franklin') ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('campaign_id'); ?>"><?php _e('Campaign:', 'franklin') ?>        
            	<select name="<?php echo $this->get_field_name('campaign_id') ?>">
            		<option value="current"><?php _e( 'Campaign currently viewed', 'franklin' ) ?></option>
            		<optgroup label="<?php _e( 'Specific campaigns', 'franklin' ) ?>">
	            		<?php foreach ( $campaigns->posts as $campaign ) : ?>
	            			<option value="<?php echo $campaign->ID ?>" <?php selected( $campaign->ID, $campaign_id ) ?>><?php echo $campaign->post_title ?></option>
	            		<?php endforeach ?>
            		</optgroup>
            	</select>    
            </label>      
        </p>

        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['campaign_id'] = $new_instance['campaign_id'];    
        return $instance;
	}
}