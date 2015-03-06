<?php 
$permalink 	= urlencode( get_the_permalink() );
$title 		= urlencode( get_the_title() );
?>
<ul class="share horizontal rrssb-buttons">
	<li><h6><?php _e( 'Share', 'franklin' ) ?></h6></li>
	<?php if ( get_theme_mod( 'campaign_share_twitter', false ) ) : ?>
		<li class="share-twitter">
			<a href="http://twitter.com/home?status=<?php echo $title ?>%20<?php echo $permalink ?>" class="popup icon" data-icon="&#xf099;"></a>
		</li>
	<?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_facebook', false ) ) : ?>
		<li class="share-facebook">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink ?>" class="popup icon" data-icon="&#xf09a;"></a>
		</li>
	<?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_googleplus', false ) ) : ?>
		<li class="share-googleplus">
			<a href="https://plus.google.com/share?url=<?php echo $title . $permalink ?>" class="popup icon" data-icon="&#xf0d5;"></a>
		</li>
	<?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_linkedin', false ) ) : ?>
		<li class="share-linkedin">
			<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $permalink ?>&amp;title=<?php echo $title ?>" class="popup icon" data-icon="&#xf0e1;"></a>
		</li>
	<?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_pinterest', false ) ) : ?>
		<li class="share-pinterest">
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo $permalink ?>&amp;description=<?php echo $title ?>" class="popup icon" data-icon="&#xf0d2;"></a>
		</li>
	<?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_widget', false ) ) : ?>
		<li class="share-widget">
			<a href="" class="icon" data-icon="&#xf121;" data-reveal-id="campaign-widget-sharing"></a>
		</li>
	<?php endif ?>
</ul>