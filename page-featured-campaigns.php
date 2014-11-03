<?php 
/*
 Template name: Featured Campaigns
 */

get_header() ?>
	
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<?php get_template_part( 'banner', 'page' ) ?>
			
			<div class="content-wrapper">

				<div <?php post_class('home-content') ?>>
					<?php the_content() ?>
				</div>

				<div class="campaigns-grid-wrapper">								

					<div class="content campaigns-grid masonry-grid">					

						<?php $campaigns = get_sofa_crowdfunding()->get_featured_campaigns() ?>

						<?php if ( $campaigns->have_posts() ) : ?>

							<?php while ( $campaigns->have_posts() ) : ?>

								<?php $campaigns->the_post() ?>

								<?php get_template_part( 'campaign' ) ?>					

							<?php endwhile ?>

						<?php endif ?>

					</div>

				</div>
			
			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>