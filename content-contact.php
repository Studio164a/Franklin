		<article id="post-<?php the_ID() ?>" <?php post_class('contact-page') ?>>			

			<div class="entry cf">
				<?php get_template_part( 'featured_image' ) ?>
				<?php the_content() ?>
			</div>						

			<?php if ( function_exists( 'ninja_forms_display_form' ) ) : ?> 
				<?php ninja_forms_display_form( get_post_meta( get_the_ID(), 'ninja_forms_form', true ) ) ?>
			<?php endif ?>

		</article>