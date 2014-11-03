<?php
/** 
 * A replacement for the default register form, using html validation
 * 
 * @since Franklin 1.5.10
 *
 * @return void
 */
function sofa_shortcode_register_form() {
	global $edd_options;
?>
	<p class="atcf-register-name">
		<label for="user_nicename"><?php _e( 'Your Name', 'atcf' ); ?></label>
		<input type="text" name="displayname" id="displayname" class="input" value="" required />
	</p>

	<p class="atcf-register-email">
		<label for="user_login"><?php _e( 'Email Address', 'atcf' ); ?></label>
		<input type="email" name="user_email" id="user_email" class="input" value="" required />
	</p>

	<p class="atcf-register-username">
		<label for="user_login"><?php _e( 'Username', 'atcf' ); ?></label>
		<input type="text" name="user_login" id="user_login" class="input" value="" required />
	</p>

	<p class="atcf-register-password">
		<label for="user_pass"><?php _e( 'Password', 'atcf' ); ?></label>
		<input type="password" name="user_pass" id="user_pass" class="input" value="" required />
	</p>

	<p class="atcf-register-submit">
		<input type="submit" name="submit" id="submit" class="<?php echo apply_filters( 'atcf_shortcode_register_button_class', 'button-primary' ); ?>" value="<?php _e( 'Register', 'atcf' ); ?>" />
		<input type="hidden" name="action" value="atcf-register-submit" />
		<?php wp_nonce_field( 'atcf-register-submit' ); ?>
	</p>
<?php
}

/** 
 * Shortcode body function for the above form
 * 
 * @since Franklin 1.5.10
 *
 * @return void
 */
function sofa_shortcode_register() {
	ob_start();
	?> 
	<div class="atcf-register">
		<form name="registerform" id="registerform" action="" method="post">
			<?php sofa_shortcode_register_form() ?>
		</form>
	</div>
	<?php 
	$form = ob_get_clean();

	return $form;
}

remove_shortcode( 'appthemer_crowdfunding_register', 'atcf_shortcode_register' );
add_shortcode( 'appthemer_crowdfunding_register', 'sofa_shortcode_register' );