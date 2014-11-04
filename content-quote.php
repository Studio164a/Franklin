		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>				

			<div class="entry cf">				
				<blockquote>
					<?php the_content() ?>
					<cite><?php the_title() ?></cite>
				</blockquote>				
			</div>

			<?php get_template_part('meta', 'below') ?>

		</article>