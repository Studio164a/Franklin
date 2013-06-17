<?php 
if ( sofa_using_crowdfunding() === false ) return;

$campaign = sofa_crowdfunding_get_campaign();
?>

<div class="block content-block">
		
	<div class="title-wrapper">	
		<h2 class="block-title"><?php the_title() ?></h2>
	</div>

	<div class="entry">
		<?php the_content() ?>
	</div>

</div>
