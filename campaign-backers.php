<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php global $campaign ?>

<?php if ( $campaign->backers() ) : ?>

	<section class="campaign-backers block content-block cf">
							
		<div class="title-wrapper">	
			<h2 class="block-title with-icon" data-icon="&#xf0c0;"><?php _e('Backers', 'franklin') ?></h2>
		</div>

		<?php echo franklin_campaign_backers( $campaign ) ?>

	</section>

<?php endif ?>