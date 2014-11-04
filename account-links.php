<?php $crowdfunding_enabled = get_franklin_theme()->crowdfunding_enabled ?>

<?php if ( $crowdfunding_enabled ) : ?>
	<?php $submit_page = sofa_crowdfunding_get_page_url('submit_page') ?>
	<?php $profile_page = sofa_crowdfunding_get_page_url('profile_page') ?>
<?php endif ?>

<span class="account-links">
	<?php if ( $crowdfunding_enabled && $submit_page ) : ?>

		<a class="user-campaign button with-icon button-alt button-small" href="<?php echo $submit_page ?>" data-icon="&#xf055;"><?php _e( 'Create a campaign', 'franklin' ) ?></a>

	<?php endif ?>

	<?php if ( is_user_logged_in() ) : ?>

	 	<?php if ( $crowdfunding_enabled && $profile_page ) : ?>
			<a class="user-account with-icon button button-alt button-small" href="<?php echo $profile_page ?>" data-icon="&#xf007;"><?php _e('Profile', 'franklin') ?></a>
		<?php endif ?>

		<a class="logout with-icon" href="<?php echo wp_logout_url( get_permalink() ) ?>" data-icon="&#xf08b;"><?php _e('Log out', 'franklin') ?></a>

	<?php elseif ( $crowdfunding_enabled && ! is_user_logged_in() ) : ?>

		<a class="user-login button with-icon button-alt button-small" href="#" data-reveal-id="login-form" data-icon="&#xf007;"><?php _e('Login / Register', 'franklin') ?></a>

	<?php endif ?>
</span>