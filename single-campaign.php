<?php 
/**
 * Front page template. This is where it's at.
 */

get_header() ?>

	<?php if ( have_posts() ) : ?>

		<?php while( have_posts() ) : ?>

			<?php the_post() ?>

			<?php $campaign = new ATCF_Campaign( get_the_ID() ) ?>

			<?php get_template_part('campaign', 'blurb') ?>			

			<div class="content-wrapper">

				<?php echo projection_campaign_video( $campaign ) ?>
				
				<div class="content">
	
					<!-- Campaign content -->					
					<?php get_template_part('content', 'campaign') ?>
					<!-- End campaign content -->

					<!-- "Campaign Below Content" sidebar -->
					<?php dynamic_sidebar('campaign_after_content') ?>
					<!-- End "Campaign Below Content" sidebar -->

					<?php comments_template('/comments-campaign.php', true) ?>

				</div>

				<?php get_sidebar( 'campaign' ) ?>
			
			</div>

		<?php endwhile ?>

	<?php endif ?>

<?php get_footer() ?>