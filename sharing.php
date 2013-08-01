<ul class="share horizontal">
	<li><h6><?php _e( 'Share', 'franklin' ) ?></h6></li>
	<?php if ( get_theme_mod( 'campaign_share_twitter', false ) ) : ?><li class="share-twitter icon" data-icon="&#xf099;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_facebook', false ) ) : ?><li class="share-facebook icon" data-icon="&#xf09a;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_googleplus', false ) ) : ?><li class="share-googleplus icon" data-icon="&#xf0d5;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_linkedin', false ) ) : ?><li class="share-linkedin icon" data-icon="&#xf0e1;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_pinterest', false ) ) : ?><li class="share-pinterest icon" data-icon="&#xf0d2;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_widget', false ) ) : ?><li class="share-widget icon" data-icon="&#xf121;" data-reveal-id="campaign-widget-sharing"></li><?php endif ?>
</ul>

<?php if ( get_theme_mod( 'campaign_share_widget', false ) ) : ?>
	<!-- Widget embed modal --> 
	<div id="campaign-widget-sharing" class="reveal-modal block multi-block">
		<a class="close-reveal-modal icon"><i class="icon-remove-sign"></i></a>
		<div class="title-wrapper">
			<h2 class="block-title"><?php _e( 'Share Campaign', 'franklin' ) ?></h2>
		</div>	
		<div class="block">	
			<?php echo apply_filters( 'the_excerpt', get_theme_mod( 'campaign_sharing_text', '' ) ) ?>
			<h4><?php _e( 'Embed Code', 'franklin' ) ?></h4>
			<pre><?php echo htmlspecialchars( '<iframe src="' . get_permalink() . '?widget=1" width="275px" height="480px" frameborder="0" scrolling="no" /></iframe>' ) ?></pre>
		</div>
		<div class="block iframe-block">
			<iframe src="<?php echo get_permalink() ?>?widget=1" width="275px" height="480px" frameborder="0" scrolling="no" /></iframe>
		</div>
	</div>
	<!-- End widget embed modal -->
<?php endif ?>