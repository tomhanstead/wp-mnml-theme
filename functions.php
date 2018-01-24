<?php

if ( ! function_exists( 'mnml_setup' ) ) {
	function mnml_setup() {

		// Theme supports: Feed links in head, post formats (aside/image/gallery), post thumbnails...
		add_theme_support( 'automatic-feed-links' );
		// add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery' ) );
		add_theme_support( 'post-thumbnails' );

		// Add Image Sizes dimensions and so on (hero background, article image, etc)
		// See CSS-Tricks

		// This theme uses wp_nav_menu() in the header and the footer.
		register_nav_menus( array(
			'header'		=> __( 'Header Navigation', 'wp-mnml' ),
			'footer'		=> __( 'Footer Navigation', 'wp-mnml' )
		) );

		// Remove the crap from the wp_head() function
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

		// Remove any unwanted wordpress dashboard boxes
		function disable_default_dashboard_widgets() {
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
			remove_meta_box('dashboard_plugins', 'dashboard', 'core');
			remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
		}

		add_action('admin_menu', 'disable_default_dashboard_widgets');
	}

} // mnml_setup

// Tell WordPress to run setup function when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'mnml_setup' );

function mnml_inline_styles() {
	$css_file = get_template_directory_uri() . '/css/core.css?asd';
	$css = file_get_contents($css_file);

	echo "<style>{$css}</style>";
}
add_action( 'wp_head', 'mnml_inline_styles', 40 );


function mnml_enqueue_styles() {
	// Better jQuery inclusion
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
	}
}
add_action( 'wp_enqueue_scripts', 'mnml_enqueue_styles' );

// Add ACF Options Page
function mnml_acf_options_page() {
	if ( function_exists( 'acf_add_options_page') ) {
		acf_add_options_page( array (
			'title' => __( 'Mnml Options', 'wp-mnml' )
		) );
	}
}
add_action( 'init', 'mnml_acf_options_page');

// Remove Emoji from TinyMCE
function mnml_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// Disable Emoji support
function mnml_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'mnml_disable_emojis_tinymce' );
}
add_action( 'init', 'mnml_disable_emojis' );
