<?php 
/**
 * Templating functions. Some override default Easy Digital Downloads 
 * and/or Astoundify Crowdfunding templates. Some are unique to this theme.
 * 
 * Child themes can override these functions by simply creating 
 * their own function with the same name. 
 * 
 * @package franklin
 */

/**
 * Remove output of variable pricing, and add our own system.
 *
 * @since Franklin 1.4
 * @return void
 */
if ( !function_exists('franklin_atcf_theme_variable_pricing')) {

	function franklin_atcf_theme_variable_pricing() {
		remove_action( 'edd_purchase_link_top', 'edd_purchase_variable_pricing' );
		add_action( 'edd_purchase_link_top', 'atcf_purchase_variable_pricing' );
	}
}

remove_action( 'after_setup_theme', 'atcf_theme_custom_variable_pricing', 100 );
add_action( 'init', 'franklin_atcf_theme_variable_pricing' );

/**
 * Displays the title 
 * 
 * @see edd_before_price_options
 * @return void
 * @since Franklin 1.4
 */
function franklin_edd_before_price_options() {
	?>
	<div class="title-wrapper"><h2 class="block-title"><?php _e( 'Enter Pledge Amount', 'franklin' ) ?></h2></div>
	<?php
}

add_action('edd_before_price_options', 'franklin_edd_before_price_options');


/**
 * Displays the contribution options. 
 * 
 * @see atcf_campaign_contribute_options
 * @return void
 * @since Franklin 1.4
 */
function franklin_atcf_campaign_contribute_options( $prices, $type, $campaign_id ) {
	global $edd_options;

	if ( franklin_is_rewardless_campaign( $campaign_id ) ) : ?>
		
		<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_0" class="edd_price_option_<?php echo $campaign_id ?> hidden" value="0" checked />

	<?php
	elseif ( count( $prices )) : ?>

		<ul class="campaign-pledge-levels">			

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php 
					$has_limit = strlen( $price['limit'] ) > 0;
					$remaining = $price['limit'] - $price['bought'];
					$class = ! $has_limit ? 'limitless' : ( $remaining == 0 ? 'not-available' : 'available' );
				?>

				<li data-price="<?php echo edd_sanitize_amount( $price['amount'] )?>" class="pledge-level <?php echo $class ?>">
					
					<?php if ( ! $has_limit ) : ?>

						<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_<?php echo $i ?>" class="edd_price_option_<?php echo $campaign_id ?> edd_price_options_input" value="<?php echo $i ?>" />
						<h3 class="pledge-title"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
						<span class="pledge-limit"><?php _e( 'Unlimited backers', 'franklin' ) ?></span>
						<p class="pledge-description"><?php echo $price['name'] ?></p>

					<?php else : ?>

						<?php if ( $remaining > 0 ) : ?>
							<input type="radio" name="edd_options[price_id][]" id="edd_price_option_<?php echo $campaign_id ?>_<?php echo $i ?>" class="edd_price_option_<?php echo $campaign_id ?> edd_price_options_input" value="<?php echo $i ?>" />
						<?php endif ?>

						<h3 class="pledge-title"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
						<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'franklin' ), $remaining, $price['limit'] ) ?></span>
						<p class="pledge-description"><?php echo $price['name'] ?></p>

					<?php endif ?>

				</li>

			<?php endforeach ?>

		</ul>

	<?php endif;
}

remove_action('atcf_campaign_contribute_options', 'atcf_campaign_contribute_options', 10, 3);
add_action('atcf_campaign_contribute_options', 'franklin_atcf_campaign_contribute_options', 10, 3);

/**
 * Display the list of pledge options. 
 * 
 * @see edd_purchase_link_end
 * @param int $campaign_id
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_edd_after_price_options') ) {

	function franklin_edd_after_price_options() {		
		?>

		<!-- Text field with pledge button -->
		<div class="campaign-price-input">
			<div class="price-wrapper"><span class="currency"><?php echo sofa_crowdfunding_edd_get_currency_symbol() ?></span><input type="text" name="atcf_custom_price" id="franklin_custom_price" value="" /></div>

		<?php
	}

}

add_action('edd_after_price_options', 'franklin_edd_after_price_options', 10, 2);

/**
 * 
 * 
 */
