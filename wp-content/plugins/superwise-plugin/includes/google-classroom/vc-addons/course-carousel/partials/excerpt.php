<?php if ((int) $show_excerpt): ?>
    <p class="course-excerpt"><?php echo wp_trim_words( $post_item->post_excerpt, $number_of_words ); ?></p>
<?php endif ?>
