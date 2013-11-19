<?php 
/**
 * Template name: Homepage Alternative
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<article <?php post_class("feature-block center") ?>>

				<div class="shadow-wrapper">

					<h1 class="page-title"><?php the_title() ?></h1>

					<div class="entry">
						<?php the_content() ?>
					</div>

				</div>

			</article>

			<div class="content-wrapper">

				<?php get_template_part('campaign', 'categories') ?>

				<?php //get_template_part('campaign', 'grid') ?>

			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>