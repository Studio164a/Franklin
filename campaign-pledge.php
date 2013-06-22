<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign === false ) return ?>

<?php echo projection_pledge_levels( $campaign->ID ) ?>