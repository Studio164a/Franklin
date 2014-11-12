	<?php if ( get_franklin_theme()->crowdfunding_enabled && !is_user_logged_in() && get_sofa_crowdfunding()->is_viewing_widget() === false ) : ?>

		<!-- Login form -->
            <?php do_action( 'franklin_login_register_modal' ) ?>
        <!-- End login form -->

    <?php endif ?>