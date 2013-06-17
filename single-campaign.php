<?php 
/**
 * Front page template. This is where it's at.
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<?php while( have_posts() ) : ?>

			<?php the_post() ?>

			<?php get_template_part('campaign', 'blurb') ?>

			<?php get_template_part('campaign', 'video') ?>

			<div class="content-wrapper">

				<div class="content">
					
					<?php get_template_part('campaign', 'content') ?>

					<?php get_template_part('campaign', 'backers') ?>

					<?php comments_template('', true) ?>

				</div>

				<aside class="sidebar sidebar-campaign">

					<?php get_template_part('campaign', 'updates') ?>

					<?php get_template_part('campaign', 'pledge') ?>

					<?php get_template_part('widget', 'blog') ?>

				</aside>

			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>