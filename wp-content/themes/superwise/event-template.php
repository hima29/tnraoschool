<?php
get_header();
?>
<?php get_template_part( 'templates/title' ); ?>
<div class="<?php echo superwise_class( 'main-wrapper' ) ?>">
	<div class="<?php echo superwise_class( 'container' ) ?>">
		<div class="<?php echo superwise_class( 'content' ) ?>">
			<?php
				the_post_thumbnail("event-post-thumb", array("alt" => get_the_title(), "title" => ""));
			?>
			<h2 class="event-title"><?php the_title();?></h2>
			<?php
			$subtitle = get_post_meta(get_the_ID(), "timetable_subtitle", true);
			if($subtitle!=""):
			?>
				<h5><?php echo esc_html( $subtitle ); ?></h5>
			<?php
			endif;
			if(have_posts()) : while (have_posts()) : the_post();
				echo tt_remove_wpautop(get_the_content());
			endwhile; endif;
			?>
		</div>
		<div class="<?php echo superwise_class( 'sidebar' ) ?>">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
