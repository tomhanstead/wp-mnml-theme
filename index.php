<?php
	get_header();

		if ( have_posts() ) {
			while ( have_posts() ) { the_post();
				get_template_part('contents/content', get_post_type());
			}
		} else {
			get_template_part('contents/content', 'none');
		}

	get_footer();
?>
