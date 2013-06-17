		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

			<h1 class="page-title"><?php the_title() ?></h1>

			<div class="entry cf">
				<?php get_template_part( 'featured_image' ) ?>
				<?php the_content() ?>
			</div>						

			<?php get_template_part('meta') ?>

		</article>