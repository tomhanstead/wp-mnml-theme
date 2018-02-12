<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body <?php body_class(); ?>>

		<header class="header" role="banner">
		 	<h1 class="header-title"><?php echo the_title(); ?></h1>

			<?php
				// https://developer.wordpress.org/reference/functions/wp_nav_menu/
				$args = array(
					'container' => 'nav',
					'container_class' => 'header-menu',
					'fallback_cb' => 'header',
					'menu' => 'header',
					'theme_location' => 'header'
				);
				wp_nav_menu( $args );
			?>
		</header>

		<main <?php post_class('main'); ?> role="main">