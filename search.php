<?php 
/**
 * Search results template
 */

get_header() ?>

	<?php get_template_part( 'banner', 'search' ) ?>	
	
	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive-title"><?php printf( __( 'Showing results for %s', 'projection' ), '&#8220;<span>' . get_search_query() . '</span>&#8221;' ) ?></h1>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : ?>

					<?php the_post() ?>
					
					<?php get_template_part( 'content', get_post_format() ) ?>

				<?php endwhile ?>

				<?php sofa_content_nav( 'nav_below' ) ?>

			<?php endif ?>

		</div>

		<?php get_sidebar() ?>

	</div>

<?php get_footer () ?>