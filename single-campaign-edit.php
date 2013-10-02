<?php 
/**
 * Campaign edit page. 
 */
get_header(); ?>

	<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>
		
				<?php the_post() ?>

				<?php get_template_part( 'banner', 'campaign-edit' ) ?>

				<div class="content content-wrapper fullwidth">							
						
					<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

						<?php if ( get_post_status() != 'draft' ) : ?>
							<div class="title-wrapper"><h2 class="post-title"><?php the_title() ?></h2></div>
						<?php endif ?>
						
						<div class="entry cf">				
							<?php echo atcf_shortcode_submit( array( 'editing' => true ) ) ?>
						</div>						

					</article>	

				</div>

			<?php endwhile ?>

		<?php endif ?>	

<?php get_footer() ?>		