<?php 
/**
 * Templating functions to override the campaign submission form.
 * 
 * @package franklin
 */

// Due to the way ATCF changed between version 1.6 and 1.7, we need to provide a fallback for the older version.
$crowdfunding = crowdfunding();

// The old way
if ( $crowdfunding->version < 1.7 ) {

	// Length
	remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_length', 30, 2 );
	add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_length', 30, 2 );

	// Category
	remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_category', 40, 2 );
	add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_category', 40, 2 );

	// Tags
	remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_tags', 45, 2 );
	add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_tags', 45, 2 );

	// Rewards
	remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_rewards', 90, 2 );
	add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_rewards', 90, 2 );

	remove_action( 'atcf_shortcode_submit_fields', 'atcf_shortcode_submit_field_contact_email', 100, 2 );
	add_action( 'atcf_shortcode_submit_fields', 'franklin_atcf_shortcode_submit_field_contact_email', 100, 2 );

	remove_action( 'edd_purchase_form_after_cc_form', 'edd_terms_agreement', 999 );
	add_action( 'edd_purchase_form_after_cc_form', 'franklin_edd_terms_agreement', 999 );
}
// The new way
else {

	/**
	 * This basically has the job of overriding default functions used by ATCF. 
	 * 
	 * @see atcf_submit_campaign()
	 * @return void
	 */
	function franklin_atcf_submit_campaign() {
		remove_action( 'atcf_shortcode_submit_field_number', 'atcf_shortcode_submit_field_number', 10, 4 );
		add_action( 'atcf_shortcode_submit_field_number', 'franklin_atcf_shortcode_submit_field_number', 10, 4 );

		remove_action( 'atcf_shortcode_submit_field_term_checklist', 'atcf_shortcode_submit_field_term_checklist', 10, 4 );
		add_action( 'atcf_shortcode_submit_field_term_checklist', 'franklin_atcf_shortcode_submit_field_term_checklist', 10, 4 );

		remove_action( 'atcf_shortcode_submit_field_rewards', 'atcf_shortcode_submit_field_rewards', 10, 4 );
		add_action( 'atcf_shortcode_submit_field_rewards', 'franklin_atcf_shortcode_submit_field_rewards_action', 10, 4 );

		remove_action( 'atcf_shortcode_submit_field_checkbox', 'atcf_shortcode_submit_field_checkbox', 10, 4 );
		add_action( 'atcf_shortcode_submit_field_checkbox', 'franklin_atcf_shortcode_submit_field_checkbox', 10, 4 );	
	}

	add_action( 'init', 'franklin_atcf_submit_campaign', 11 );		
}