if ( !function_exists('franklin_edd_purchase_link_top') ) {

	function franklin_edd_purchase_link_top($campaign_id) {
	
		// If it's donations only, add custom price input field
		if ( franklin_is_rewardless_campaign( $campaign_id ) && get_post_meta( $campaign_id, '_variable_pricing', true ) != 1 ) {

			// Display the modal title
			franklin_edd_before_price_options();

			// Then display the custom input field
			franklin_edd_after_price_options();
		}
	}
}
remove_action( 'edd_purchase_link_top', 'atcf_purchase_variable_pricing' );
remove_action( 'edd_purchase_link_top', 'atcf_campaign_contribute_custom_price', 5 );
add_action('edd_purchase_link_top', 'franklin_edd_purchase_link_top');

/**
 * Display the list of pledge options. 
 * 
 * @see edd_purchase_link_end
 * @param int $campaign_id
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_edd_purchase_link_end') ) {

	function franklin_edd_purchase_link_end($campaign_id) {				
		// Close the .campaign-price-input div, which wraps around the text field & pledge button 
		?>
		</div>
		<!-- End text field with pledge button -->		
		<?php
	}
}

add_action('edd_purchase_link_end', 'franklin_edd_purchase_link_end');

/**
 * Filter the title displayed for the pledge button. 
 *
 * @see edd_purchase_link_args
 * @param array $args
 * @return array
 * @since Franklin 1.5.5
 */
if ( !function_exists('franklin_edd_purchase_link_args') ) {

	function franklin_edd_purchase_link_args($args) {
		$args['text'] = ! empty( $edd_options[ 'add_to_cart_text' ] ) 
			? $edd_options[ 'add_to_cart_text' ] 
			: __( 'Pledge', 'franklin' );

		return $args;
	}
}

add_filter('edd_purchase_link_args', 'franklin_edd_purchase_link_args');

/**
 * Determines whether this is a no rewards campaign. 
 * 
 * @param int $campaign_id
 * @return bool
 */
function franklin_is_rewardless_campaign($campaign_id) {

	if ( get_post_meta( $campaign_id, 'campaign_norewards', true ) == 1  // Donation-only is ticked
		|| get_post_meta( $campaign_id, '_variable_pricing', true ) != 1  // Variable pricing is not ticked
		|| count( edd_get_variable_prices( $campaign_id ) ) === 0  // There are no rewards specified
	) {
		return true;	
	}

	return false;
}

