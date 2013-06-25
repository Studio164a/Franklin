<?php 
/**
 * Template name: Fullwidth Template
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>
		
				<?php the_post() ?>

				<?php get_template_part( 'banner', 'page' ) ?>

				<div class="content content-wrapper">		
						
					<?php get_template_part( 'content', 'page' ) ?>

					<?php comments_template('', true) ?>

				</div>

			<?php endwhile ?>

		<?php endif ?>	

<?php get_footer() ?>