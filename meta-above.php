			<?php 
			// Hide meta if this is a sticky post
			if (is_sticky() && ! is_singular() ) return; 

			// Hide post meta if that setting is ticked
			if ( franklin_hide_post_meta() ) return; ?>

			<div class="meta meta-above">
				<?php get_template_part( 'meta', 'inside' ) ?>				
			</div>