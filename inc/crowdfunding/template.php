<?php 
/**
 * Templating functions. Some override default Easy Digital Downloads 
 * and/or Astoundify Crowdfunding templates. Some are unique to this theme.
 * 
 * Child themes can override these functions by simply creating 
 * their own function with the same name. 
 * 
 * @package cheers
 */

if ( !function_exists('franklin_edd_before_price_options') ) {

	function franklin_edd_before_price_options($campaign_id) {
		?>
		<div class="title-wrapper"><h2 class="block-title"><?php _e( 'Enter Pledge Amount', 'franklin' ) ?></h2></div>

		<?php
		$prices = edd_get_variable_prices( $campaign_id );		

		if ( count( $prices )) : ?>

		<ul class="campaign-pledge-levels">			

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php $remaining = $price['limit'] - $price['bought'] ?>

				<li data-price="<?php echo $price['amount'] ?>" class="pledge-level<?php if ($remaining == 0) echo ' not-available' ?>">
					<?php if ( $remaining > 0 ) : ?>
						<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_<?php echo $i ?>" class="edd_price_option_<?php echo $campaign_id ?> edd_price_options_input" value="<?php echo $i ?>" />
					<?php endif ?>
					<h3 class="pledge-title"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
					<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'franklin' ), $remaining, $price['limit'] ) ?></span>
					<p class="pledge-description"><?php echo $price['name'] ?></p>
				</li>

			<?php endforeach ?>

		</ul>

		<?php endif ?>

		<!-- Text field with pledge button -->
		<div class="campaign-price-input">
			<span class="price-wrapper alignleft"><span class="currency"><?php echo sofa_crowdfunding_edd_get_currency_symbol() ?></span><input type="text" name="franklin_custom_price" id="franklin_custom_price" value="" /></span>
		<?php
	}
}

add_action('edd_before_price_options', 'franklin_edd_before_price_options');


/**
 * Display the list of pledge options. 
 * 
 * @see edd_purchase_link_end
 * @param int $campaign_id
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_atcf_campaign_contribute_options') ) {

	function franklin_edd_purchase_link_end() {		
		// Close the .campaign-price-input div, which wraps around the text field & pledge button 
		?>
		</div>
		<!-- End text field with pledge button -->		
		<?php
	}
}

add_action('edd_purchase_link_end', 'franklin_edd_purchase_link_end', 10, 2);

/**
 * Displays a pledge button at the bottom of the campaign content. 
 * 
 * @see edd_after_download_content
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_edd_append_purchase_link') ) {

	function franklin_edd_append_purchase_link() {
		?>
		<p class="campaign-support campaign-support-small"><a class="button accent" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'franklin' ) ?></a></p>
		<?php 
	}
}

remove_action('edd_after_download_content', 'edd_append_purchase_link');
add_action('edd_after_download_content', 'franklin_edd_append_purchase_link');

/**
 * Register form
 *
 * @global $edd_options
 * @since Franklin 1.0
 * @return $form
 */
function franklin_atcf_shortcode_register_form() {
	global $edd_options;
?>
	<p class="atcf-register-name">
		<label for="user_nicename"><?php _e( 'Your Name', 'tranklin' ); ?></label>
		<input type="text" name="displayname" id="atcf-register-displayname" class="input" value="" />
	</p>

	<p class="atcf-register-email">
		<label for="user_login"><?php _e( 'Email Address', 'tranklin' ); ?></label>
		<input type="text" name="user_email" id="atcf-register-user_email" class="input" value="" />
	</p>

	<p class="atcf-register-username">
		<label for="user_login"><?php _e( 'Username', 'tranklin' ); ?></label>
		<input type="text" name="user_login" id="atcf-register-user_login" class="input" value="" />
	</p>

	<p class="atcf-register-password">
		<label for="user_pass"><?php _e( 'Password', 'tranklin' ); ?></label>
		<input type="password" name="user_pass" id="atcf-register-user_pass" class="input" value="" />
	</p>
	
	<p class="atcf-register-submit">
		<input type="submit" name="submit" id="atcf-register-submit" class="<?php echo apply_filters( 'atcf_shortcode_register_button_class', 'button-primary' ); ?>" value="<?php _e( 'Register', 'tranklin' ); ?>" />
		<input type="hidden" name="action" value="atcf-register-submit" />
		<?php wp_nonce_field( 'atcf-register-submit' ); ?>
	</p>
<?php
}
remove_action( 'atcf_shortcode_register', 'atcf_shortcode_register_form' );
add_action( 'atcf_shortcode_register', 'franklin_atcf_shortcode_register_form' );


