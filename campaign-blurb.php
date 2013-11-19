<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php global $campaign ?>

<?php if ( $campaign === false ) return ?>

<!-- Active campaign -->
<section class="active-campaign current-campaign feature-block cf <?php if ( !$campaign->is_active() ) : ?>ended<?php endif ?>">

	<div class="shadow-wrapper">

		<div class="campaign-excerpt">
			<?php the_excerpt() ?>			
		</div>				

		<?php if ( has_post_thumbnail( $campaign->ID ) ) : ?>
			<div class="campaign-image">

				<?php 
				// Campaign ended, fully funded
				if ( $campaign->is_funded() ) : ?>

					<span class="campaign-successful"><?php _e( 'Successful' ) ?></span>

				<?php 
				// Campaign ended, not funded
				elseif ( ! $campaign->is_active() && ! $campaign->is_funded() ) : ?>

					<span class="campaign-unsuccessful"><?php _e( 'Unsuccessful' ) ?></span>

				<?php endif ?>

				<?php echo get_the_post_thumbnail( $campaign->ID ) ?>
			</div>
		<?php endif ?>

		<div class="campaign-summary cf">		

			<?php if ( $campaign->is_active() ) : ?>

				<p class="campaign-support"><a class="button button-large" data-reveal-id="campaign-form-<?php echo $campaign->ID ?>" href="#"><?php _e( 'Support', 'franklin' ) ?></a></p>

			<?php else : ?>

				<?php if ( $campaign->is_funded() ) : ?>
					<h3 class="campaign-ended"><?php printf( __( 'This campaign successfully reached its funding goal and ended %s', 'franklin' ), '<span class="time-ago">'.sofa_crowdfunding_get_time_since_ended($campaign, true).'</span>' ) ?></h3>
				<?php else : ?>
					<h3 class="campaign-ended"><?php printf( __( 'This campaign failed to reach its funding goal %s', 'franklin' ), '<span class="time-ago">'.sofa_crowdfunding_get_time_since_ended($campaign, true).'</span>' ) ?></h3>
				<?php endif ?>

			<?php endif ?>

			<div class="barometer" data-progress="<?php echo $campaign->percent_completed(false) ?>" data-width="148" data-height="148" data-strokewidth="11" data-stroke="<?php echo get_theme_mod('accent_text', '#fff') ?>" data-progress-stroke="<?php echo get_theme_mod('body_text', '#7D6E63') ?>">
				<span><?php printf( _x( "%s Funded", 'x percent funded', 'franklin' ), '<span>'.$campaign->percent_completed(false).'<sup>%</sup></span>' ) ?></span>
			</div>		

			<ul>
				<li class="campaign-raised">
					<span><?php echo $campaign->current_amount() ?></span>
					<?php _e( 'Pledged', 'franklin' ) ?>		
				</li>
				<li class="campaign-goal">
					<span><?php echo $campaign->goal() ?></span>
					<?php _e( 'Goal', 'franklin' ) ?>				
				</li>
				<li class="campaign-backers">
					<span><?php echo $campaign->backers_count() ?></span>
					<?php _e( 'Backers', 'franklin' ) ?>
				</li>				
			</ul>

			<?php if ( sofa_crowdfunding_show_countdown($campaign) ) : ?>
				<div class="campaign-countdown">
					<span class="countdown" data-enddate='<?php echo sofa_crowdfunding_get_enddate($campaign, true) ?>'></span>
					<span><?php _e( 'Time left to donate', 'franklin' ) ?></span>
				</div>
			<?php endif ?>
			
		</div>				

		<?php get_template_part( 'sharing' ) ?>

	</div>	

</section>
<!-- End active campaign -->