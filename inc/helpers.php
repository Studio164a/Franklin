<?php

/**
 * A collection of helper functions that are used in the theme. 
 * 
 * @package Projection
 * @author Studio164a
 */

/**
 * Returns whether crowdfunding is enabled.
 * 
 * @return bool
 * @since Projection 0.1
 */
function sofa_using_crowdfunding() {
	return get_projection_theme()->crowdfunding_enabled;
}