/**
 * Display the pledge levels. 
 * 
 * @param int $campaign_id
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_pledge_levels') ) {

	function franklin_pledge_levels( $campaign_id ) {
			
		// Start the buffer
		ob_start();

		$prices = edd_get_variable_prices( $campaign_id );

		$wrapper_atts = apply_filters( 'franklin_pledge_levels_wrapper_atts', 'class="campaign-pledge-levels"', $campaign_id );

		if ( is_array( $prices ) && count( $prices )) : ?>

			<div id="campaign-pledge-levels-<?php echo $campaign_id ?>" <?php echo $wrapper_atts ?>>

				<?php foreach ( $prices as $i => $price ) : ?>

					<?php $remaining = isset( $price['bought'] ) ? $price['limit'] - count($price['bought']) + 1 : $price['limit'] ?>

					<h3 class="pledge-title" data-icon="&#xf0d7;"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
					<div class="pledge-level cf<?php if ($remaining == 0) echo ' not-available' ?>">										
						<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'franklin' ), $remaining, $price['limit'] ) ?></span>
						<p class="pledge-description"><?php echo $price['name'] ?></p>

						<?php if ($remaining > 0) : ?>
							<a class="pledge-button button button-alt button-small accent" data-reveal-id="campaign-form-<?php echo $campaign_id ?>" data-price="<?php echo $price['amount'] ?>" href="#"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), edd_currency_filter( edd_format_amount( $price['amount'] ) ) ) ?></a>
						<?php endif ?>
					</div>

				<?php endforeach ?>

			</div>

			<?php if ( get_the_ID() != $campaign_id && ! in_array( get_page_template(), array( 'homepage-campaigns.php', 'page-single-campaign.php' ) ) ) : ?>

				<!-- Support modal -->
				<div id="campaign-form-<?php echo $campaign_id ?>" class="campaign-form reveal-modal content-block block">
			        <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
			        <?php echo edd_get_purchase_link( array( 'download_id' => $campaign_id ) ); ?>
			    </div>
			    <!-- End support modal -->

			<?php endif ?>

		<?php endif;

		return apply_filters( 'franklin_pledge_levels', ob_get_clean(), $campaign_id );
	}
}

/**
 * Display a project's campaign backers. 
 * 
 * @param ATCF_Campaign $campaign
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_campaign_backers') ) {

	function franklin_campaign_backers( $campaign, $args = array() ) {

		$defaults = array(
			'number'		=> 10,
			'show_location'	=> true,
			'show_pledge'	=> true, 
			'show_name' 	=> true 
		);

		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		$backers = $campaign->backers();

		// Start the buffer 
		ob_start();

		if ( $backers === false ) : ?>
			
			<p><?php _e( 'No backers yet. Be the first!', 'franklin' ) ?></p>
		
		<?php else :

			$number = count($backers) > $number ? $number : count($backers);		
			?>
			<ul>

			<?php for( $i = 0; $i <= $number; $i++ ) : ?>

				<?php if ( isset( $backers[$i] ) ) : ?>

					<?php $log = $backers[$i] ?>

					<?php if ( ! sofa_crowdfunding_is_backer_anonymous( $log ) ) : ?>

						<?php $backer = sofa_crowdfunding_get_payment($log) ?>

						<li class="campaign-backer"> 			

							<?php echo sofa_crowdfunding_get_backer_avatar( $backer ) ?>

							<div class="if-tiny-hide">
								<?php if ( $show_name ) : ?>

									<h6><?php echo $backer->post_title ?></h6>

								<?php endif ?>

								<?php if ( $show_location || $show_pledge ) : ?>

									<p>
										<?php if ( $show_location ) : ?>

											<?php echo sofa_crowdfunding_get_backer_location( $backer ) ?><br />

										<?php endif ?>

										<?php if ( $show_pledge ) : ?>

											<?php echo sofa_crowdfunding_get_backer_pledge( $backer ) ?>					

										<?php endif ?>

									</p>

								<?php endif ?>
							</div>

						</li>

					<?php endif ?>

				<?php endif ?>
				
			<?php endfor ?>

			</ul>

		<?php endif;

		return apply_filters( 'franklin_campaign_backers', ob_get_clean(), $campaign, $show_location );
	}
}

/**
 * Show the campaign video.
 * 
 * @param ATCF_Campaign $campaign
 * @global $wp_embed
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'franklin_campaign_video' ) ) {

	function franklin_campaign_video($campaign) {
		global $wp_embed;

		// If a campaign object was not passed, do nothing
		if ( !$campaign instanceof ATCF_Campaign )
			return;

		// If there is no video, do nothing
		if ( !$campaign->video() || trim( $campaign->video() ) == 'http://' )
			return;		
		?>

		<!-- Campaign video -->
		<section class="campaign-video">
			<?php echo $wp_embed->run_shortcode('[embed]'.$campaign->video().'[/embed]' ) ?>
		</section>
		<!-- End campaign video -->

		<?php
	}
}

/**
 * Displays the site's crowdfunding stats.
 * 
 * @uses franklin_crowdfunding_stats filter
 * 
 * @return string
 * @since Franklin 1.2
 */
