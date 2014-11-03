<?php global $campaigns ?>

<div class="content campaigns-grid masonry-grid">							

<?php if ( $campaigns->have_posts() ) : ?>

	<?php while ( $campaigns->have_posts() ) : ?>

		<?php $campaigns->the_post() ?>

		<?php get_template_part( 'campaign' ) ?>					

	<?php endwhile ?>													

<?php endif ?>						

</div>				

<?php wp_reset_postdata() ?>