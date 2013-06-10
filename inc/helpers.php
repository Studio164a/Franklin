<?php

/**
 * A collection of helper functions that are used in the theme. 
 * 
 * @package Projection
 * @author Studio164a
 */


/** 
 * Get the currently active campaign. 
 * 
 * @return false|ATCF_Campaign
 */
function projection_get_campaign() {
	$campaign_id = get_theme_mod('campaign', false);

	if ( false === $campaign_id )
		return false;

	return new ATCF_Campaign($campaign_id);
}