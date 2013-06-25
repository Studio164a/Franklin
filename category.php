<?php 
/**
 * Category template
 */

get_header() ?>

	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive-title">		
				<?php printf( __( 'Category: %s', 'projection' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
			</h1>

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