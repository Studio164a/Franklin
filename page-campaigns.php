<?php 
/*
 Template name: Campaigns Homepage
 */

get_header() ?>
	
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<?php get_template_part( 'campaign', 'featured' ) ?>

			<div class="content-wrapper">
				
				<?php if ( strlen( get_the_content() ) ) : ?>
					<div <?php post_class('home-content') ?>>
						<?php the_content() ?>
					</div>
				<?php endif ?>

				<div class="campaigns-grid-wrapper">								

					<nav class="campaigns-navigation" role="navigation">
			            <a class="menu-button toggle-button"><i class="icon-th-list"></i></a>
			            <?php sofa_crowdfunding_campaign_nav() ?>				
					</nav>

					<h3 class="section-title"><?php _e( 'Latest Projects', 'franklin' ) ?></h3>

					<div class="content campaigns-grid masonry-grid">					

						<?php $campaigns = new ATCF_Campaign_Query() ?>

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