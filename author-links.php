<?php 
global $author;

$website = $author->user_url;
$twitter = $author->twitter;
$facebook = $author->facebook;

if (strlen($website . $twitter . $facebook)) :
?>

<ul class="author-links horizontal center">
	<?php if ($website) : ?>
		<li class="with-icon" data-icon="&#xf0c1;">
			<a href="<?php echo $website ?>" title="<?php printf( __("Visit %s's website", 'franklin'), $author->nickname ) ?>"><?php echo sofa_condensed_url($website) ?></a>
		</li>
	<?php endif ?>				

	<?php if ($twitter) : ?>
		<li class="with-icon" data-icon="&#xf099;">
			<a href="<?php echo $twitter ?>" title="<?php printf( __("Visit %s's Twitter profile", 'franklin'), $author->nickname ) ?>"><?php echo sofa_condensed_url($twitter) ?></a>
		</li>
	<?php endif ?>				

	<?php if ($facebook) : ?>
		<li class="with-icon" data-icon="&#xf09a;">
			<a href="<?php echo $facebook ?>" title="<?php printf( __("Visit %s's Facebook profile", 'franklin'), $author->nickname ) ?>"><?php echo sofa_condensed_url($facebook) ?></a>
		</li>
	<?php endif ?>				
</ul>

<?php endif ?>