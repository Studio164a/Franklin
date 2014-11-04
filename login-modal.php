	<?php if ( get_franklin_theme()->crowdfunding_enabled && !is_user_logged_in() && get_sofa_crowdfunding()->is_viewing_widget() === false ) : ?>

		<!-- Login form -->
		<div id="login-form" class="reveal-modal block multi-block">
            <a class="close-reveal-modal icon" data-icon="&#xf057;"></a>
            <?php do_action( 'franklin_login_register_modal' ) ?>
        </div>
        <!-- End login form -->

    <?php endif ?>