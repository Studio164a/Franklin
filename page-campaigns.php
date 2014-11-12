<?php 
/*
 Template name: Campaigns Homepage
 */

get_header() ?>
	
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<?php get_template_part( 'campaign', 'featured' ) ?>

			<div class="content-wrapper cf">
				
				<?php if ( strlen( get_the_content() ) ) : ?>

					<div <?php post_class('home-content') ?>>
						<?php the_content() ?>
					</div>

				<?php endif ?>

				<?php get_template_part('campaign', 'grid') ?>
			
			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>