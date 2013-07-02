<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = new ATCF_Campaign( get_the_ID() ) ?>

<?php if ( $campaign === false ) return ?>

	<div class="campaign block entry-block cf">

		<?php get_template_part( 'featured_image' ) ?>

		<div class="title-wrapper"><h3 class="block-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Link to %s', 'franklin'), get_the_title() ) ?>"><?php the_title() ?></a></h3></div>

		<div class="entry">
			<?php the_excerpt() ?>
		</div>

		<ul class="campaign-status horizontal center">
			<li class="barometer barometer-small" data-progress="<?php echo $campaign->percent_completed(false) ?>" data-width="42" data-height="42" data-strokewidth="8" data-stroke="<?php echo get_theme_mod('secondary_border', '#dbd5d1') ?>" data-progress-stroke="<?php echo get_theme_mod('accent_colour', '#d95b43') ?>">
			</li>
			<!-- <li class="barometer barometer-small">hello</li> -->
			<li class="campaign-raised">
				<span><?php echo $campaign->percent_completed(false) ?><sup>%</sup></span>
				<?php _e( 'Funded', 'franklin' ) ?>		
			</li>
			<li class="campaign-pledged">
				<span><?php echo $campaign->current_amount() ?></span>
				<?php _e( 'Pledged', 'franklin' ) ?>				
			</li>
			<li class="campaign-time-left">
				<span><?php echo $campaign->days_remaining() ?></span>
				<?php _e( 'Days to go', 'franklin' ) ?>
			</li>				
		</ul>

		<?php get_template_part( 'meta', 'campaign' ) ?>

	</div>