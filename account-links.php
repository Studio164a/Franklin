<?php if ( get_franklin_theme()->crowdfunding_enabled ) : ?>
	<span class="account-links">
			<a class="user-campaign button with-icon button-alt button-small" href="<?php echo sofa_crowdfunding_get_page_url('submit_page') ?>" data-icon="&#xf055;"><?php _e( 'Create a campaign', 'franklin' ) ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a class="user-account button with-icon button-alt button-small" href="<?php echo sofa_crowdfunding_get_page_url('profile_page') ?>" data-icon="&#xf007;"><?php _e('Profile', 'franklin') ?></a>
		<?php else : ?>
			<a class="user-login button with-icon button-alt button-small" href="#" data-reveal-id="login-form" data-icon="&#xf007;"><?php _e('Login / Register', 'franklin') ?></a>
		<?php endif ?>
	</span>
<?php endif ?>