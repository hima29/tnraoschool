<?php $footer_social_links = superwise_get_option( 'footer-social-links', array() ); ?>
<div class="<?php echo superwise_class( 'footer-social-links' ); ?>">
	<?php foreach ( $footer_social_links as $social_link): ?>
		<?php
			$parts = explode('|', $social_link);
			$icon  = isset( $parts[0] ) ? trim( $parts[0] ) : false;
			$link  = isset( $parts[1] ) ? trim( $parts[1] ): '';
			$style = isset( $parts[2] ) ? 'font-size:' . (int) $parts[2] . 'px' : '';
		 ?>
		 <?php if ( $icon ): ?>
			 <a target="_blank" href="<?php echo esc_url( $link );  ?>" style="<?php echo esc_attr( $style ); ?>">
		 		<i class="<?php echo esc_html( $icon ); ?>"></i>
			 </a>
		 <?php endif; ?>
	<?php endforeach; ?>
</div>
