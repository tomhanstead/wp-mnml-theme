<?php
	get_header();

		if ( have_posts() ) {
			while ( have_posts() ) { the_post();
				get_template_part( 'items/item', 'search' );
			}
		} else {
			get_template_part( 'items/item', 'none' );
		}

		if ( show_posts_nav() ) {
			get_template('blocks/block', 'pagination');
		}

	get_footer();
?>