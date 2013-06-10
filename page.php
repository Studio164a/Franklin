<?php 
/**
 * The page template
 */

get_header() ?>

	<div id="main_wrap" class="inner_wrap cf<?php if ($featured_image) : ?> has_featured_image<?php endif ?>">	

		<div class="page_wrap">

			<div id="content">				

				<?php if ( have_posts() ) : ?>			

					<?php while ( have_posts() ) : ?>

						<?php the_post() ?>
						
						<?php get_template_part( 'content', 'page' ) ?>

						<?php comments_template('', true) ?>

					<?php endwhile ?>

				<?php endif ?>

			</div>

			<?php get_sidebar() ?>

		</div>

	</div>

<?php get_footer () ?>