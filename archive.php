<?php
	get_header();

		if ( have_posts() ) { the_post();
			get_template_part( 'items/item', get_post_type() );
		} else {
			get_template_part( 'items/item', 'none' );
		}

		if ( show_posts_nav() ) {
			get_template_part( 'blocks/block', 'pagination' );
		}

	get_footer();
?>
