<?php 
/*
 Template name: Campaigns homepage
 */

get_header() ?>
	
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<?php //the_excerpt() ?>

			<?php get_template_part( 'campaign', 'featured' ) ?>

			<div class="content-wrapper">
				
				<div <?php post_class('home-content') ?>>
					<?php the_content() ?>
				</div>

				<div class="content campaigns-grid">

					<?php $campaigns = new ATCF_Campaign_Query() ?>

					<?php if ( $campaigns->have_posts() ) : ?>

						<?php while ( $campaigns->have_posts() ) : ?>

							<?php $campaigns->the_post() ?>

							<?php get_template_part( 'campaign' ) ?>					

						<?php endwhile ?>
						
						<?php while ( $campaigns->have_posts() ) : ?>

							<?php $campaigns->the_post() ?>

							<?php get_template_part( 'campaign' ) ?>					

						<?php endwhile ?>


					<?php endif ?>

				</div>
			
			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>