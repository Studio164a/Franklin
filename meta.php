			<?php 
			// Meta is only shown on singular pages
			if ( !is_singular() ) return ?>

			<?php wp_link_pages(array( 'before' => '<p class="entry_pages">' . __('Pages: ', 'osfa') ) ) ?>

			<?php get_template_part('sharing') ?>

			<?php 
			// Hide post meta if that setting is ticked
			if (get_post_meta(get_the_ID(), '_osfa_hide_post_meta', true) == true) return; ?>

			<div class="meta cf">
				<p>
					<?php printf( '<i class="icon-user"></i><a href="%s" class="author">%s</a> %s <a href="%s" class="date_posted">%s</a>', 
						get_author_posts_url( get_the_author_meta('ID') ), 
						get_the_author(), 
						_x( 'posted on', 'posted on date', 'osfa' ),
						get_permalink(),
						get_the_time('j F, Y') ) ?>

					<?php if ( comments_open() ) : ?>
						<span class="comment_count"><i class="icon-comments"></i><a href="<?php comments_link() ?>"><?php echo get_comments_number() ?></a></span>
					<?php endif ?>

					<?php if ( has_category() ) : ?>
						<span class="categories"><i class="icon-file"></i><?php the_category(', ') ?></span>
					<?php endif ?>

					<?php if ( has_tag() ) : ?>
						<span class="tags"><i class="icon-tags"></i><?php the_tags('') ?></span>
					<?php endif ?>
				</p>
			</div>