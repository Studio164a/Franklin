<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ) : ?>
	<div class="campaign-image">
		<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'franklin' ), get_the_title() ) ?>">
			<?php the_post_thumbnail() ?> 
		</a>
	</div>
<?php endif; ?>