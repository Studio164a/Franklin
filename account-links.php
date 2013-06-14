<?php if ( get_projection_theme()->crowdfunding_enabled ) : ?>
	<span class="account-links">
		<?php if ( is_user_logged_in() ) : ?>
			<a href="" class="user-account"><?php _e('Account', 'projection') ?></a>
		<?php else : ?>
			<a href="<?php echo sofa_crowdfunding_get_page_url('login_page') ?>" class="user-login"><?php _e('Login', 'projection') ?></a>
			<a href="<?php echo sofa_crowdfunding_get_page_url('register_page') ?>" class="user-register"><?php _e('Register', 'projection') ?></a>
		<?php endif ?>
	</span>
<?php endif ?>