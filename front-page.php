<?php 
/**
 * Front page template. This is where it's at.
 */

get_header(); ?>

	<?php	
	$partials = new Sofa_Partials();
	foreach ( $partials->get_partials() as $partial ) : ?>		

		<div id="partial-<?php echo $partial->get_ID() ?>" <?php $partial->render_class() ?>>

			<?php $partial->render() ?>

		</div>		

	<?php endforeach ?>

<?php get_footer() ?> 