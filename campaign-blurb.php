<?php $campaign = projection_get_campaign() ?>	
<!-- Active campaign -->
<section class="active-campaign cf">	
	<?php if ( has_post_thumbnail( $campaign->ID ) ) : ?>
		<?php echo get_the_post_thumbnail( $campaign->ID ) ?>
	<?php endif ?>

	<div class="campaign-summary cf">

		<div class="campaign-excerpt"><?php echo apply_filters( 'the_content', $campaign->data->post_excerpt ) ?></div>

		<div id="barometer"><span><?php printf( _x( "%s funded", 'x percent funded', 'projection' ), $campaign->percent_completed(true) ) ?></span></div>
		<ul>
			<li class="campaign-goal">
				<span><?php _e( 'Goal:', 'projection' ) ?></span>
				<?php echo $campaign->goal() ?>
			</li>
			<li class="campaign-backers">
				<span><?php _e( 'Backers:', 'projection' ) ?></span>
				<?php echo $campaign->backers_count() ?>
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

	</div>

	<p class="campaign-support"><a class="button" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'projection' ) ?></a></p>	

</section>
<!-- End active campaign -->