/**
 * Number field.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $atts The shortcoe attribtues.
 * @param $campaign The current campaign (if editing/previewing).
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_number( $key, $field, $atts, $campaign ) {
	if ( $key == 'length' ) {
		franklin_atcf_shortcode_submit_field_length( $atts, $campaign );
	} 
	else {
		atcf_shortcode_submit_field_number( $key, $field, $atts, $campaign );
	}	
}

/**
 * Checkbox field.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $atts The shortcoe attribtues.
 * @param $campaign The current campaign (if editing/previewing).
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_checkbox( $key, $field, $atts, $campaign ) {
	if ( $key == 'tos' ) {
		franklin_atcf_shortcode_submit_field_terms( $atts, $campaign );
	} 
	else {
		atcf_shortcode_submit_field_checkbox( $key, $field, $atts, $campaign );
	}	
}


/**
 * Rewards.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $atts The shortcoe attribtues.
 * @param $campaign The current campaign (if editing/previewing).
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_rewards_action( $key, $field, $atts, $campaign ) {
	franklin_atcf_shortcode_submit_field_rewards( $atts, $campaign );
}


/**
 * Term checklist field.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $atts The shortcoe attribtues.
 * @param $campaign The current campaign (if editing/previewing).
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_term_checklist( $key, $field, $atts, $campaign ) {
	if ( ! atcf_theme_supports( 'campaign-' . $key ) ) {
		return;
	}

	if ( ! function_exists( 'wp_terms_checklist' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	}
?>
	<div class="atcf-submit-campaign-<?php echo esc_attr( $key ); ?>">
		<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $field[ 'label' ] ); ?></label>

		<ul class="atcf-multi-select cf">			
		<?php 
			wp_terms_checklist( isset ( $campaign->ID ) ? $campaign->ID : 0, array( 
				'taxonomy'   => 'download_' . $key,
				'walker'     => new ATCF_Walker_Terms_Checklist
			) );
		?>
		</ul>
	</div>
<?php
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

	$description = sprintf( __( "Your campaign's length can be between %d and %d days", 'franklin' ), $min, $max );

	$start = apply_filters( 'atcf_shortcode_submit_field_length_start', round( ( $min + $max ) / 2 ) );

	$length = $atts[ 'previewing' ] ? $campaign->days_remaining() : null;
?>
	<p class="atcf-submit-campaign-length">
		<label for="length"><?php _e( 'Length (Days)', 'franklin' ); ?></label>
		<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="1" name="length" id="length" value="<?php echo esc_attr( $start ); ?>" placeholder="<?php echo esc_attr( $length ); ?>">
		<span class="description"><?php echo $description ?></span>
	</p>
<?php
}


/**
 * Campaign Category
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_category( $atts, $campaign ) {
	franklin_atcf_shortcode_submit_field_term_checklist( 'category', array( 'label' => __( 'Category', 'franklin' ) ), $atts, $campaign );	
}

/**
 * Campaign Tags
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_tags( $atts, $campaign ) {
	franklin_atcf_shortcode_submit_field_term_checklist( 'tag', array( 'label' => __( 'Tags', 'franklin' ) ), $atts, $campaign );
}

/**
 * Campaign Backer Rewards
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @param array $field
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_rewards( $atts, $campaign ) {
	$rewards  = ( $atts[ 'previewing' ] || $atts[ 'editing' ] ) ? edd_get_variable_prices( $campaign->ID ) : array( 0 => array( 'amount' => null, 'name' => null, 'limit' => null ) );

	$crowdfunding = crowdfunding();

	if ( $crowdfunding->version < 1.7 ) :
		$shipping = $atts[ 'previewing' ] || $atts[ 'editing' ] ? $campaign->needs_shipping() : 0;
		$norewards = $atts[ 'previewing' ] || $atts[ 'editing' ] ? $campaign->is_donations_only() : 0;
?>
	<h3 class="atcf-submit-section backer-rewards"><?php _e( 'Backer Rewards', 'franklin' ); ?></h3>

	<p class="atcf-submit-campaign-shipping">
		<label for="shipping"><input type="checkbox" id="shipping" name="shipping" value="1" <?php checked(1, $shipping); ?> /> <?php _e( 'Collect shipping information on checkout.', 'franklin' ); ?></label>
	</p>

	<p class="atcf-submit-campaign-norewards">
		<label for="norewards"><input type="checkbox" id="norewards" name="norewards" value="1" <?php checked(1, $norewards); ?> /> <?php _e( 'No rewards, donations only.', 'franklin' ); ?></label>
	</p>

<?php		
	endif;

	do_action( 'atcf_shortcode_submit_field_rewards_list_before' ); ?>

	<div class="atcf-submit-campaign-rewards">
		<?php foreach ( $rewards as $key => $reward ) : 
			$disabled = isset ( $reward[ 'bought' ] ) && $reward[ 'bought' ] > 0 ? true : false; 
		?>
		<div class="atcf-submit-campaign-reward">
			<?php do_action( 'atcf_shortcode_submit_field_rewards_before' ); ?>

			<p class="atcf-submit-campaign-reward-price">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][price]"><?php printf( __( 'Amount (%s)', 'franklin' ), edd_currency_filter( '' ) ); ?></label>
				<input class="name" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][price]" id="rewards[<?php echo esc_attr( $key ); ?>][price]" value="<?php echo esc_attr( $reward[ 'amount' ] ); ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-limit">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][limit]"><?php _e( 'Limit', 'franklin' ); ?></label>
				<input class="description" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][limit]" id="rewards[<?php echo esc_attr( $key ); ?>][limit]" value="<?php echo isset ( $reward[ 'limit' ] ) ? esc_attr( $reward[ 'limit' ] ) : null; ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-description">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][description]"><?php _e( 'Reward', 'franklin' ); ?></label>
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

		<p class="atcf-submit-campaign-add-reward">
			<a href="#" class="atcf-submit-campaign-add-reward-button"><?php _e( '+ <em>Add Reward</em>', 'franklin' ); ?></a>
		</p>
	</div>
<?php
}

/**
 * Campaign Contact Email
 *
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_contact_email( $atts, $campaign ) {
?>
	<h3 class="atcf-submit-section payment-information"><?php _e( 'Your Information', 'franklin' ); ?></h3>

	<?php if ( ! $atts[ 'editing' ] ) : ?>
		<p class="atcf-submit-campaign-contact-email">
		<?php if ( ! is_user_logged_in() ) : ?>
			<label for="email"><?php _e( 'Contact Email', 'franklin' ); ?></label>
			<input type="text" name="contact-email" id="contact-email" value="<?php echo $atts[ 'editing' ] ? $campaign->contact_email() : null; ?>" placeholder="<?php if ( ! $atts[ 'editing' ] ) : ?><?php _e( 'An account will be created for you with this email address. It must be active.', 'franklin' ); ?><?php endif; ?>" />			
		<?php else : ?>
			<?php $current_user = wp_get_current_user(); ?>
			<?php printf( __( '<strong>Note</strong>: You are currently logged in as %1$s. This %2$s will be associated with that account. Please <a href="%3$s">log out</a> if you would like to make a %2$s under a new account.', 'franklin' ), $current_user->user_email, strtolower( edd_get_label_singular() ), wp_logout_url( get_permalink() ) ); ?>
		<?php endif; ?>
		</p>
	<?php endif; ?>
<?php
}

/**
 * Renders the Checkout Agree to Terms, this displays a checkbox for users to
 * agree the T&Cs set in the EDD Settings. This is only displayed if T&Cs are
 * set in the EDD Settigs.
 *
 * @since 1.2.1
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function franklin_edd_terms_agreement() {
	global $edd_options;
	if ( isset( $edd_options['show_agree_to_terms'] ) ) {
?>
		<fieldset id="edd_terms_agreement">
				<div id="edd_terms" style="display:none;">
					<?php
						do_action( 'edd_before_terms' );
						echo wpautop( $edd_options['agree_text'] );
						do_action( 'edd_after_terms' );
					?>
				</div>
				<div id="edd_show_terms">
					<a href="#" class="edd_terms_links"><?php _e( 'Show Terms', 'edd' ); ?></a>
					<a href="#" class="edd_terms_links" style="display:none;"><?php _e( 'Hide Terms', 'edd' ); ?></a>
				</div>
				<label for="edd_agree_to_terms">
					<input name="edd_agree_to_terms" class="required" type="checkbox" id="edd_agree_to_terms" value="1"/>
					<?php echo isset( $edd_options['agree_label'] ) ? $edd_options['agree_label'] : __( 'Agree to Terms?', 'edd' ); ?>
				</label>				
		</fieldset>
<?php
	}
}

/**
 * Terms
 *
 * @since 1.2.1
 * @return void
 */
function franklin_atcf_shortcode_submit_field_terms( $atts, $campaign ) {
	edd_agree_to_terms_js();
	franklin_edd_terms_agreement();
}