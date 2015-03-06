<?php global $campaign_id;

if ( ! isset( $campaign_id ) ) {
	global $campaign;

	if ( isset( $campaign ) && is_a( $campaign, 'ATCF_Campaign' ) ) {
		$campaign_id = $campaign->ID;
	} 

	$campaign_id = get_the_ID();
}

$page_has_modal = wp_cache_get( sprintf( 'franklin_page_has_campaign_%d_modal', $campaign_id ) );

if ( false === $page_has_modal ) :
	?>
	<!-- Support modal -->
	<div id="campaign-form-<?php echo $campaign_id ?>" class="campaign-form reveal-modal content-block block" data-reveal>
	    <a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
	    <?php echo edd_get_purchase_link( array( 'download_id' => $campaign_id ) ); ?>
	</div>
	<!-- End support modal -->
	<?php

	wp_cache_set( sprintf( 'franklin_page_has_campaign_%d_modal', $campaign_id ), true );

endif;