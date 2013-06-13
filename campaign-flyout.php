<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign()  ?>	
<!-- Active campaign -->
<section class="active-campaign flyout">

	<a class="campaign-button toggle-button"><?php printf( _x( '%s funded', 'x percent funded', 'projection' ), $campaign->percent_completed(true) ) ?></a>

	<div id="barometer"><span><?php printf( _x( "%s funded", 'x percent funded', 'projection' ), $campaign->percent_completed(true) ) ?></span></div>
	<ul class="campaign-summary">
		<li class="campaign-goal">
			<span><?php _e( 'Goal:', 'projection' ) ?></span>
			<?php echo $campaign->goal() ?>
		</li>
		<li class="campaign-raised">
			<span><?php _e( 'Raised:', 'projection' ) ?></span>
			<?php echo $campaign->current_amount() ?>
		</li>
		<li class="campaign-end">
			<span><?php _e( 'End date', 'projection' ) ?></span>
			<?php echo mysql2date( 'j F, Y', $campaign->__get( 'campaign_end_date' ) ) ?>
		</li>
	</ul>

	<p><a class="button campaign-support" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'projection' ) ?></a></p>
</section>
<!-- End active campaign -->