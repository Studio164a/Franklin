<?php 
/**
 * Front page template. This is where it's at.
 */

get_header(); ?>

	<?php get_template_part('campaign', 'blurb') ?>

	<?php get_template_part('campaign', 'video') ?>

	<div class="content">
		
		<?php get_template_part('campaign', 'content') ?>

		<?php get_template_part('campaign', 'backers') ?>

		<?php comments_template('', true) ?>

	</div>

	<aside class="sidebar">

		<?php get_template_part('campaign', 'updates') ?>

		<?php get_template_part('campaign', 'pledge') ?>

		<?php get_template_part('widget', 'blog') ?>

	</aside>

<?php get_footer() ?>