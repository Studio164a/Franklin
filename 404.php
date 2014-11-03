<?php 
/**
 * 404 page template
 */

get_header() ?>

	<?php get_template_part( 'banner', '404' ) ?>

	<div class="content-wrapper">

		<div class="content">	

			<h2><?php _e( 'Sorry, but you\'ve hit a dead end.', 'franklin' ) ?></h2>
			
			<p><a href="<?php echo site_url() ?>"><?php _e( 'Go home', 'franklin' ) ?></a></p>

		</div>

	</div>

<?php get_footer () ?>