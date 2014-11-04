<?php 
/**
 * The default blog posts template
 */

get_header() ?>

	<?php get_template_part( 'banner' ) ?>

	<div class="content-wrapper">		

		<div class="content">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : ?>

					<?php the_post() ?>
					
					<?php get_template_part( 'content', get_post_format() ) ?>

				<?php endwhile ?>

			<?php endif ?>

			<?php sofa_content_nav('nav-below') ?>
			
		</div>

	<?php get_sidebar() ?>

	</div>

<?php get_footer () ?>