<form action="<?php echo esc_url( get_post_type_archive_link( 'ag_course' ) ); ?>" method="get" id="searchform">
	<input type="text" value="<?php if ( superwise_is_search_courses() ) { echo get_search_query(); } ?>" name="s" placeholder="<?php esc_html_e( 'Search Courses', 'superwise' ); ?>"/>
	<input type="hidden" name="search-type" value="courses"/>
	<button type="submit" class="wh-button"><?php esc_html_e( 'Search', 'superwise' ); ?></button>
</form>
