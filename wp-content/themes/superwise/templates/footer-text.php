<?php $footer_text = superwise_get_option( 'footer-text', '' ); ?>
<?php if ( $footer_text ): ?>
	<div class="<?php echo superwise_class( 'footer-text' ); ?>">
		<?php echo do_shortcode( $footer_text ); ?>
	</div>
<?php endif; ?>
