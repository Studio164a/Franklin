<?php 

if ( sofa_using_crowdfunding() === false ) return;

global $post;
$campaign = sofa_crowdfunding_get_campaign();
$post = $campaign;
?>

<div class="block content-block">
		
	<?php the_content() ?>

</div>
