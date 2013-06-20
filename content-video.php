			<article>

				<?php 
				$video = get_post_meta( get_the_ID(), 'video', true );

				if ( !$video )  
					$video = sofa_do_first_embed();

				if ( $video ) : ?>

					<div class="fit-video">
						<?php echo $video ?>
					</div>

				<?php endif ?>				

				<?php sofa_post_header() ?>
					
				<div class="entry cf">

					<?php sofa_video_format_the_content() ?>

				</div>

			</article>