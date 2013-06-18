<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign === false ) return ?>

<?php if ( $campaign->backers() ) : ?>

	<!-- Campaign backers -->
	<section class="campaign-backers block content-block cf">
			
		<div class="title-wrapper">	
			<h2 class="block-title"><?php _e('Backers', 'projection') ?></h2>
		</div>

		<ul>

		<?php foreach ($campaign->backers() as $i => $log ) : ?>

			<?php $backer = sofa_crowdfunding_get_payment($log) ?>

			<li class="campaign-backer"> 
				<?php echo sofa_crowdfunding_get_backer_avatar( $backer ) ?>
				<div class="if-tiny-hide">
					<h6><?php echo $backer->post_title ?></h6>
					<p>
						<?php if ( $campaign->needs_shipping() ) : ?>
							<?php echo sofa_crowdfunding_get_backer_location( $backer ) ?><br />
						<?php endif ?>
						<?php echo sofa_crowdfunding_get_backer_pledge( $backer ) ?>
					</p>
				</div>
			</li>
			
		<?php endforeach ?>

		</ul>

	</section>
	<!-- End campaign backers -->

<?php endif ?>