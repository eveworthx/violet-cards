<?php

/**
 * Violet Cards functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Violet_Cards
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

if (!function_exists('violet_cards_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function violet_cards_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Violet Cards, use a find and replace
		 * to change 'violet-cards' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('violet-cards', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__('Primary', 'violet-cards'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'violet_cards_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action('after_setup_theme', 'violet_cards_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function violet_cards_content_width()
{
	$GLOBALS['content_width'] = apply_filters('violet_cards_content_width', 640);
}
add_action('after_setup_theme', 'violet_cards_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function violet_cards_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'violet-cards'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'violet-cards'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'violet_cards_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function violet_cards_scripts()
{
	wp_enqueue_style('violet-cards-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('violet-cards-style', 'rtl', 'replace');

	wp_enqueue_script('violet-cards-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'violet_cards_scripts');

/**
 * Enqueue Google fonts.
 */
function wpb_add_google_fonts()
{

	wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet" rel="stylesheet', false);
}

add_action('wp_enqueue_scripts', 'wpb_add_google_fonts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Style all Gutenberg buttons to look like Bootstrap
 */
add_filter('render_block', function ($block_content, $block) {
	// Button block additional class.
	$block_content = str_replace(
		'wp-block-button__link',
		'wp-block-button__link btn btn-primary w-100',
		$block_content
	);

	return $block_content;
}, 5, 2);

/**
 * Font Awesome CDN Setup Webfont
 * 
 * This will load Font Awesome from the Font Awesome Free or Pro CDN.
 */
if (!function_exists('fa_custom_setup_cdn_webfont')) {
	function fa_custom_setup_cdn_webfont($cdn_url = '', $integrity = null)
	{
		$matches = [];
		$match_result = preg_match('|/([^/]+?)\.css$|', $cdn_url, $matches);
		$resource_handle_uniqueness = ($match_result === 1) ? $matches[1] : md5($cdn_url);
		$resource_handle = "font-awesome-cdn-webfont-$resource_handle_uniqueness";

		foreach (['wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts'] as $action) {
			add_action(
				$action,
				function () use ($cdn_url, $resource_handle) {
					wp_enqueue_style($resource_handle, $cdn_url, [], null);
				}
			);
		}

		if ($integrity) {
			add_filter(
				'style_loader_tag',
				function ($html, $handle) use ($resource_handle, $integrity) {
					if (in_array($handle, [$resource_handle], true)) {
						return preg_replace(
							'/\/>$/',
							'integrity="' . $integrity .
								'" crossorigin="anonymous" />',
							$html,
							1
						);
					} else {
						return $html;
					}
				},
				10,
				2
			);
		}
	}
}

fa_custom_setup_cdn_webfont(
	'https://pro.fontawesome.com/releases/v5.10.0/css/all.css',
	'sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p'
);


// function mytheme_customize_register($wp_customize)
// {
// 	$wp_customize->add_setting('header_textcolor', array(
// 		'default'   => '#000000',
// 		'transport' => 'refresh',
// 	));
// }
// add_action('customize_register', 'mytheme_customize_register');

// $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
// 	'label'      => __('Header Color', 'violet-cards'),
// 	'section'    => 'colors',
// 	'settings'   => 'header_textcolor',
// )));
