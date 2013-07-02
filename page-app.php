<?php 
/**
 * Application page (no meta, no comments). 
 * Not provided as an option from the template dropdown -- used 
 * as the template for login, registration, profile, purchase and 
 * other "application" pages.
 * 
 * @since Franklin 1.1 
 */
get_header(); ?>

	<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>
		
				<?php the_post() ?>

				<?php get_template_part( 'banner', 'page' ) ?>

				<div class="content content-wrapper fullwidth">		
						
					<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			
					
						<div class="entry cf">
							<?php get_template_part( 'featured_image' ) ?>
							<?php the_content() ?>
						</div>						

					</article>

				</div>

			<?php endwhile ?>

		<?php endif ?>	

<?php get_footer() ?>