<?php 
/**
 * Campaign Contributions
 *
 * @since Franklin 1.5.11
 *
 * @return void
 */
function franklin_atcf_shortcode_profile_contributions( $user ) {
	global $edd_options;

	$contributions = edd_get_payments( array(
		'user'   => $user->ID,
		'status' => atcf_has_preapproval_gateway() ? array( 'preapproval', 'publish' ) : 'publish',
		'mode'   => edd_is_test_mode() ? 'test' : 'live'
	) );

	if ( empty( $contributions ) )
		return;
?>
	<h3 class="atcf-profile-section your-campaigns"><?php _e( 'Your Contributions', 'franklin' ); ?></h3>

	<ul class="atcf-profile-contributinos cf">
		<?php foreach ( $contributions as $contribution ) : ?>
		<?php
			$payment_data = edd_get_payment_meta( $contribution->ID );
			$cart         = edd_get_payment_meta_cart_details( $contribution->ID );
			$key          = edd_get_payment_key( $contribution->ID );
		?>
		<?php if ( $cart ) : ?>
		<li>
			<?php foreach ( $cart as $download ) : ?>
				<?php 
					$payment_url 	= add_query_arg( 'payment_key', $key, get_permalink( $edd_options[ 'success_page' ] ) );
					$payment_amount	= edd_currency_filter( edd_format_amount( $download[ 'price' ] ) );
				
					printf( _x('%s pledge to %s', 'price for download (payment history)', 'franklin'), 
						'<a href="' . $payment_url . '">'. $payment_amount . '</a>', 
						'<a href="' . get_permalink( $download[ 'id' ] ) . '">' . $download[ 'name' ] . '</a>' 
					);
				?>
			<?php endforeach; ?>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php
}

remove_action( 'atcf_shortcode_profile', 'atcf_shortcode_profile_contributions', 30, 1 );
add_action( 'atcf_shortcode_profile', 'franklin_atcf_shortcode_profile_contributions', 30, 1 );