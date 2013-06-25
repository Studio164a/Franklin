		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

			<?php if ( !is_page_template('page-fullwidth.php') ) : ?>
				<h1 class="page-title"><?php the_title() ?></h1>
			<?php endif ?>

			<div class="entry cf">
				<?php get_template_part( 'featured_image' ) ?>
				<?php the_content() ?>
			</div>						

			<?php get_template_part('meta', 'below') ?>

		</article>