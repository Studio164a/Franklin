<?php 
/**
 * Campaign edit page.
 */

get_header(); ?>

	<?php get_template_part( 'banner', 'campaign-edit' ) ?>

	<div class="content-wrapper">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>

				<?php the_post() ?>

				<div class="content">									

					<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

						<div class="title-wrapper"><h2 class="post-title"><?php the_title() ?></h2></div>
						
						<div class="entry cf">				
							<?php echo atcf_shortcode_submit( array( 'editing' => true ) ) ?>
						</div>						

					</article>									

				</div>

			<?php endwhile ?>				

		<?php endif ?>

		<?php get_sidebar() ?>

	</div>	

<?php get_footer() ?>		