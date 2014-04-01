<?php
/**
 * A collection of miscellaneous helper functions used by the 
 * theme & child themes. 
 * 
 * @package cheers
 */

/** 
 * Get the currently active campaign. 
 * 
 * @return false|ATCF_Campaign
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_campaign() {
	return get_sofa_crowdfunding()->get_active_campaign();	
}

/**
 * The callback function for the campaigns navigation
 * 
 * @param bool $echo
 * @return string
 * @since Franklin 1.1
 */
function sofa_crowdfunding_campaign_nav($echo = true) {	
	$categories = get_categories( array( 'taxonomy' => 'download_category', 'orderby' => 'name', 'order' => 'ASC' ) );

	if ( empty( $categories ) )
		return;

	$html = '<ul class="menu menu-site"><li class="download_category with-icon" data-icon="&#xf02c;">'.__('Categories', 'franklin');
	$html .= '<ul><li><a href="'.get_post_type_archive_link('download').'">'.__('All', 'franklin').'</a></li>';

	foreach ( $categories as $category ) {
		$html .= '<li><a href="'.esc_url( get_term_link($category) ).'">'.$category->name.'</a></li>';
	}

	$html .= '</li></ul>';

	if ( $echo === false ) 
		return $html;
	
	echo $html;
}

/**
 * Get the end date of the given campaign. 
 * 
 * @param ATCF_Campaign $campaign
 * @param bool $json_format 	
 * @return mixed
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_enddate( $campaign, $json_format = false ) {
	if ( false === ( $campaign instanceof ATCF_Campaign ) )
		return;		

	$end_date = strtotime( $campaign->end_date() );

	$end_date_array = array( 
		'year' => date('Y', $end_date), // Year
		'day' => date('d', $end_date), // Day
		'month' => date('n', $end_date), // Month
		'hour' => date('G', $end_date), // Hour
		'minute' => date('i', $end_date), // Minute
		'second' => date('s', $end_date)  // Second
	);

	return sprintf( date( "j F Y H:i:s", $end_date ), get_option( 'timezone_string', date_default_timezone_get() ) );


	// echo '<pre>'; print_r( $end_date_array ); 

	// $date_timezone = new DateTimeZone( get_option( 'timezone_string', date_default_timezone_get() ) );
	// $date_time = new DateTime($campaign->end_date(), $date_timezone);

	// $date_time2 = new DateTime('now', $date_timezone);

	// echo $date_time->format('O');
	// echo PHP_EOL;
	// echo $date_time2->format('O');
	// echo PHP_EOL;

	// // echo $date_time->getOffset();

	// die;

	// return $json_format ? json_encode($end_date_array) : $end_date_array;
}

/**
 * Get the time elapsed since the campaign ended. 
 * 
 * @param ATCF_Campaign $campaign
 * @param bool $readable 
 * @return string|int
 * @since Franklin 1.3
 */
function sofa_crowdfunding_get_time_since_ended( $campaign, $readable = true ) {
	if ( false === ( $campaign instanceof ATCF_Campaign ) )
		return;

	$end_date = strtotime( $campaign->end_date() );

	// Return it as a readable string
	if ( $readable ) {
		return human_time_diff( $end_date, current_time('timestamp') ) . ' ' . __( 'ago', 'franklin' );
	}

	// Return as an int representing the seconds elapsed
	return $end_date - current_time('timestamp');
}

/**
 * Get the amount of time left in the campaign. 
 *
 * @param ATCF_Campaign $campaign
 * @return string
 * @since Franklin 1.5.5
 */
function sofa_crowdfunding_get_time_left( ATCF_Campaign $campaign ) {
	$transient = sprintf( 'campaign-time-left-%d', $campaign->ID );

	$value = get_transient($transient);

	$value = false;

	if ( ! $value ) {

		$set_transient = true;

		if ( $campaign->is_endless() ) {

			$value = sprintf( "<span>%s</span>%s", 
				__('Campaign', 'franklin'),
				__('does not end', 'franklin')
			);

			// Cache as long as possible
			$expiration = 0;
		}
		elseif ( !$campaign->is_active() ) {

			$value = sprintf( "<span>%s</span>%s", 
				__('Campaign', 'franklin'),
				__('is finished', 'franklin')
			);

			// Cache as long as possible
			$expiration = 0;
		}
		else {
			$expires = strtotime( $campaign->end_date() );
			$now = current_time('timestamp');

			// Exact amount of seconds left
			$diff = $expires - $now;

			// Days left, rounded down
			$days_remaining = floor( $diff / 86400 );
			
			// At least a day left
			if ( $days_remaining >= 1 ) {

				$value = sprintf("<span>%s</span>%s", 
					_n('1', $days_remaining, $days_remaining, 'franklin'), 
					_n('day left', 'days left', $days_remaining, 'franklin')
				);

				// Cache until this day is over
				$expiration = $diff - ( $days_remaining * 86400 );

			}
			// Less than a day left
			else {

				// Hours left, rounded down
				$hours_remaining = floor( $diff / 3600 );

				// At least an hour left
				if ( $hours_remaining >= 1 ) {

					$value = sprintf("<span>%s</span>%s", 
						_n('1', $hours_remaining, $hours_remaining, 'franklin'), 
						_n('hour left', 'hours left', $hours_remaining, 'franklin')
					);

					// Cache until this hour is over				
					$expiration = $diff - ( $hours_remaining * 3600 );								
				}
				// Less than an hour left
				else {					
					// Number of minutes left, rounded UP
					$minutes_remaining = ceil( $diff / 60 );

					$value = sprintf("<span>%s</span>%s", 
						_n('One', $minutes_remaining, $minutes_remaining, 'franklin'), 
						_n('minute left', 'minutes left', $minutes_remaining, 'franklin')
					);

					// No point caching this
					$set_transient = false;						
				}
			}
		} 

		// Set transient if there is more than an hour remaining
		if ( $set_transient ) {
			set_transient( $transient, $value, $expiration );
		}
	}

	return $value;
}

