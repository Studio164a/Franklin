<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * A widget to display your campaign's author details.
 * 
 * @see WP_Widget
 * @author Studio164a
 */
class Sofa_Crowdfunding_Author_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'campaign_author_widget', // Base ID
			__( 'Campaign Author', 'franklin'), // Name
			array( 'description' => __( 'Display the campaign creator\'s details.', 'franklin' ) ) // Args
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );		

		// We have to have a campaign id
		if ( !isset( $instance['campaign_id'] ) || $instance['campaign_id'] == '' )
			return;

		$title = apply_filters( 'widget_title', $instance['title'] );
		$campaign_id = $instance['campaign_id'] == 'current' ? get_the_ID() : $instance['campaign_id'];
		$campaign = get_post( $campaign_id );
		$author = get_user_by('id', $campaign->post_author);
		$campaigns = sofa_crowdfunding_get_campaigns_by_user($author->ID);		

		echo $before_widget;

		if ( !empty($title) )
			echo $before_title . $title . $after_title;

		ob_start();		
		?>

		<div class="author-description cf">

			<?php $avatar = get_avatar( $author->ID, 120 ) ?>
			<?php if ( strlen( $avatar ) ) : ?>
				<div class="author-avatar">
					<?php echo $avatar ?>
				</div>
			<?php endif ?>

			<div class="author-stats">
				<h6><?php echo $author->nickname ?></h6>
				<p><?php /*printf( __( "Joined %s / Backed %d / Created %d", 'franklin' ), 
						date('F Y', strtotime($author->user_registered) ), 
						edd_count_purchases_of_customer($author->ID), 
						$campaigns->post_count ) */?>				
					<?php printf( _n( '%d campaign', '%d campaigns', $campaigns->post_count, 'franklin' ), $campaigns->post_count ) ?>
				</p>
			</div>

			<p class="author-links">
				<?php if ($author->user_url) : ?>
					<a href="<?php echo $author->user_url ?>" title="<?php printf( __("Visit %s's website", 'franklin'), $author->nickname ) ?>" class="with-icon" data-icon="&#xf0c1;"><?php echo sofa_condensed_url($author->user_url) ?></a><br />
				<?php endif ?>

				<?php if ($author->twitter) : ?>
					<a href="<?php echo $author->twitter ?>" title="<?php printf( __("Visit %s's Twitter profile", 'franklin'), $author->nickname ) ?>" class="with-icon" data-icon="&#xf099;"><?php echo sofa_condensed_url($author->twitter) ?></a><br />
				<?php endif ?>

				<?php if ($author->facebook) : ?>
					<a href="<?php echo $author->facebook ?>" title="<?php printf( __("Visit %s's Facebook profile", 'franklin'), $author->nickname ) ?>" class="with-icon" data-icon="&#xf09a;"><?php echo sofa_condensed_url($author->facebook) ?></a>
				<?php endif ?>	
			</p>

			<p class="author-bio"><?php echo $author->description ?></p>

			<p class="author-profile-link center"><a href="<?php echo get_author_posts_url($author->ID) ?>" class="button button-alt"><?php _e( 'Profile', 'franklin' ) ?></a></p>

		</div>		


		<?php 
		echo apply_filters( 'sofa_crowdfunding_author_widget', ob_get_clean() );

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

        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['campaign_id'] = $new_instance['campaign_id'];        
        return $instance;
	}
}