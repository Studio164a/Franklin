<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php global $campaign ?>

<?php if ( $campaign === false ) return ?>

<!-- Active campaign -->
<section class="active-campaign current-campaign cf">

	<div class="shadow-wrapper">

		<div class="campaign-excerpt">
			<?php the_excerpt() ?>			
		</div>				

		<?php if ( has_post_thumbnail( $campaign->ID ) ) : ?>
			<div class="campaign-image">
				<?php echo get_the_post_thumbnail( $campaign->ID ) ?>
			</div>
		<?php endif ?>

		<div class="campaign-summary cf">		

			<p class="campaign-support"><a class="button button-large" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'franklin' ) ?></a></p>

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

			<div class="campaign-countdown">
				<span class="countdown" data-enddate='<?php echo sofa_crowdfunding_get_enddate($campaign, true) ?>'></span>
				<span><?php _e( 'Time left to donate', 'franklin' ) ?></span>
			</div>
			
		</div>				

		<?php get_template_part( 'sharing' ) ?>

	</div>	

</section>
<!-- End active campaign -->