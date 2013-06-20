<?php 
/**
 * Archive template
 */

get_header() ?>


	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive_title">		
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: %s', 'projection' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'projection' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'projection' ) ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'projection' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'projection' ) ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Archives', 'projection' ); ?>
				<?php endif; ?>
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