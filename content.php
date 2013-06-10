		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

			<?php get_template_part('sticky') ?>

			<?php sofa_post_header() ?>			

			<div class="entry cf">
				<?php get_template_part( 'featured_image' ) ?>
				<?php the_content() ?>
			</div>						

			<?php get_template_part('meta') ?>

		</article>