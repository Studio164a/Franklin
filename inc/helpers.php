<?php

/**
 * A collection of helper functions that are used in the theme. 
 * 
 * @package Franklin
 * @author 	Studio164a
 */

/**
 * Returns whether crowdfunding is enabled.
 * 
 * @return 	boolean
 * @since 	Franklin 0.1
 */
function franklin_using_crowdfunding() {
	return get_franklin_theme()->crowdfunding_enabled;
}