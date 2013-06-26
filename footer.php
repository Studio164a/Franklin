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
					<p class="footer-notice aligncenter"><?php echo get_theme_mod( 'footer_notice' ) ?></p>			
				</div>
				<!-- You've passed rockbottom -->
			<?php endif ?>		

		</footer>		

	</div>
	<!-- End body-wrapper -->

	<?php wp_footer() ?>

</body>
</html>