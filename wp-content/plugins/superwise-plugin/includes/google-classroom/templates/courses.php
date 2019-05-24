<?php foreach ($courses as $course): ?>
	
	<h3><a href="<?php get_permalink(get_the_ID()) ?>?class=<?php echo esc_attr( $course->get_id() ); ?>"><?php echo esc_html( $course->get_title() );  ?></a></h3>
	<h4><?php echo esc_html( $course->get_subtitle() ); ?></h4>

<?php endforeach ?>