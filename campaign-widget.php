<?php get_header('widget') ?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post() ?>
		<div class="widget-wrapper" style="width: 275px;">

			<?php get_template_part( 'campaign' ) ?>
		
		</div>
	<?php endwhile ?>
<?php endif ?>

<?php get_footer('widget') ?>