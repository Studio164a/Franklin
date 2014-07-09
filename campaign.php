<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = new ATCF_Campaign( get_the_ID() ) ?>

<?php if ( $campaign === false ) return ?>

<?php if ( ( $cached_html = get_transient("campaign-".$campaign->ID ) ) !== false )
{
	echo "\n\n\ncached\n\n\n";
	echo $cached_html;
}
else
{
	ob_start();
	?>
	<div class="campaign block entry-block cf">

		<?php if ( has_post_thumbnail( $campaign->ID ) ) : ?>
			<div class="campaign-image">

				<?php 
				// Campaign ended, fully funded
				if ( $campaign->is_funded() ) : ?>

					<span class="campaign-successful"><?php _e( 'Successful', 'franklin' ) ?></span>

				<?php 
				// Campaign ended, not funded
				elseif ( ! $campaign->is_active() && ! $campaign->is_funded() ) : ?>

					<span class="campaign-unsuccessful"><?php _e( 'Unsuccessful', 'franklin' ) ?></span>

				<?php endif ?>

				<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'franklin' ), get_the_title() ) ?>">
					<?php the_post_thumbnail() ?> 
				</a>

			</div>			
		<?php endif ?>
		
		<div class="title-wrapper"><h3 class="block-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Link to %s', 'franklin'), get_the_title() ) ?>"><?php the_title() ?></a></h3></div>

		<div class="entry">
			<?php the_excerpt() ?>
		</div>

		<ul class="campaign-status horizontal center">
			<li class="barometer barometer-small" data-progress="<?php echo $campaign->percent_completed(false) ?>" data-width="42" data-height="42" data-strokewidth="8" data-stroke="<?php echo get_theme_mod('secondary_border', '#dbd5d1') ?>" data-progress-stroke="<?php echo get_theme_mod('accent_colour', '#d95b43') ?>">
			</li>
			
			<li class="campaign-raised">
				<span><?php echo $campaign->percent_completed(false) ?><sup>%</sup></span>
				<?php _e( 'Funded', 'franklin' ) ?>		
			</li>
			<li class="campaign-pledged">
				<span><?php echo $campaign->current_amount() ?></span>
				<?php _e( 'Pledged', 'franklin' ) ?>				
			</li>
			<li class="campaign-time-left">
				<?php echo sofa_crowdfunding_get_time_left( $campaign ) ?>				
			</li>				
		</ul>

		<?php get_template_part( 'meta', 'campaign' ) ?>

	</div>

<?php
	//end buffering, save & print
	set_transient("campaign-".$campaign->ID,ob_get_contents());
	ob_end_flush();
}