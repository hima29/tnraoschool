<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<?php if ( ! superwise_page_title_enabled() ) : ?>
			<?php the_title( '<h1>', '</h1>' ); ?>
		<?php endif; ?>

		<div class="thumbnail">
			<?php superwise_get_thumbnail( array( 'thumbnail' => 'superwise-featured-image' ) ); ?>
		</div>
		<?php if ( ! superwise_page_title_enabled() ) : ?>
			<?php if ( is_single() ) : ?>
				<?php get_template_part( 'templates/entry-meta' ); ?>
			<?php endif; ?>
		<?php endif; ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php wp_link_pages( array(
			'before' => '<nav class="page-nav"><p>' . esc_html__( 'Pages:', 'superwise' ),
			'after'  => '</p></nav>'
		) ); ?>
		<div class="prev-next-item">
			<div class="left-cell">
				<p class="label"><?php esc_html_e( 'Previous', 'superwise' ) ?></p>
				<!-- <?php //previous_post_link( '<i class="icon-long-arrow-left"></i> %link ', '%title', false ); ?> -->
				<?php
                $prevPost = get_previous_post();
                $prevthumbnail = get_the_post_thumbnail($prevPost->ID); ?>
                <?php previous_post_link('%link', $prevthumbnail); ?>
                <?php previous_post_link( '<i class="icon-long-arrow-left"></i> %link ', '%title', false ); ?>
			</div>
			<div class="right-cell">
				<p class="label"><?php esc_html_e( 'Next', 'superwise' ) ?></p>
				<!-- <?php //next_post_link( '%link <i class="icon-long-arrow-right"></i> ', '%title', false ); ?> -->
				<?php
                $nextPost = get_next_post();
                $nextthumbnail = get_the_post_thumbnail($nextPost->ID); ?>
                <?php next_post_link('%link', $nextthumbnail); ?>
                <?php next_post_link( '<i class="icon-long-arrow-right"></i> %link', '%title', false ); ?>
			</div>
			<div class="clearfix"></div>
		</div>

		<?php if ( superwise_get_option( 'archive-single-use-share-this', false ) ): ?>
			<?php superwise_social_share(); ?>
		<?php endif; ?>

		<?php $author_meta = get_the_author_meta( 'description' ); ?>
		<?php if ($author_meta) : ?>
		<div class="author-info">
			<div class="author-avatar">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'superwise_author_bio_avatar_size', 90 ) ); ?>
				</a>
			</div>
			<div class="author-description">
				<div class="author-tag"><?php echo esc_html__( 'Author', 'superwise' ); ?></div>
				<h2 class="author-title"><?php echo get_the_author(); ?></h2>
				<p class="author-bio">
					<?php the_author_meta( 'description' ); ?>
				</p>
			</div>
		</div>
		<?php endif; ?>

		<?php comments_template( '/templates/comments.php' ); ?>
	</div>
<?php endwhile; ?>
