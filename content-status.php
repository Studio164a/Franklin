		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

			<?php if ( is_single() ) : ?>

				<?php get_template_part('meta', 'above') ?>

			<?php endif ?>

			<?php sofa_post_header() ?>					

			<?php if ( is_single() ) : ?>
					
				<?php get_template_part( 'meta', 'taxonomy' ) ?>				

			<?php else : ?>				

				<?php get_template_part('meta', 'below') ?>

			<?php endif ?>			

		</article>