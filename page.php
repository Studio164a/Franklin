<?php 
/**
 * Default page template.
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<?php get_template_part( 'banner', 'page' ) ?>

			<div class="content-wrapper">

				<div class="content">									
							
					<?php get_template_part( 'content', 'page' ) ?>

					<?php comments_template('', true) ?>

				<?php endwhile ?>

			<?php endif ?>

		</div>

	<?php get_sidebar() ?>

	</div>

<?php get_footer() ?>