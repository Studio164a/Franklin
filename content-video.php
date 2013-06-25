			<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

				<?php 
				$video = get_post_meta( get_the_ID(), 'video', true );

				if ( !$video )  
					$video = sofa_do_first_embed();

				if ( $video ) : ?>

					<div class="fit-video">
						<?php echo $video ?>
					</div>

				<?php endif ?>				

				<?php if ( is_single() ) : ?>

					<?php get_template_part('meta', 'above') ?>

				<?php endif ?>

				<?php sofa_post_header() ?>			

				<div class="entry cf">				
					<?php sofa_video_format_the_content() ?>			

					<?php wp_link_pages(array( 'before' => '<p class="entry_pages">' . __('Pages: ', 'franklin') ) ) ?>
				</div>						

				<?php if ( is_single() ) : ?>
						
					<?php get_template_part( 'meta', 'taxonomy' ) ?>				

				<?php else : ?>				

					<?php get_template_part('meta', 'below') ?>

				<?php endif ?>			

			</article>