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

if ( !function_exists('projection_edd_before_price_options') ) {

	function projection_edd_before_price_options() {
		?>
		<h2 class="block-title"><?php _e( 'Enter Pledge Amount', 'projection' ) ?></h2>

		<!-- Text field with pledge button -->
		<div class="campaign-price-input">
			<input type="text" name="projection_custom_price" id="projection_custom_price" value="" class="left" />		
		<?php
	}
}

add_action('edd_before_price_options', 'projection_edd_before_price_options');


/**
 * Display the list of pledge options. 
 * 
 * @see edd_purchase_link_end
 * @param int $campaign_id
 * @return void
 * @since Projection 1.0
 */
if ( !function_exists('projection_atcf_campaign_contribute_options') ) {

	function projection_edd_purchase_link_end($campaign_id) {
		$prices = edd_get_variable_prices( $campaign_id );		

		// First, we need to close the .campaign-price-input div, which wraps around the text field & pledge button 
		?>
		</div>
		<!-- End text field with pledge button -->

		<?php if ( count( $prices )) : ?>

		<ul class="campaign-pledge-levels">			

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php $remaining = $price['limit'] - count($price['bought']) + 1 ?>

				<li data-price="<?php echo $price['amount'] ?>" class="pledge-level<?php if ($remaining == 0) echo ' not-available' ?>">
					<?php if ( $remaining > 0 ) : ?>
						<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_<?php echo $i ?>" class="edd_price_option_11 edd_price_options_input" value="<?php echo $i ?>" />
					<?php endif ?>
					<h3 class="pledge-title"><?php printf( _x( 'Pledge %s', 'pledge amount', 'projection' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
					<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'projection' ), $remaining, $price['limit'] ) ?></span>
					<p class="pledge-description"><?php echo $price['name'] ?></p>
				</li>

			<?php endforeach ?>

		<ul class="campaign-pledge-levels">

		<?php endif;
	}
}

add_action('edd_purchase_link_end', 'projection_edd_purchase_link_end', 10, 2);