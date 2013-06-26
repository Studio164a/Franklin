<?php
/**
 * Display the post thumbnail, if there is one.
 */

if ( has_post_thumbnail() ) : 

	$thumbnail_id = get_post_thumbnail_id();
	$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'post-thumbnail' );
	?> 

	<div class="featured-image">

		<?php $full_src = wp_get_attachment_image_src( $thumbnail_id, 'full' ); ?>

		<?php if ( is_single() ) : ?>

			<a href="<?php echo $full_src[0] ?>" title="<?php echo get_the_title( $thumbnail_id ) ?>" data-rel="lightbox" class="lightbox">				
				<?php the_post_thumbnail() ?> 
				<i class="icon-zoom-in"></i>
			</a>

		<?php else : ?>

			<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'franklin' ), get_the_title() ) ?>">
				<?php the_post_thumbnail() ?> 
			</a>

		<?php endif ?>

	</div>

<?php endif ?>