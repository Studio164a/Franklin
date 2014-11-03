<div class="banner">
	<div class="shadow-wrapper">
		<h1 class="banner-title">			
			<?php printf( __( 'Campaigns%s', 'franklin' ), is_post_type_archive() ? '' : '<span>: ' . single_cat_title( '', false ) . '</span>' ); ?>			
		</h1>
	</div>
</div>