if ( !function_exists( 'franklin_crowdfunding_stats' ) ) {

	function franklin_crowdfunding_stats() {

		$post_count = wp_count_posts('download');

		ob_start();
		?>

		<ul>
			<li><span><?php echo $post_count->publish ?></span>
				<?php echo _n('Campaign', 'Campaigns', $post_count->publish, 'franklin') ?>
			</li>
			<li>				
				<?php printf( __( '%s Funded', 'franklin' ), '<span>' . edd_currency_filter( edd_format_amount( edd_get_total_earnings() ) ) . '</span>' ) ?>
			</li>
			<li>
				<span><?php echo edd_count_total_customers() ?></span>				
				<?php echo _n('Backer', 'Backers', edd_count_total_customers(), 'franklin') ?>
			</li>
		</ul>

		<?php
		return apply_filters( 'franklin_crowdfunding_stats', ob_get_clean(), $post_count );
	}

}

/**
 * Customize comment output. 
 *
 * @param stdClass $comment
 * @param array $args
 * @param int $depth
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'franklin_campaign_comment' ) ) {

	function franklin_campaign_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>

		<li class="pingback">
			<p><?php _e( 'Pingback:', 'franklin' ); ?> <?php comment_author_link() ?></p>
			<?php edit_comment_link( __( 'Edit', 'franklin' ), '<p class="comment_meta">', '</p>' ); ?>
		</li>
		
		<?php	
				break;
			default :
		?>

		<li <?php comment_class( get_option('show_avatars') ? 'avatars' : 'no-avatars' ) ?> id="li-comment-<?php comment_ID(); ?>">

			<?php echo get_avatar( $comment, 50 ) ?>

			<div class="comment-details">
				<?php if ( sofa_comment_is_by_author($comment) ) : ?><small class="post-author with-icon alignright"><i class="icon-star"></i><?php _e('Author', 'franklin') ?></small><?php endif ?>
				<h6 class="comment-author vcard">
					<?php comment_author_link() ?>
				</h6>				
				<div class="comment-text"><?php comment_text() ?></div>
				<p class="comment-meta">
					<span class="comment-date"><?php printf( __( '%s ago', 'franklin' ), human_time_diff( get_comment_time('U', true) ) ) ?></span>
					<span class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => _x( 'Reply', 'reply to comment' , 'franklin' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?></span>
				</p>
			</div>		

		</li>

		<?php
				break;
		endswitch;	
	}
}

/**
 * Campaign Length 
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_length( $atts, $campaign ) {
	global $edd_options;

	if ( $atts[ 'editing' ] )
		return;

	$min = isset ( $edd_options[ 'atcf_campaign_length_min' ] ) ? $edd_options[ 'atcf_campaign_length_min' ] : 14;
	$max = isset ( $edd_options[ 'atcf_campaign_length_max' ] ) ? $edd_options[ 'atcf_campaign_length_max' ] : 48;

	$start = apply_filters( 'atcf_shortcode_submit_field_length_start', round( ( $min + $max ) / 2 ) );

	$length = $atts[ 'previewing' ] ? $campaign->days_remaining() : null;
?>
	<p class="atcf-submit-campaign-length">
		<label for="length"><?php _e( 'Length (Days)', 'atcf' ); ?></label>
		<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="1" name="length" id="length" value="<?php echo esc_attr( $start ); ?>" value="<?php echo esc_attr( $length ); ?>">
		<span class="description"><?php printf( __( "Your campaign's length can be between %d and %d days", 'franklin' ), $min, $max ) ?></span>
	</p>
<?php
}
remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_length', 30, 2 );
add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_length', 30, 2 );

/**
 * Campaign Backer Rewards
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_rewards( $atts, $campaign ) {
	$rewards  = $atts[ 'previewing' ] || $atts[ 'editing' ] ? edd_get_variable_prices( $campaign->ID ) : array();
	$shipping = $atts[ 'previewing' ] || $atts[ 'editing' ] ? $campaign->needs_shipping() : 0;
?>
	<h3 class="atcf-submit-section backer-rewards"><?php _e( 'Backer Rewards', 'tranklin' ); ?></h3>

	<p class="atcf-submit-campaign-shipping">
		<label for="shipping"><input type="checkbox" id="shipping" name="shipping" value="1" <?php checked(1, $shipping); ?> /> <?php _e( 'Collect shipping information on checkout.', 'tranklin' ); ?></label>
	</p>

	<?php do_action( 'atcf_shortcode_submit_field_rewards_list_before' ); ?>

	<div class="atcf-submit-campaign-rewards">
		<?php foreach ( $rewards as $key => $reward ) : $disabled = isset ( $reward[ 'bought' ] ) && $reward[ 'bought' ] > 0 ? true : false; ?>
		<div class="atcf-submit-campaign-reward">
			<?php do_action( 'atcf_shortcode_submit_field_rewards_before' ); ?>

			<p class="atcf-submit-campaign-reward-price">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][price]"><?php printf( __( 'Amount (%s)', 'tranklin' ), edd_currency_filter( '' ) ); ?></label>
				<input class="name" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][price]" id="rewards[<?php echo esc_attr( $key ); ?>][price]" value="<?php echo esc_attr( $reward[ 'amount' ] ); ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-limit">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][limit]"><?php _e( 'Limit', 'tranklin' ); ?></label>
				<input class="description" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][limit]" id="rewards[<?php echo esc_attr( $key ); ?>][limit]" value="<?php echo isset ( $reward[ 'limit' ] ) ? esc_attr( $reward[ 'limit' ] ) : null; ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-description">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][description]"><?php _e( 'Reward', 'tranklin' ); ?></label>
				<textarea class="description" name="rewards[<?php echo esc_attr( $key ); ?>][description]" id="rewards[<?php echo esc_attr( $key ); ?>][description]" rows="3" <?php disabled(true, $disabled); ?>><?php echo esc_attr( $reward[ 'name' ] ); ?></textarea>
			</p>			

			<?php do_action( 'atcf_shortcode_submit_field_rewards_after' ); ?>

			<?php if ( ! $disabled ) : ?>
			<p class="atcf-submit-campaign-reward-remove">
				<a href="#">&times; <?php _e( 'Remove', 'franklin' ) ?></a>
			</p>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>

		<?php if ( ! $atts[ 'previewing' ] && ! $atts[ 'editing' ] ) : ?>
		<div class="atcf-submit-campaign-reward">
			<?php do_action( 'atcf_shortcode_submit_field_rewards_before' ); ?>

			<p class="atcf-submit-campaign-reward-price">
				<label for="rewards[0][price]"><?php printf( __( 'Amount (%s)', 'tranklin' ), edd_currency_filter( '' ) ); ?></label>
				<input class="name" type="text" name="rewards[0][price]" id="rewards[0][price]" placeholder="<?php echo edd_format_amount( 20 ); ?>">
			</p>

			<p class="atcf-submit-campaign-reward-limit">
				<label for="rewards[0][limit]"><?php _e( 'Limit', 'tranklin' ); ?></label>
				<input class="description" type="text" name="rewards[0][limit]" id="rewards[0][limit]" />
			</p>

			<p class="atcf-submit-campaign-reward-description">
				<label for="rewards[0][description]"><?php _e( 'Reward', 'tranklin' ); ?></label>
				<textarea class="description" name="rewards[0][description]" id="rewards[0][description]" rows="3" placeholder="<?php esc_attr_e( 'Description of reward for this level of contribution.', 'tranklin' ); ?>"></textarea>
			</p>			

			<?php do_action( 'atcf_shortcode_submit_field_rewards_after' ); ?>

			<p class="atcf-submit-campaign-reward-remove">
				<a href="#">&times; <?php _e( 'Remove', 'franklin' ) ?></a>
			</p>
		</div>
		<?php endif; ?>

		<p class="atcf-submit-campaign-add-reward">
			<a href="#" class="atcf-submit-campaign-add-reward-button"><?php _e( '+ <em>Add Reward</em>', 'tranklin' ); ?></a>
		</p>
	</div>
<?php
}
remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_rewards', 90, 2 );
add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_rewards', 90, 2 );

/**
 * Campaign Contact Email
 *
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_contact_email( $atts, $campaign ) {
?>
	<h3 class="atcf-submit-section payment-information"><?php _e( 'Your Information', 'tranklin' ); ?></h3>

	<?php if ( ! $atts[ 'editing' ] ) : ?>
		<p class="atcf-submit-campaign-contact-email">
		<?php if ( ! is_user_logged_in() ) : ?>
			<label for="email"><?php _e( 'Contact Email', 'tranklin' ); ?></label>
			<input type="text" name="contact-email" id="contact-email" value="<?php echo $atts[ 'editing' ] ? $campaign->contact_email() : null; ?>" placeholder="<?php if ( ! $atts[ 'editing' ] ) : ?><?php _e( 'An account will be created for you with this email address. It must be active.', 'tranklin' ); ?><?php endif; ?>" />			
		<?php else : ?>
			<?php $current_user = wp_get_current_user(); ?>
			<?php printf( __( '<strong>Note</strong>: You are currently logged in as %1$s. This %2$s will be associated with that account. Please <a href="%3$s">log out</a> if you would like to make a %2$s under a new account.', 'tranklin' ), $current_user->user_email, strtolower( edd_get_label_singular() ), wp_logout_url( get_permalink() ) ); ?>
		<?php endif; ?>
		</p>
	<?php endif; ?>
<?php
}
remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_contact_email', 100, 2 );
add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_contact_email', 100, 2 );
