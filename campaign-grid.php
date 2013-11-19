<div class="campaigns-grid-wrapper">								

	<nav class="campaigns-navigation" role="navigation">
        <a class="menu-button toggle-button"><i class="icon-th-list"></i></a>
        <?php sofa_crowdfunding_campaign_nav() ?>				
	</nav>

	<h3 class="section-title"><?php _e( 'Latest Projects', 'franklin' ) ?></h3>

	<div class="content campaigns-grid masonry-grid">					

		<?php						
		$campaigns = new ATCF_Campaign_Query() ?>

		<?php if ( $campaigns->have_posts() ) : ?>

			<?php while ( $campaigns->have_posts() ) : ?>

				<?php $campaigns->the_post() ?>

				<?php get_template_part( 'campaign' ) ?>					

			<?php endwhile ?>													

		<?php endif ?>						

	</div>				

	<?php if ($campaigns->max_num_pages > 1) : ?>	

		<p class="center">
			<a class="button button-alt" href="<?php echo site_url( apply_filters( 'sofa_previous_campaigns_link', '/campaigns/page/2/' ) ) ?>">
				<?php echo apply_filters( 'sofa_previous_campaigns_text', __( 'Previous Campaigns', 'franklin' ) ) ?>
			</a>
		</p>

	<?php endif ?>

</div>