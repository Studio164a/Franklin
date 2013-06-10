<?php 
/**
 * 404 page template
 */

get_header() ?>

	<div id="main_wrap" class="inner_wrap cf fullwidth">	

		<?php get_template_part('breadcrumbs') ?>

		<div class="page_wrap">
			
			<div id="content">	

				<h1><?php _e( 'Sorry, but you\'ve hit a dead end.', 'osfa' ) ?></h1>
				<?php get_search_form() ?>

			</div>

		</div>

	</div>

<?php get_footer () ?>