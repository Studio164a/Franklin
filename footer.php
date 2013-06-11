	</div>
	<!-- End main content section -->
	
	<!-- Site footer -->
	<footer id="site_footer" class="wrapper">

		<div id="footer_widgets" class="inner_wrap widget_quatro cf">

			<?php dynamic_sidebar( 'footer' ) ?>			

		</div>

		<!-- You've hit rockbottom -->
		<div id="rockbottom">
			<p class="footer_notice aligncenter"><?php echo get_theme_mod( 'footer_notice' ) ?></p>			
		</div>
		<!-- You've passed rockbottom -->

	</footer>

	<!-- Congratulations, you've reached the end -->

	<?php wp_footer() ?>

</body>
</html>