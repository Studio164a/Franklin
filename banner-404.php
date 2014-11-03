<?php $banner_title = get_theme_mod( '404_banner_title', false ) ?>

<?php if ( $banner_title ) : ?>

	<div class="banner">
		<div class="shadow-wrapper">
			<h1 class="banner-title"><?php echo $banner_title ?></h1>
		</div>
	</div>

<?php endif ?>