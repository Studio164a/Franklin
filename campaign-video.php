<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign->video() ) : ?>

	<?php global $wp_embed ?>

	<!-- Campaign video -->
	<section class="campaign-video content-wrapper">
		<?php echo $wp_embed->run_shortcode('[embed]'.$campaign->video().'[/embed]' ) ?>
	</section>
	<!-- End campaign video -->

<?php endif ?>