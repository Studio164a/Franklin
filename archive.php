<?php 
/**
 * Archive template
 */

get_header() ?>

	<?php get_template_part( 'banner' ) ?>

	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive-title">		
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: %s', 'franklin' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'franklin' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'franklin' ) ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'franklin' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'franklin' ) ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Archives', 'franklin' ); ?>
				<?php endif; ?>
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