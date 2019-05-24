<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function aislin_gc_template( $template, $params = [], $echo = true ) {

	$content = Aislin_Template_Manager::get( $template, $params );

	if ($echo) {
		echo $content;
	} else {
		return $content;
	}

}

function agc_classroom_link_html( $course ) {
	if ( ! is_a( $course, 'Aislin_Classroom_Course' ) ) {
		return '';
	}
	$link = $course->get_link();

	if ( ! $link ) {
		return '';
	}
	?>
		<a class="classroom-link" 
			href="<?php echo esc_url( $link ); ?>" 
			title="<?php echo esc_attr( $course->get_title() ); ?>" 
			target="_blank">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
				<style type="text/css">
					.st0{fill:#DFDFDE;}
				</style>
				<path d="M0,0c21.3,0,42.7,0,64,0c0,21.3,0,42.7,0,64c-21.3,0-42.7,0-64,0C0,42.7,0,21.3,0,0z M58.4,58.5
					c0-17.8,0-35.4,0-52.9c-17.8,0-35.4,0-52.9,0c0,17.8,0,35.3,0,52.9c10.6,0,21.1,0,31.7,0c0.1-1.3,0.2-2.5,0.4-3.6
					c5.4,0,10.6,0,15.7,0c0.1,1.3,0.2,2.4,0.3,3.6C55.2,58.5,56.7,58.5,58.4,58.5z"/>
				<circle class="st0" cx="19.5" cy="29.6" r="2.9"/>
				<path class="st0" d="M19.5,33.3c-5.8-0.2-7.3,3.6-7.3,3.6v3h7.2h0.2h7.2v-3C26.8,36.9,25.3,33.1,19.5,33.3z"/>
				<circle class="st0" cx="44.7" cy="29.6" r="2.9"/>
				<path class="st0" d="M44.7,33.3c-5.8-0.2-7.3,3.6-7.3,3.6v3h7.2h0.2H52v-3C52,36.9,50.5,33.1,44.7,33.3z"/>
				<circle cx="32" cy="25.3" r="4.1"/>
				<path d="M42.3,39.9v-4.2c0-0.1,0-0.2-0.1-0.2c-0.1-0.2-2.9-5.3-10.3-5.1l0,0c-8,0-10.2,4.9-10.3,5.1
					c0,0.1,0,0.1,0,0.2v4.2"/>
				</svg>
			<span><?php esc_html_e( 'Go to classroom', 'superwise-plugin' ); ?></span>
		</a>

	<?php
}
