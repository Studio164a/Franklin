<?php if ( get_franklin_theme()->crowdfunding_enabled ) : ?>

	<?php $submit_page = sofa_crowdfunding_get_page_url('submit_page') ?>
	<?php $profile_page = sofa_crowdfunding_get_page_url('profile_page') ?>

	<span class="account-links">
		<?php if ( $submit_page ) : ?>
			<a class="user-campaign button with-icon button-alt button-small" href="<?php echo $submit_page ?>" data-icon="&#xf055;"><?php _e( 'Create a campaign', 'franklin' ) ?></a>
		<?php endif ?>
		<?php if ( is_user_logged_in() && $profile_page ) : ?>
			<a class="user-account button with-icon button-alt button-small" href="<?php echo $profile_page ?>" data-icon="&#xf007;"><?php _e('Profile', 'franklin') ?></a>
		<?php elseif ( ! is_user_logged_in() ) : ?>
			<a class="user-login button with-icon button-alt button-small" href="#" data-reveal-id="login-form" data-icon="&#xf007;"><?php _e('Login / Register', 'franklin') ?></a>
		<?php endif ?>
	</span>
<?php endif ?>