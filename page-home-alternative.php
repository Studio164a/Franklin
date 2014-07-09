<?php 
/**
 * Template name: Homepage Alternative
 */


add_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res');

get_header(); 

?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post() ?>

			<article <?php post_class("feature-block center") ?>>

				<div class="shadow-wrapper">	

					<?php get_template_part('layer-slider') ?>				

					<h1 class="page-title"><?php the_title() ?></h1>

					<div class="entry">
						<?php the_content() ?>
					</div>

				</div>

			</article>

			<div class="content-wrapper">

				<?php if ( get_post_meta( get_the_ID(), '_franklin_homepage_2_show_campaigns', true ) ) : ?>

					<?php get_template_part('campaign', 'grid') ?>

				<?php endif ?>				

				<?php if ( get_post_meta( get_the_ID(), '_franklin_homepage_2_show_categories', true ) ) : ?>

					<?php get_template_part('campaign', 'categories') ?>

				<?php endif ?>

			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>