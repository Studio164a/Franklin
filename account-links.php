<?php if ( get_projection_theme()->crowdfunding_enabled ) : ?>
	<span class="account-links">
		<?php if ( is_user_logged_in() ) : ?>
			<a class="user-account button with-icon button-alt button-small" href="<?php echo sofa_crowdfunding_get_page_url('profile_page') ?>" data-icon="&#xf007;"><?php _e('Profile', 'projection') ?></a>
		<?php else : ?>
			<a class="user-login button with-icon button-alt button-small" href="#" data-reveal-id="login-form" data-icon="&#xf007;"><?php _e('Login / Register', 'projection') ?></a>
		<?php endif ?>
	</span>
<?php endif ?>