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
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />
    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ) ?>
    <?php wp_head() ?> 	
</head>
<body <?php body_class() ?>>

	<!-- Header -->
	<header id="header" class="cf wrapper">

		<!-- Site title -->
		<?php sofa_site_title() ?>	
		<!-- End site title -->		

		<!-- Wrapper for the navigation -->
		<div id="site-navigation" class="wrapper">		
			
			<nav role="navigation">
	            <a class="menu-button toggle-button"><i class="icon-th-list"></i></a>
	            <?php wp_nav_menu( array(   
	                'theme_location' => 'primary_navigation',
	                'container' => false,
	                'menu_class' => 'menu responsive_menu' ) ) ?>
	        </nav>
	    </div>
	    <!-- End navigation -->

	</header>
	<!-- End header -->

	<?php if ( !is_front_page() ) : ?>

		<?php get_template_part('campaign', 'flyout') ?>

	<?php endif ?>	

	<!-- Main content section. Everything between the header and the footer -->
	<div id="main" class="cf" role="main"> 	

