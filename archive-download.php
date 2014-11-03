<?php 
/*
 * Projects archive. 
 */

get_header() ?>

	<?php get_template_part( 'banner', 'campaign-archive' ) ?>

	<div class="content-wrapper">
		
		<div class="content campaigns-grid masonry-grid">			

			<?php if ( have_posts() ) : ?>

				<?php while( have_posts() ) : ?>

					<?php the_post() ?>

					<?php get_template_part( 'campaign' ) ?>					

				<?php endwhile ?>				

			<?php endif ?>			

		</div>

		<?php sofa_content_nav( 'nav_below' ) ?>
	
	</div>

<?php get_footer() ?>