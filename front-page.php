<?php 
/**
 * Front page template. This is where it's at.
 */

get_header(); ?>

	<?php get_template_part('campaign', 'blurb') ?>

	<?php dynamic_sidebar( 'homepage-1' ) ?>

	<?php dynamic_sidebar( 'homepage-2' ) ?>

<?php get_footer() ?> 