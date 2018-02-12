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

		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

		function disable_default_dashboard_widgets() {
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
			remove_meta_box('dashboard_plugins', 'dashboard', 'core');
			remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
		}

		add_action('admin_menu', 'disable_default_dashboard_widgets');
	}

} // mnml_setup
add_action( 'after_setup_theme', 'mnml_setup' );

function mnml_inline_styles() {
	$css_file = get_template_directory() . '/assets/css/critical.css';
	$css = file_get_contents($css_file, true);
	echo "<style>{$css}</style>";
}
add_action( 'wp_head', 'mnml_inline_styles', 40 );

function mnml_enqueue_styles() {
	wp_register_style( 'mnml-critical', get_template_directory_uri() . '/assets/css/critical.css', false, '1.0.0' );
	// in order to enqueue site.cc, you need critical.css
	wp_register_style( 'mnml-styles', get_template_directory_uri() . '/assets/css/site.css', $dependencies = array('mnml-critical'), '1.0.0' );
	wp_enqueue_style( 'mnml-styles' );
}
add_action( 'wp_enqueue_scripts', 'mnml_enqueue_styles' );

function mnml_enqueue_scripts() {
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
	}	
	wp_register_script( 'mnml-scripts', get_template_directory_uri() .  '/assets/js/site.min.js', false, '1.0.0' );
	wp_enqueue_script( 'mnml-scripts' );
}
add_action( 'wp_enqueue_scripts', 'mnml_enqueue_scripts' );

function mnml_show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}

function mnml_acf_options_page() {
	if ( function_exists( 'acf_add_options_page') ) {
		acf_add_options_page( array (
			'title' => __( 'Mnml Options', 'wp-mnml' )
		) );
	}
}
add_action( 'init', 'mnml_acf_options_page');

function mnml_save_acf_json( $path ) {
	$path = get_template_directory_uri() . '/acf';
	return $path;	
}
add_filter('acf/settings/save_json', 'mnml_save_acf_json');

function mnml_load_acf_json( $paths ) {
    unset($paths[0]);
    $paths[] = get_template_directory_uri() . '/acf';
    return $paths;
}
add_filter('acf/settings/load_json', 'mnml_load_acf_json');

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
