<?php  if ( sofa_using_crowdfunding() === false ) return ?>

<div class="campaign-content block content-block cf">
		
	<div class="title-wrapper">	
		<h2 class="block-title"><?php the_title() ?></h2>
	</div>

	<?php the_content() ?>

</div>
