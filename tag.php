<?php 
/**
 * Tag archive template
 */

get_header() ?>

	<div id="main_wrap" class="inner_wrap cf">	

		<?php get_template_part('breadcrumbs') ?>

		<div id="content">	

			<h1 class="archive_title">		
				<?php printf( __( 'Tag archives: %s', 'osfa' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
			</h1>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : ?>

					<?php the_post() ?>
					
					<?php get_template_part( 'content', get_post_format() ) ?>

				<?php endwhile ?>

				<?php osfa_content_nav( 'nav_below' ) ?>

			<?php endif ?>

		</div>

		<?php get_sidebar() ?>

	</div>

<?php get_footer () ?>