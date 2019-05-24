<?php
/**
 * @package WordPress
 * @subpackage Wheels
 *
 * Template Name: Home (No Footer)
 */
get_header();
?>
<div class="<?php echo superwise_class( 'main-wrapper' ) ?>">
    <div class="<?php echo superwise_class( 'container_home_content' ) ?>">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
