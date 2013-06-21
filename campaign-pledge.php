<?php if ( sofa_using_crowdfunding() === false ) return ?>

<?php $campaign = sofa_crowdfunding_get_campaign() ?>

<?php if ( $campaign === false ) return ?>

<?php $prices = edd_get_variable_prices( $campaign->ID ) ?>

		<?php if ( count( $prices )) : ?>

		<div class="accordion campaign-pledge-levels">

			<?php foreach ( $prices as $i => $price ) : ?>

				<?php $remaining = $price['limit'] - count($price['bought']) + 1 ?>

				<h3 class="pledge-title" data-icon="&#xf0d7;"><?php printf( _x( 'Pledge %s', 'pledge amount', 'projection' ), '<strong>'.edd_currency_filter( edd_format_amount( $price['amount'] ) ).'</strong>' ) ?></h3>
				<div class="pledge-level cf<?php if ($remaining == 0) echo ' not-available' ?>">										
					<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'projection' ), $remaining, $price['limit'] ) ?></span>
					<p class="pledge-description"><?php echo $price['name'] ?></p>

					<?php if ($remaining > 0) : ?>
						<a class="pledge-button button button-alt button-small accent" data-reveal-id="campaign-form" data-price="<?php echo $price['amount'] ?>" href="#"><?php _e( 'Pledge', 'projection' ) ?></a>
					<?php endif ?>
				</div>

			<?php endforeach ?>

		</div>

		<?php endif ?>