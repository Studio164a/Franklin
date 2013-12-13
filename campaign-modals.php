	<?php global $campaign;
	$campaign = isset($campaign) ? $campaign : new ATCF_Campaign( get_the_ID() ) ?>

	<!-- Support modal -->
	<div id="campaign-form-<?php echo $campaign->ID ?>" class="campaign-form reveal-modal content-block block">
        <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
        <?php echo edd_get_purchase_link( array( 'download_id' => $campaign->ID ) ); ?>
    </div>
    <!-- End support modal -->

    <!-- Widget embed modal --> 
    <?php if ( get_theme_mod( 'campaign_share_widget', false ) ) : ?>				
		<div id="campaign-widget-sharing" class="reveal-modal block multi-block">
			<a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
			<div class="title-wrapper">
				<h2 class="block-title"><?php _e( 'Share Campaign', 'franklin' ) ?></h2>
			</div>	
			<div class="block">	
				<?php echo apply_filters( 'the_excerpt', get_theme_mod( 'campaign_sharing_text', '' ) ) ?>
				<h4><?php _e( 'Embed Code', 'franklin' ) ?></h4>
				<pre><?php echo htmlspecialchars( '<iframe src="' . get_permalink($campaign->ID) . '?widget=1" width="275px" height="480px" frameborder="0" scrolling="no" /></iframe>' ) ?></pre>
			</div>
			<div class="block iframe-block">
				<iframe src="<?php echo get_permalink($campaign->ID) ?>?widget=1" width="275px" height="480px" frameborder="0" scrolling="no" /></iframe>
			</div>
		</div>				
	<?php endif ?>
	<!-- End widget embed modal -->