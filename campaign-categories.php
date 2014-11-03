<nav class="categories campaign-categories">

	<h2 class="block-title"><?php _e( 'Browse by Category', 'franklin' ) ?></h2>

	<ul class="horizontal">

	<?php foreach ( get_categories( array( 'taxonomy' => 'download_category', 'orderby' => 'name', 'order' => 'ASC' ) ) as $category ) : ?>				

		<li><a href="<?php echo esc_url( get_term_link($category) ) ?>"><?php echo $category->name ?></a></li>

	<?php endforeach ?>

	</ul>

</nav>