/**
 * Get the login page URL. 
 * 
 * @param string $page
 * @return string|false
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_page_url($page) {
	global $edd_options;
	
	if ( !isset( $edd_options[$page] ) || $edd_options[$page] == 0 )
		return false;

	return get_permalink( $edd_options[$page] );
}

/**
 * Get currency symbol. 
 * 
 * @return string
 * @since Franklin 1.0
 */
function sofa_crowdfunding_edd_get_currency_symbol() {
	global $edd_options;

	$currency = edd_get_currency();

	switch ( $currency ) {
		case "GBP" : return '&pound;'; break;
		case "BRL" : return 'R&#36;'; break;
		case "USD" :
		case "AUD" :
		case "CAD" :
		case "HKD" :
		case "MXN" :
		case "SGD" : return '&#36;'; break;
		case "JPY" : return '&yen;'; break;
		case "EUR" : return '&euro;'; break;
		default : return $currency;
	}	
}

/**
 * Get the payment ID for the log object.
 * 
 * @param WP_Post $log
 * @return int 
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_payment($log) {
	return get_post( get_post_meta( $log->ID, '_edd_log_payment_id', true ) ); 
}

/**
 * Return whether the backer is anonymous.
 * 
 * @param WP_Post $log
 * @return bool
 * @since Franklin 1.0
 */
function sofa_crowdfunding_is_backer_anonymous($log) {
	$payment_meta = edd_get_payment_meta( get_post_meta( $log->ID, '_edd_log_payment_id', true ) );
	return $payment_meta['anonymous'];
}

/**
 * Get the avatar for the backer. 
 * 
 * @param WP_Post $backer
 * @param int $size
 * @return string
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_backer_avatar($backer, $size = 115) {
	return get_avatar( edd_get_payment_user_email($backer->ID), $size, '', $backer->post_title );
}

/**
 * Get the backer's location. 
 * 
 * @param WP_Post $backer
 * @return string|void
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_backer_location($backer) {
	$meta = get_post_meta( $backer->ID, '_edd_payment_meta', true );
	if ( !isset( $meta['shipping'] ) ) 
		return;

	return apply_filters('sofa_backer_location', sprintf( "%s, %s", $meta['shipping']['shipping_city'], $meta['shipping']['shipping_country'] ), $meta, $backer );
}

/**
 * Get the backer's pledge amount. 
 * 
 * @param WP_Post $backer
 * @param bool $formatted
 * @return string
 * @since Franklin 1.0
 */
function sofa_crowdfunding_get_backer_pledge($backer, $formatted = true) {
	if ( $formatted ) {
		return edd_currency_filter( edd_format_amount( edd_get_payment_amount($backer->ID) ) );
	}

	return edd_get_payment_amount($backer->ID);	
}

/**
 * Counts the total number of customers. 
 *  
 * @global object $wpdb
 * @return int
 * @since Franklin 1.2
 */
if ( !function_exists( 'edd_count_total_customers' ) ) {
	function edd_count_total_customers() {
		global $wpdb;
		$count = $wpdb->get_col( "SELECT COUNT(DISTINCT meta_value) FROM $wpdb->postmeta WHERE meta_key = '_edd_payment_user_email'" );
		return $count[0];
	}
}

/**
 * Determines whether to show the campaign's countdown. 
 * 
 * The countdown is only shown if the campaign is finite and still active.
 * 
 * @param ATCF_Campaign $campaign
 * @return bool
 * @since Franklin 1.3
 */
function sofa_crowdfunding_show_countdown($campaign) {
	if ( false === ( $campaign instanceof ATCF_Campaign ) )
		return;

	return $campaign->is_active() && ( ! method_exists($campaign, 'is_endless') || ! $campaign->is_endless() );
} 

/**
 * Returns the campaigns that a specific user has created.
 * 
 * @global object $wpdb
 * @param int $user_id
 * @return WP_Query
 * @since Franklin 1.5
 */
function sofa_crowdfunding_get_campaigns_by_user($user_id = null) {
	global $wpdb; 

	if (is_null($user_id)) {
		$user_id = get_current_user_id();
	}

	return new ATCF_Campaign_Query( apply_filters( 'sofa_campaigns_by_user_args', array(
		'author' => $user_id, 
		'post_status' => 'publish', 
		'posts_per_page' => -1
	) ) );
}

/**
 * Returns the number of purchases a user has made. 
 *
 * @param int $user_id
 * @return int
 * @since Franklin 1.5.4
 */
function sofa_crowdfunding_get_user_purchase_count($user_id) {
	if ( empty( $user_id ) )
		$user_id = get_current_user_id();

	$stats = edd_get_purchase_stats_by_user( $user_id );

	return isset( $stats['purchases'] ) ? $stats['purchases'] : 0;
}

