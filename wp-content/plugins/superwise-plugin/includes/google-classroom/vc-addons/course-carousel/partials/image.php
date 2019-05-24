<?php
$img_url = '';
$class_rounded = '';

if ( (int) $image_is_rounded ) {
    $class_rounded = 'rounded';
}
?>
<?php if ( has_post_thumbnail( $post_id ) ) : ?>
    
    <div class="img-container <?php echo esc_attr( $class_rounded ); ?>">
        <a href="<?php echo get_permalink( $post_id ) ?>" title="<?php echo esc_attr( get_post_field( 'post_title', $post_id ) ); ?>"><?php echo get_the_post_thumbnail( $post_id, $image_size, array( 'class' => 'featured-course-thumb') ); ?></a>
    </div>
<?php endif; ?>
