<?php 
/**
 * Tag archive template
 */

get_header() ?>

	<?php get_template_part( 'banner' ) ?>

	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive-title">		
				<?php printf( __( 'Tag archives: %s', 'franklin' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
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