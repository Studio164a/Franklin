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
		<div id="banner">
			<?php sofa_site_title() ?>
		</div>
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

	<!-- Active campaign -->
	<?php $campaign = projection_get_campaign() ?>	
	<section id="active-campaign">

		<a class="campaign-button toggle-button"><?php printf( _x( '%s funded', 'x percent funded', 'projection' ), $campaign->percent_completed(true) ) ?></a>

		<div id="barometer"><span><?php printf( _x( "%s funded", 'x percent funded', 'projection' ), $campaign->percent_completed(true) ) ?></span></div>
		<ul class="campaign-summary">
			<li class="campaign-goal">
				<span><?php _e( 'Goal:', 'projection' ) ?></span>
				<?php echo $campaign->goal() ?>
			</li>
			<li class="campaign-raised">
				<span><?php _e( 'Raised:', 'projection' ) ?></span>
				<?php echo $campaign->current_amount() ?>
			</li>
			<li class="campaign-end">
				<span><?php _e( 'End date', 'projection' ) ?></span>
				<?php echo mysql2date( 'j F, Y', $campaign->__get( 'campaign_end_date' ) ) ?>
			</li>
		</ul>

		<p><a class="button campaign-support" data-reveal-id="campaign-form" href="#"><?php _e( 'Support', 'projection' ) ?></a></p>

		<div id="campaign-form" class="reveal-modal">
			<a class="close-reveal-modal"><i class="icon-remove"></i></a>
			<?php echo edd_get_purchase_link( array( 'download_id' => $campaign->ID ) ); ?>
		</div>

	</section>

	<!-- End active campaign -->


	<!-- Main content section. Everything between the header and the footer -->
	<div id="main" class="cf" role="main"> 	

