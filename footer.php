			</div>
			<!-- End main content section -->
			
			<!-- Site footer -->
			<footer id="site-footer" class="wrapper">

				<div class="footer-left">

					<?php dynamic_sidebar( 'footer_left' ) ?>

				</div>

				<div class="footer-right">

					<?php dynamic_sidebar( 'footer_right' ) ?>

				</div>

				<?php if ( get_theme_mod( 'footer_notice', false ) ) : ?>
					<!-- You've hit rockbottom -->
					<div id="rockbottom">
						<?php if ( function_exists('wpml_languages_list') ) echo wpml_languages_list(0, 'language-list') ?>
						<p class="footer-notice aligncenter"><?php echo get_theme_mod( 'footer_notice' ) ?></p>			
					</div>
					<!-- You've passed rockbottom -->
				<?php endif ?>		

			</footer>		

		</div>
		<!-- End body-wrapper -->

		<?php get_template_part('login', 'modal') ?>

	</div>
	<!-- End site-container -->

	<?php wp_footer() ?>

</body>
</html>