				<p class="center">
					<span class="date-posted"><?php printf( _x( 'Posted on %s', 'posted on date', 'franklin' ), '<a href="'.get_permalink().'">'.get_the_time(get_option('date_format')).'</a>' ) ?></span>
					<span class="author"><?php printf( _x( 'By %s', 'by author', 'franklin' ), '<a href="'.get_author_posts_url( get_the_author_meta('ID') ) .'">'.get_the_author().'</a>' ) ?></span>

					<?php if ( comments_open() ) : ?>
						<span class="comment-count"><?php printf( _n( 'With %s comment', 'With %s comments', get_comments_number(), 'franklin' ), '<a href="' . get_comments_link() . '">' . get_comments_number() ) ?></a></span>
					<?php endif ?>
				</p>