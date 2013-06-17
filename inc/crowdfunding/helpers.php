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
 * @since Projection 0.1
 */
function sofa_crowdfunding_get_campaign() {
	return get_sofa_crowdfunding()->get_active_campaign();	
}

/**
 * Get the end date of the given campaign. 
 * 
 * @param ATCF_Campaign $campaign
 * @param bool $json_format 	
 * @since Projection 0.1
 */
function sofa_crowdfunding_get_enddate( $campaign, $json_format = false ) {
	if ( false === ( $campaign instanceof ATCF_Campaign ) )
		return;
		
	$end_date = strtotime( $campaign->__get( 'campaign_end_date' ) );
	$end_date_array = array( 
		'year' => date('Y', $end_date), // Year
		'day' => date('d', $end_date), // Day
		'month' => date('n', $end_date), // Month
		'hour' => date('G', $end_date), // Hour
		'minute' => date('i', $end_date), // Minute
		'second' => date('s', $end_date)  // Second
	);

	return $json_format ? json_encode($end_date_array) : $end_date_array;
}

/**
 * Get the login page URL. 
 * 
 * @param string $page
 * @return string|false
 * @since Projection 0.1
 */
function sofa_crowdfunding_get_page_url($page) {
	global $edd_options;
	return isset( $edd_options[$page] ) ? $edd_options[$page] : false;
}

/**
 * Get currency symbol. 
 * 
 * @return string
 * @since Projection 0.1
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
		default : return $currency;
	}	
}
