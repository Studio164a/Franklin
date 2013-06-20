			<?php 
			// Hide meta if this is a sticky post
			if (is_sticky() && ! is_singular() ) return; 

			// Hide post meta if that setting is ticked
			if (get_post_meta(get_the_ID(), '_osfa_hide_post_meta', true) == true) return; ?>

			<div class="meta meta-below">
				<?php get_template_part( 'meta', 'inside' ) ?>				
			</div>