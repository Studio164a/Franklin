<?php 
/**
 * Author template
 */

$author = sofa_get_current_author();

get_header() ?>	

	<?php get_template_part( 'banner' ) ?>

	<div class="content-wrapper">

		<div class="content">	

			<h1 class="archive-title">		
				<?php printf( __( "Posts by %s", 'projection' ), $author->nickname ) ?>
			</h1>

			<?php if ( $author->__isset('description') ) : ?>
				<div class="widget author-description">
					
					<?php $avatar = get_avatar( $author->ID, 70 ) ?>
					<?php if (strlen( $avatar ) ) : ?>
						<?php echo $avatar ?>
					<?php endif ?>

					<p><?php echo $author->__get('description') ?></p>
				</div>
			<?php endif ?>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : ?>

					<?php the_post() ?>
					
					<?php get_template_part( 'content', get_post_format() ) ?>

				<?php endwhile ?>

				<?php sofa_content_nav( 'nav_below' ) ?>

			<?php endif ?>

		</div>

		<?php get_sidebar() ?>

	</div>

<?php get_footer () ?>