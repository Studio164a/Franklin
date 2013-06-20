<?php 
/**
 * 404 page template
 */

get_header() ?>

	<div class="content-wrapper">

		<div class="content">	

			<h1><?php _e( 'Sorry, but you\'ve hit a dead end.', 'projection' ) ?></h1>
			<?php get_search_form() ?>

		</div>

	</div>

<?php get_footer () ?>