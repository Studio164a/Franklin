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
			__( 'Campaign Pledge Levels', 'projection'), // Name
			array( 'description' => __( 'Display a campaign\'s pledge levels.', 'projection' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );

		// We have to have a campaign id
		if ( !isset( $instance['campaign_id']) )
			return;

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( !empty($title) )
			echo $before_title . $title . $after_title;

		echo sofa_crowdfunding_pledge_levels( $instance['campaign_id'] );

		echo $after_widget;
	}

	public function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '', 'campaign_id' => '' ));

		$campaigns = new ATCF_Campaign_Query();

        $title = $instance['title'];
        $campaign_id = $instance['campaign_id'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'projection') ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('campaign_id'); ?>"><?php _e('Campaign:', 'projection') ?>        
            	<select name="<?php echo $this->get_field_name('campaign_id') ?>">
            		<option><?php _e( 'Select', 'projection' ) ?></option>
            		<?php foreach ( $campaigns->posts as $campaign ) : ?>
            			<option value="<?php echo $campaign->ID ?>" <?php selected( $campaign->ID, $campaign_id ) ?>><?php echo $campaign->post_title ?></option>
            		<?php endforeach ?>
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


/**
 * Display the pledge levels. 
 * 
 * @param int $campaign_id
 * @return string
 * @since Projection 1.0
 */
function sofa_crowdfunding_pledge_levels( $campaign_id ) {
		
		// Start the buffer
		ob_start();

		$prices = edd_get_variable_prices( $campaign_id );

		$wrapper_atts = apply_filters( 'sofa_crowdfunding_pledge_levels_wrapper_atts', 'class="campaign-pledge-levels"', $campaign_id );

		if ( is_array( $prices ) && count( $prices )) : ?>

		<div id="campaign-pledge-levels-<?php echo $campaign_id ?>" <?php echo $wrapper_atts ?>>

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php $remaining = $price['limit'] - count($price['bought']) + 1 ?>

				<h3 class="pledge-title" data-icon="&#xf0d7;"><?php printf( _x( 'Pledge %s', 'pledge amount', 'projection' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
				<div class="pledge-level cf<?php if ($remaining == 0) echo ' not-available' ?>">										
					<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'projection' ), $remaining, $price['limit'] ) ?></span>
					<p class="pledge-description"><?php echo $price['name'] ?></p>

					<?php if ($remaining > 0) : ?>
						<a class="pledge-button button button-alt button-small accent" data-reveal-id="campaign-form" data-price="<?php echo $price['amount'] ?>" href="#"><?php printf( _x( 'Pledge %s', 'pledge amount', 'projection' ), edd_currency_filter( edd_format_amount( $price['amount'] ) ) ) ?></a>
					<?php endif ?>
				</div>

			<?php endforeach ?>

		</div>

		<?php endif;

		return apply_filters( 'sofa_crowdfunding_pledge_levels', ob_get_clean(), $campaign_id );
}

/** 
 * Shortcode wrapper for sofa_crowdfunding_pledge_levels template function.
 *
 * @see sofa_crowdfunding_pledge_levels
 * @param array $atts
 * @return string|void
 * @since Projection 1.0
 */
function sofa_crowdfunding_pledge_levels_shortcode( $atts ) {
	global $post;

	if ( isset( $atts['campaign_id']) ) {
		$campaign_id = $atts['campaign_id'];
	}
	elseif ( $post->post_type == 'download' ) {		
		$campaign_id = $post->ID;
	}
	else {
		$active_campaign = get_sofa_crowdfunding()->get_active_campaign();

		if ( $active_campaign == false )
			return;

		$campaign_id = $active_campaign->ID;
	}

	return sofa_crowdfunding_pledge_levels($campaign_id);
}