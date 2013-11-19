<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $post_id = get_the_ID() ?>
<?php $campaigns = get_sofa_crowdfunding()->get_featured_campaign( $post_id ) ?>
<?php $featured_page = get_post_meta( $post_id, '_franklin_featured_campaigns_page', true ) ?>

<?php if ( $campaigns->have_posts() ) : ?>

	<!-- Featured campaigns -->
	<section class="active-campaign featured-campaigns feature-block cf">

		<div class="shadow-wrapper">

			<?php if ( $featured_page ) : ?>
				<a href="<?php echo get_permalink($featured_page) ?>" class="more-link alignright"><?php echo get_post_meta( $post_id, '_franklin_featured_campaigns_page_text', true ) ?></a>
			<?php endif ?>			
			<h6 class="block-title with-icon" data-icon="&#xf005;"><?php echo _n( 'Featured Project', 'Featured Projects', $campaigns->found_posts, 'franklin' ) ?></h6>

			<?php while ( $campaigns->have_posts() ) : ?>

				<?php $campaigns->the_post() ?>

				<?php $campaign = new ATCF_Campaign( get_the_ID() ) ?>

				<div class="featured-campaign">					

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="campaign-image">
							<?php echo get_the_post_thumbnail() ?>
						</div>
					<?php endif ?>

					<div class="campaign-summary cf">		

						<div class="title-wrapper">
							<h3><a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'franklin' ), get_the_title() ) ?>"><?php the_title() ?></a></h3>
						</div>

						<?php the_excerpt() ?>

						<p class="campaign-support center"><a class="button button-alt" data-reveal-id="campaign-form-<?php the_ID() ?>" href="#"><?php _e( 'Support', 'franklin' ) ?></a></p>

						<ul class="campaign-status horizontal center">
							<li class="campaign-funded">
								<span><?php echo $campaign->percent_completed() ?></span>
								<?php _e( 'Funded', 'franklin' ) ?>		
							</li>
							<li class="campaign-raised">
								<span><?php echo $campaign->current_amount() ?></span>
								<?php _e( 'Pledged', 'franklin' ) ?>		
							</li>
							<li class="campaign-goal if-tiny-hide">
								<span><?php echo $campaign->goal() ?></span>
								<?php _e( 'Goal', 'franklin' ) ?>				
							</li>
							<li class="campaign-backers">
								<span><?php echo $campaign->backers_count() ?></span>
								<?php _e( 'Backers', 'franklin' ) ?>
							</li>		
							<li class="campaign-time-left">
								<span><?php echo $campaign->days_remaining() ?></span>
								<?php _e( 'Days to go', 'franklin' ) ?>
							</li>		
						</ul>

					</div>	

					<?php get_template_part( 'sharing' ) ?>	

				</div>					

			<?php endwhile ?>

		</div>	

	</section>
	<!-- End featured campaigns -->

	<?php while ( $campaigns->have_posts() ) : ?>

		<?php $campaigns->the_post() ?>
		<!-- Support modal -->
		<div id="campaign-form-<?php the_ID() ?>" class="campaign-form reveal-modal content-block block">
	        <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
	        <?php echo edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ); ?>
	    </div>
	    <!-- End support modal -->	

	<?php endwhile ?>

<?php endif ?>

<?php wp_reset_postdata() ?>