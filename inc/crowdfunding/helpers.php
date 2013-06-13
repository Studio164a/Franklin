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
	$campaign_id = get_theme_mod('campaign', false);

	if ( false === $campaign_id )
		return false;

	return new ATCF_Campaign($campaign_id);
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