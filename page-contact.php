<?php if ( !defined( 'NINJA_FORMS_VERSION' ) ) exit;

/**
 * Template name: Contact Page Template
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>
		
				<?php the_post() ?>

				<?php get_template_part( 'banner', 'page' ) ?>

				<div class="content content-wrapper">		
						
					<?php get_template_part( 'content', 'contact' ) ?>

					<?php comments_template('', true) ?>

				</div>

			<?php endwhile ?>

		<?php endif ?>	

<?php get_footer() ?>