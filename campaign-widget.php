<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes() ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( "&raquo;", true ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/media/js/raphael-min.js"></script> 
    <?php wp_head() ?>
    <style>
body {
	background: transparent !important;
}
    </style>
</head>
<body>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post() ?>
			<div class="widget-wrapper" style="width: 260px;">
				<?php get_template_part('campaign') ?>
			</div>
		<?php endwhile ?>
	<?php endif ?>
	<?php wp_footer() ?>
</body>