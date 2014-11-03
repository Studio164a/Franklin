<?php 
/**
 * Single campaign template.
 */

get_header();

	if ( have_posts() ) :

		while( have_posts() ) :

			the_post();

			$campaign = atcf_get_campaign( get_the_ID() );

			do_action( 'atcf_campaign_before', $campaign );

			get_template_part('campaign', 'blurb') 
			?>			

			<div class="content-wrapper">

				<?php echo franklin_campaign_video( $campaign ) ?>
				
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

		<?php endwhile;

	endif;

	get_template_part('campaign', 'modals');

get_footer();