<?php 
/**
 * The page template
 */

get_header() ?>

	<div class="partial">	

		<?php if ( have_posts() ) : ?>			

			<?php while ( have_posts() ) : ?>

				<?php the_post() ?>
				
				<?php get_template_part( 'content', 'page' ) ?>

				<?php comments_template('', true) ?>

			<?php endwhile ?>

		<?php endif ?>

	</div>

<?php get_footer () ?>