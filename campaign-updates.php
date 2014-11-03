<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign === false ) return ?>

<?php if ( $campaign->updates() ) : ?>

	<!-- Campaign updates -->
	<aside class="campaign-updates widget widget-open">
		<div class="title-wrapper">
			<h3 class="widget-title"><?php _e( 'Campaign Updates', 'franklin' ) ?></h3>
		</div>
		<?php echo apply_filters( 'the_excerpt', $campaign->updates() ) ?>
	</aside>
	<!-- End campaign updates -->

<?php endif ?>
