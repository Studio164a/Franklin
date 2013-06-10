<?php
/**
 * Display the post thumbnail, if there is one
 */

if ( has_post_thumbnail() ) : 

	$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );
	?> 

	<div class="featured_image" style="width: <?php echo $thumbnail_src[1] ?>px;">

		<?php $full_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
		<a href="<?php echo $full_src[0] ?>" title="<?php printf( __( 'Go to %s', 'osfa' ), get_the_title() ) ?>">
			<?php the_post_thumbnail() ?> 
			<span class="on_hover"><i class="icon-zoom-in"></i></span>
		</a>		
	</div>

<?php endif ?>