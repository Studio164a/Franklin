		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			
		
			<div class="entry cf">
				<?php get_template_part( 'featured_image' ) ?>
				<?php the_content() ?>
			</div>						

			<?php get_template_part('meta', 'below') ?>

		</article>