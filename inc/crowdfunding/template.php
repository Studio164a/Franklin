<?php 
/**
 * Functions that are used to override the default Crowdfunding & Easy Digital Downloads
 * templates. Note that these functions are called by Crowdfunding & Easy Digital Downloads
 * using hooks. 
 * 
 * Child themes can override these functions by simply providing 
 * their own function with the same name. 
 * 
 * @package cheers
 */

if ( !function_exists('projection_edd_templates_dir') ) {

	function projection_edd_templates_dir($default) {	
		return 'templates/edd';
	}
} // Close function_exists wrapper

add_filter('edd_templates_dir', 'projection_edd_templates_dir');


if ( !function_exists('projection_edd_before_price_options') ) {

	function projection_edd_before_price_options() {
		?>
		<h3><?php _e( 'Choose your level of support', 'projection' ) ?></h3>
		<?php
	}
}

add_action('edd_before_price_options', 'projection_edd_before_price_options');


if ( !function_exists('projection_atcf_campaign_contribute_options') ) {

	function projection_atcf_campaign_contribute_options($prices, $type) {
		foreach ( $prices as $price ) : ?>

		<li><?php echo $price ?></li>

		<?php endforeach;
	}
}

add_action('atcf_campaign_contribute_options', 'projection_atcf_campaign_contribute_options', 10, 2);