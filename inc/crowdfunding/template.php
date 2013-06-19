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

	function projection_edd_before_price_options($campaign_id) {
		?>
		<div class="title-wrapper"><h2 class="block-title"><?php _e( 'Enter Pledge Amount', 'projection' ) ?></h2></div>

		<?php
		$prices = edd_get_variable_prices( $campaign_id );		

		if ( count( $prices )) : ?>

		<ul class="campaign-pledge-levels">			

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php $remaining = $price['limit'] - count($price['bought']) + 1 ?>

				<li data-price="<?php echo $price['amount'] ?>" class="pledge-level<?php if ($remaining == 0) echo ' not-available' ?>">
					<?php if ( $remaining > 0 ) : ?>
						<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_<?php echo $i ?>" class="edd_price_option_<?php echo $campaign_id ?> edd_price_options_input" value="<?php echo $i ?>" />
					<?php endif ?>
					<h3 class="pledge-title"><?php printf( _x( 'Pledge %s', 'pledge amount', 'projection' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
					<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'projection' ), $remaining, $price['limit'] ) ?></span>
					<p class="pledge-description"><?php echo $price['name'] ?></p>
				</li>

			<?php endforeach ?>

		</ul>

		<?php endif ?>

		<!-- Text field with pledge button -->
		<div class="campaign-price-input">
			<span class="price-wrapper left"><span class="currency"><?php echo sofa_crowdfunding_edd_get_currency_symbol() ?></span><input type="text" name="projection_custom_price" id="projection_custom_price" value="" /></span>
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

	function projection_edd_purchase_link_end() {		
		// Close the .campaign-price-input div, which wraps around the text field & pledge button 
		?>
		</div>
		<!-- End text field with pledge button -->		
		<?Php
	}
}

add_action('edd_purchase_link_end', 'projection_edd_purchase_link_end', 10, 2);

/**
 * Displays a pledge button at the bottom of the campaign content. 
 * 
 * @see edd_after_download_content
 * @return void
 * @since Projection 1.0
 */
if ( !function_exists('projection_edd_append_purchase_link') ) {

	function projection_edd_append_purchase_link() {
		?>
		<p class="campaign-support campaign-support-small"><a class="button accent" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'projection' ) ?></a></p>
		<?php 
	}
}

remove_action('edd_after_download_content', 'edd_append_purchase_link');
add_action('edd_after_download_content', 'projection_edd_append_purchase_link');


/**
 * Customize comment output. 
 *
 * @param stdClass $comment
 * @param array $args
 * @param int $depth
 * @return string
 * @since Projection 1.0
 */
if ( !function_exists( 'projection_campaign_comment' ) ) {

	function projection_campaign_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>

		<li class="pingback">
			<p><?php _e( 'Pingback:', 'projection' ); ?> <?php comment_author_link() ?></p>
			<?php edit_comment_link( __( 'Edit', 'projection' ), '<p class="comment_meta">', '</p>' ); ?>
		</li>
		
		<?php	
				break;
			default :
		?>

		<li <?php comment_class( get_option('show_avatars') ? 'avatars' : 'no-avatars' ) ?> id="li-comment-<?php comment_ID(); ?>">

			<?php echo get_avatar( $comment, 50 ) ?>

			<div class="comment-details">
				<?php if ( sofa_comment_is_by_author($comment) ) : ?><small class="post-author with-icon alignright"><i class="icon-star"></i><?php _e('Author', 'projection') ?></small><?php endif ?>
				<h6 class="comment-author vcard">
					<?php comment_author_link() ?>
				</h6>				
				<div class="comment-text"><?php comment_text() ?></div>
				<p class="comment-meta">
					<span class="comment-date"><?php printf( __( '%s ago', 'projection' ), human_time_diff( get_comment_time('U', true) ) ) ?></span>
					<span class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => _x( 'Reply', 'reply to comment' , 'projection' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?></span>
				</p>
			</div>		

		</li>

		<?php
				break;
		endswitch;	
	}
}

/**
 * Displays the comment field if the user is logged in and this is a campaign.
 * 
 * @uses comment_form_field_comment
 * @param string $default
 * @return string
 * @since Projection 1.0
 */
if ( !function_exists( 'projection_campaign_comment_field' )) {

	function projection_comment_form_field_comment($default) {
		global $post;

		if ( is_user_logged_in() && get_post_type() == 'download' )
			return sofa_comment_form_field_comment();
	}
}

add_filter( 'comment_form_field_comment', 'projection_comment_form_field_comment' );




