<ul class="share horizontal">
	<li><h6><?php _e( 'Share', 'franklin' ) ?></h6></li>
	<?php if ( get_theme_mod( 'campaign_share_twitter', false ) ) : ?><li class="share-twitter icon" data-icon="&#xf099;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_facebook', false ) ) : ?><li class="share-facebook icon" data-icon="&#xf09a;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_googleplus', false ) ) : ?><li class="share-googleplus icon" data-icon="&#xf0d5;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_linkedin', false ) ) : ?><li class="share-linkedin icon" data-icon="&#xf0e1;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_pinterest', false ) ) : ?><li class="share-pinterest icon" data-icon="&#xf0d2;"></li><?php endif ?>
	<?php if ( get_theme_mod( 'campaign_share_widget', false ) ) : ?><li class="share-widget icon" data-icon="&#xf121;" data-reveal-id="campaign-widget-sharing"></li><?php endif ?>
</ul>