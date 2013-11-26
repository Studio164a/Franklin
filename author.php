<?php 
/**
 * Author template
 */

$author = sofa_get_current_author();
$campaigns = sofa_crowdfunding_get_campaigns_by_user($author->ID);

get_header() ?>	

	<?php get_template_part( 'banner', 'author' ) ?>

	<div class="content-wrapper fullwidth">

		<div class="author-description cf">

			<?php $avatar = get_avatar( $author->ID, 140 ) ?>					
			<?php if ( strlen( $avatar ) ) : ?>
				<div class="author-avatar">
					<?php echo $avatar ?>
				</div>
			<?php endif ?>

			<div class="author-stats">
				<h6><?php _e( 'Details', 'franklin' ) ?></h6>
				<p><?php /*printf( __( "Joined %s / Backed %d / Created %d", 'franklin' ), 
						date('F Y', strtotime($author->user_registered) ), 
						edd_count_purchases_of_customer($author->ID), 
						$campaigns->post_count ) */?>
					<?php printf( __( 'Joined %s', 'franklin' ), date('F Y', strtotime($author->user_registered) ) ) ?><br />
					<?php printf( __( 'Backed %d', 'franklin' ), edd_count_purchases_of_customer($author->ID) ) ?><br />
					<?php printf( __( 'Created %d', 'franklin' ), $campaigns->post_count ) ?>
				</p>
			</div>

			<div class="author-bio">				
				<h6><?php _e( 'Bio', 'franklin' ) ?></h6>				
				<p><?php echo $author->description ?></p>
			</div>

		</div>

		<?php get_template_part('author', 'links') ?>

		<?php if ( $campaigns->post_count ) : ?>

			<section class="author-campaigns-block block">

				<h2 class="section-title center"><?php printf( __( 'Campaigns created by %s', 'franklin' ), $author->nickname ) ?></h2>

				<?php get_template_part('campaign', 'grid-loop') ?>

			</section>

		<?php endif ?>

		<?php if ( have_posts() ) : ?>

			<section class="author-posts-block block">

				<h2 class="section-title center"><?php printf( __( 'Posts by %s', 'franklin' ), $author->nickname ) ?></h2>

				<ul class="masonry-grid">
				<?php while ( have_posts() ) : ?>

					<?php the_post() ?>
					<li <?php post_class('column-half') ?>>
						<?php get_template_part('featured_image') ?>
						<div class="title-wrapper">
							<h3 class="post-title">
								<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'franklin' ), get_the_title() ) ?>"><?php the_title() ?></a>
							</h3>
						</div>
						<div class="entry">
							<?php the_excerpt() ?>
						</div>

						<?php get_template_part('meta', 'below') ?>
					</li>
					
				<?php endwhile ?>
				</ul>

			</section>

		<?php endif ?>

	</div>

<?php get_footer () ?>