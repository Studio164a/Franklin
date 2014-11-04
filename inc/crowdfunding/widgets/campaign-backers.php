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
			__( 'Campaign Backers', 'franklin'), // Name
			array( 'description' => __( 'Display a campaign\'s backers.', 'franklin' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );		

		// We have to have a campaign id
		if ( !isset( $instance['campaign_id'] ) || $instance['campaign_id'] == '' )
			return;

		$title = apply_filters( 'widget_title', $instance['title'] );
		$campaign_id = $instance['campaign_id'] == 'current' ? get_the_ID() : $instance['campaign_id'];
		$campaign = new ATCF_Campaign( $campaign_id );
		$backers = $campaign->backers();

		// If there are no backers, users can elect to hide this.
		if ( isset( $instance['hide_if_no_backers'] ) && $instance['hide_if_no_backers'] && empty( $backers ) ) {
			return;
		}
		
		echo $before_widget;

		if ( !empty($title) )
			echo $before_title . $title . $after_title;

		echo franklin_campaign_backers( $campaign, $instance );

		echo $after_widget;
	}

	public function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '', 'campaign_id' => '' ));

		$campaigns = new ATCF_Campaign_Query();

        $title = $instance['title'];
        $campaign_id = $instance['campaign_id'];
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 10;
        $show_location = isset( $instance['show_location'] ) ? $instance['show_location'] : false;
        $show_pledge = isset( $instance['show_pledge'] ) ? $instance['show_pledge'] : false;
        $show_name = isset( $instance['show_name'] ) ? $instance['show_name'] : false;
        $hide_if_no_backers = isset( $instance['hide_if_no_backers'] ) ? $instance['hide_if_no_backers'] : false;
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

        <p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of backers to show:', 'franklin' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" />
		</p>

        <p>
			<label for="<?php echo $this->get_field_id('show_name') ?>"><?php _e( 'Show backer\'s name:', 'franklin' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_name') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_name') ); ?>" <?php checked( $show_name ) ?>>
		</p>

        <p>
			<label for="<?php echo $this->get_field_id('show_pledge') ?>"><?php _e( 'Show backer\'s pledge amount:', 'franklin' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_pledge') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_pledge') ); ?>" <?php checked( $show_pledge ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_location') ?>"><?php _e( 'Show backer\'s location:', 'franklin' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_location') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_location') ); ?>" <?php checked( $show_location ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('hide_if_no_backers') ?>"><?php _e( 'Hide if there are no backers:', 'franklin' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('hide_if_no_backers') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('hide_if_no_backers') ); ?>" <?php checked( $hide_if_no_backers ) ?>>
		</p>

        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['campaign_id'] = $new_instance['campaign_id'];    
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_location'] = isset( $new_instance['show_location'] ) ? true : false;
        $instance['show_pledge'] = isset( $new_instance['show_pledge'] ) ? true : false;
        $instance['show_name'] = isset( $new_instance['show_name'] ) ? true : false;
        $instance['hide_if_no_backers'] = isset( $new_instance['hide_if_no_backers'] ) ? true : false;
        return $instance;
	}
}