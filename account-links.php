<?php if ( get_projection_theme()->crowdfunding_enabled ) : ?>
	<span class="account-links">
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo sofa_crowdfunding_get_page_url('profile_page') ?>" class="user-account button with-icon button-alt button-small"><i class="icon-user"></i><?php _e('Account', 'projection') ?></a>
		<?php else : ?>
			<a href="#" class="user-login button with-icon button-alt button-small" data-reveal-id="login-form"><i class="icon-user"></i><?php _e('Login / Register', 'projection') ?></a>
		<?php endif ?>
	</span>
<?php endif ?>