<?php get_header('widget');

if ( have_posts() ) :

	while ( have_posts() ) :

		the_post();
		?>
		<div class="widget-wrapper" style="width: 275px;">
			<?php get_template_part( 'campaign' ) ?>
		</div>
		<?php 

	endwhile;

endif;

get_footer('widget');