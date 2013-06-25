<?php $banner_title = get_theme_mod( 'blog_banner_title', false ) ?>

<?php if ( $banner_title ) : ?>

	<div class="banner">
		<div class="shadow-wrapper">
			<h2 class="banner-title"><?php echo $banner_title ?></h2>
		</div>
	</div>

<?php endif ?>