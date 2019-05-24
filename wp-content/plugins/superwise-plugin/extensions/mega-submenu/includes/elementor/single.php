<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>
<?php endwhile; ?>
<?php wp_footer(); ?>
</body>
</html>