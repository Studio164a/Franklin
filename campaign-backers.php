<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign === false ) return ?>

<?php if ( $campaign->backers() ) : ?>

	<!-- Campaign backers -->
	<section class="campaign-backers block content-block cf">
			
		<div class="title-wrapper">	
			<h2 class="block-title with-icon" data-icon="&#xf0c0;"><?php _e('Backers', 'projection') ?></h2>
		</div>

		<?php echo projection_campaign_backers( $campaign ) ?>

	</section>
	<!-- End campaign backers -->

<?php endif ?>