/**
 * Displays a pledge button at the bottom of the campaign content. 
 * 
 * @see edd_after_download_content
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists('franklin_edd_append_purchase_link') ) {

	function franklin_edd_append_purchase_link() {
		global $post; 

		$campaign = new ATCF_Campaign( $post->ID );

		if ( $campaign->is_active() ) : ?>

			<p class="campaign-support campaign-support-small"><a class="button accent" data-reveal-id="campaign-form-<?php echo $post->ID ?>" href="#"><?php _e( 'Support', 'franklin' ) ?></a></p>
		
		<?php 
		endif;
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
		<label for="user_nicename"><?php _e( 'Your Name', 'franklin' ); ?></label>
		<input type="text" name="displayname" id="atcf-register-displayname" class="input" value="" />
	</p>

	<p class="atcf-register-email">
		<label for="user_login"><?php _e( 'Email Address', 'franklin' ); ?></label>
		<input type="text" name="user_email" id="atcf-register-user_email" class="input" value="" />
	</p>

	<p class="atcf-register-username">
		<label for="user_login"><?php _e( 'Username', 'franklin' ); ?></label>
		<input type="text" name="user_login" id="atcf-register-user_login" class="input" value="" />
	</p>

	<p class="atcf-register-password">
		<label for="user_pass"><?php _e( 'Password', 'franklin' ); ?></label>
		<input type="password" name="user_pass" id="atcf-register-user_pass" class="input" value="" />
	</p>
	
	<p class="atcf-register-submit">
		<input type="submit" name="submit" id="atcf-register-submit" class="<?php echo apply_filters( 'atcf_shortcode_register_button_class', 'button-primary' ); ?>" value="<?php _e( 'Register', 'franklin' ); ?>" />
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
		
		$campaign = new ATCF_Campaign( $campaign_id );

		// Start the buffer
		ob_start();

		$prices = edd_get_variable_prices( $campaign_id );

		$wrapper_atts = apply_filters( 'franklin_pledge_levels_wrapper_atts', 'class="campaign-pledge-levels"', $campaign_id );

		if ( is_array( $prices ) && count( $prices )) : ?>

			<div id="campaign-pledge-levels-<?php echo $campaign_id ?>" <?php echo $wrapper_atts ?>>

				<?php if ($campaign->is_donations_only()) : ?>

					<p><a class="pledge-button button button-alt button-large accent" data-reveal-id="campaign-form-<?php echo $campaign_id ?>" data-price="<?php echo $price['amount'] ?>" href="#"><?php _e( 'Pledge', 'franklin' ) ?></a></p>

				<?php else : 

					foreach ( $prices as $i => $price ) :			
						
						$has_limit = strlen( $price['limit'] ) > 0;
						$remaining = isset( $price['bought'] ) ? $price['limit'] - count($price['bought']) + 1 : $price['limit'];
						$class = !$has_limit ? 'limitless' : ( $remaining == 0 ? 'not-available' : 'available' );
						?>

						<h3 class="pledge-title" data-icon="&#xf0d7;"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
						<div class="pledge-level cf<?php if ($has_limit && $remaining == 0) echo ' not-available' ?>">

							<?php if ( $has_limit ) : ?>
								<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'franklin' ), $remaining, $price['limit'] ) ?></span>
							<?php else : ?>
								<span class="pledge-limit"><?php _e( 'Unlimited backers', 'franklin' ) ?></span>
							<?php endif ?>

							<p class="pledge-description"><?php echo $price['name'] ?></p>

							<?php if ( $campaign->is_active() && ( !$has_limit || $remaining > 0 ) ) : ?>
								<a class="pledge-button button button-alt button-small accent" data-reveal-id="campaign-form-<?php echo $campaign_id ?>" data-price="<?php echo $price['amount'] ?>" href="#"><?php printf( _x( 'Pledge %s', 'pledge amount', 'franklin' ), edd_currency_filter( edd_format_amount( $price['amount'] ) ) ) ?></a>
							<?php endif ?>
						</div>					

					<?php endforeach;

				endif ?>

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

		if ( empty( $backers ) ): ?>
			
			<p><?php _e( 'No backers yet. Be the first!', 'franklin' ) ?></p>
		
		<?php else :

			$number = count($backers) > $number ? $number : count($backers);		
			?>
			<ul class="masonry-grid">

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

remove_filter( 'the_title', 'edd_microdata_title', 10, 2 );

/**
 * Add the login & register blocks to the modal page.
 *
 * @return void
 * @since Franklin 1.4.2
 */
if ( !function_exists('franklin_login_register_modal') ) {

	function franklin_login_register_modal() {
		?>
		<div class="content-block login-block">
		    <div class="title-wrapper"><h3 class="block-title accent"><?php _e( 'Login', 'franklin') ?></h3></div> 
		    <?php echo atcf_shortcode_login() ?>
		</div>
		<div class="register-block  block last">
		    <div class="title-wrapper"><h3 class="block-title accent"><?php _e( 'Register', 'franklin') ?></h3></div> 
		    <?php echo atcf_shortcode_register() ?>
		</div>
		<?php 
	}

}

add_action( 'franklin_login_register_modal', 'franklin_login_register_modal' );