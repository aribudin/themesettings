<?php
/**
 * starter  functions and definitions
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('starter_VERSION', '1.0.0');
define('starter_DIR', get_template_directory());
define('starter_URI', get_template_directory_uri());
define('starter_INC', starter_DIR . '/inc');

/**
 * Include theme files
 */
require_once starter_INC . '/theme-setup.php';
require_once starter_INC . '/enqueue.php';
require_once starter_INC . '/template-tags.php';
require_once starter_INC . '/template-functions.php';
require_once starter_INC . '/widgets.php';
require_once starter_INC . '/customizer.php';

/**
 * Include ThemeSettings framework
 */
if (file_exists(starter_DIR . '/themesettings/theme-settings.php')) {
    require_once starter_DIR . '/themesettings/theme-settings.php';
}

/**
 * Theme Setup
 */
function starter_setup() {
    // Make theme available for translation
    load_theme_textdomain('nametheme', starter_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Set default Post Thumbnail size
    set_post_thumbnail_size(1200, 630, true);

    // Add custom image sizes
    add_image_size('featured-large', 1200, 630, true);
    add_image_size('featured-medium', 600, 400, true);
    add_image_size('featured-small', 300, 200, true);
    add_image_size('thumbnail', 150, 150, true);

    // Register navigation menus
    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'nametheme'),
        'footer'  => esc_html__('Footer Menu', 'nametheme'),
        'social'  => esc_html__('Social Links', 'nametheme'),
    ]);

    // Switch default core markup for search form, comment form, and comments
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Add support for core custom logo
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for custom background
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
    ]);

    // Add support for starter content
    add_theme_support('content', starter_get_starter_content());
}
add_action('after_setup_theme', 'starter_setup');

/**
 * Set the content width in pixels
 */
function starter_content_width() {
    $GLOBALS['content_width'] = apply_filters('starter_content_width', 800);
}
add_action('after_setup_theme', 'starter_content_width', 0);

function starter_get_option($key, $default = '') {
    // Call ThemeSettings function
    if (function_exists('ts_get_option')) {
        return ts_get_option($key, $default);
    }
    
    // Fallback to database
    $options = get_option('ts_options', []);
    return isset($options[$key]) ? $options[$key] : $default;
}

function starter_has_option($key) {
    if (function_exists('ts_get_option')) {
        $value = ts_get_option($key, null);
        return !empty($value);
    }
    
    $options = get_option('ts_options', []);
    return isset($options[$key]) && !empty($options[$key]);
}

/**
 * Get starter content for theme
 *
 * @return array
 */
function starter_get_starter_content() {
    return [
        'widgets' => [
            'sidebar-1' => [
                'search',
                'categories',
                'recent-posts',
            ],
            'footer-1' => [
                'text_about' => [
                    'text',
                    [
                        'title' => esc_html__('About Us', 'nametheme'),
                        'text'  => esc_html__('starter  is a clean, professional WordPress theme designed for bloggers and content creators.', 'nametheme'),
                    ],
                ],
            ],
            'footer-2' => [
                'recent-posts',
            ],
            'footer-3' => [
                'categories',
            ],
        ],
        'nav_menus' => [
            'primary' => [
                'name'  => esc_html__('Primary Menu', 'nametheme'),
                'items' => [
                    'page_home',
                    'page_about',
                    'page_blog',
                    'page_contact',
                ],
            ],
        ],
    ];
}

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function starter_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }
    
    return ts_get_option('excerpt_length', 25);
}
add_filter('excerpt_length', 'starter_excerpt_length');

/**
 * Custom excerpt more
 *
 * @param string $more More string
 * @return string
 */
function starter_excerpt_more($more) {
    if (is_admin()) {
        return $more;
    }
    
    return '&hellip;';
}
add_filter('excerpt_more', 'starter_excerpt_more');

/**
 * Add custom body classes
 *
 * @param array $classes Body classes
 * @return array
 */
function starter_body_classes($classes) {
    // Add class for sidebar position
    $sidebar_position = ts_get_option('sidebar_position', 'right');
    $classes[] = 'sidebar-' . $sidebar_position;
    
    // Add class if no sidebar
    if (!is_active_sidebar('sidebar-1') || is_page_template('templates/full-width.php')) {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for sticky header
    if (ts_get_option('sticky_header', true)) {
        $classes[] = 'has-sticky-header';
    }
    
    // Adds a class of hfeed to non-singular pages
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    
    // Adds a class of no-sidebar when there is no sidebar present
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'starter_body_classes');

/**
 * Add custom post classes
 *
 * @param array $classes Post classes
 * @return array
 */
function starter_post_classes($classes) {
    $classes[] = 'post';
    
    if (!has_post_thumbnail()) {
        $classes[] = 'no-thumbnail';
    }
    
    return $classes;
}
add_filter('post_class', 'starter_post_classes');

/**
 * Pingback header for single posts
 */
function starter_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'starter_pingback_header');

/**
 * Add preconnect for Google Fonts
 *
 * @param array $urls URLs to print for resource hints
 * @param string $relation_type The relation type
 * @return array
 */
function starter_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = [
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        ];
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'starter_resource_hints', 10, 2);

/**
 * Disable WordPress emojis
 */
function starter_disable_emojis() {
    if (ts_get_option('disable_emojis', false)) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }
}
add_action('init', 'starter_disable_emojis');

/**
 * Output dynamic CSS from theme options
 */
function starter_dynamic_css() {
    $css = '';
    
    // ===== COLORS dari Theme Settings =====
    
    // Primary color
    $primary_color = ts_get_option('primary_color', '#0066cc');
    if ($primary_color) {
        $css .= ":root { --color-primary: {$primary_color}; }\n";
    }
    
    // Secondary color
    $secondary_color = ts_get_option('secondary_color', '#6c757d');
    if ($secondary_color) {
        $css .= ":root { --color-secondary: {$secondary_color}; }\n";
    }
    
    // Accent color
    $accent_color = ts_get_option('accent_color', '#ff6b6b');
    if ($accent_color) {
        $css .= ":root { --color-accent: {$accent_color}; }\n";
    }
    
    // Footer background color
    $footer_bg_color = ts_get_option('footer_bg_color', '#1a1a1a');
    if ($footer_bg_color) {
        $css .= ":root { --color-footer-bg: {$footer_bg_color}; }\n";
        $css .= ".footer { background-color: {$footer_bg_color}; }\n";
    }
    
    // ===== TYPOGRAPHY dari Theme Settings =====
    
    // Headings font
    $headings_font = ts_get_option('headings_font', '');
    if ($headings_font) {
        $css .= ":root { --font-headings: '{$headings_font}', sans-serif; }\n";
        $css .= "h1, h2, h3, h4, h5, h6, h1>a, h2>a, h3>a, h4>a, h5>a, h6>a, .site-title, .text-heading { font-family: '{$headings_font}', sans-serif; }\n";
    }
    
    // Body font
    $body_font = ts_get_option('body_font', '');
    if ($body_font) {
        $css .= ":root { --font-body: '{$body_font}', sans-serif; }\n";
        $css .= "body, p, div, span, a, input, textarea, select, button { font-family: '{$body_font}', sans-serif; }\n";
    }
    
    // Base font size
    $base_font_size = ts_get_option('base_font_size', 16);
    if ($base_font_size && $base_font_size != 16) {
        $css .= ":root { --font-size-base: {$base_font_size}px; }\n";
        $css .= "body { font-size: {$base_font_size}px; }\n";
    }
    
    // ===== CUSTOM CSS =====
    $custom_css = ts_get_option('custom_css', '');
    if ($custom_css) {
        $css .= "\n/* Custom CSS from Theme Settings */\n";
        $css .= wp_strip_all_tags($custom_css) . "\n";
    }
    
    // Output CSS
    if ($css) {
        wp_add_inline_style('style', $css);
    }
}
// Hook
add_action('wp_enqueue_scripts', 'starter_dynamic_css', 20);

/**
 * Get Google Fonts URL based on theme settings
 *
 * @return string|false
 */
function starter_get_google_fonts_url() {
    $fonts = [];
    
    // Get fonts from theme settings
    $headings_font = ts_get_option('headings_font', '');
    $body_font = ts_get_option('body_font', '');
    
    // Add headings font
    if ($headings_font && !in_array($headings_font, ['Arial', 'Helvetica', 'Times New Roman', 'Georgia', 'Verdana'])) {
        $fonts[] = str_replace(' ', '+', $headings_font) . ':wght@400;500;600;700';
    }
    
    // Add body font (hindari duplikat)
    if ($body_font && $body_font !== $headings_font && !in_array($body_font, ['Arial', 'Helvetica', 'Times New Roman', 'Georgia', 'Verdana'])) {
        $fonts[] = str_replace(' ', '+', $body_font) . ':wght@400;500;600;700';
    }
    
    // Return Google Fonts URL
    if (!empty($fonts)) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts) . '&display=swap';
        
        return esc_url_raw($fonts_url);
    }
    
    return false;
}

/**
 * Add favicon from theme settings
 */
function starter_add_favicon() {
    $favicon = ts_get_option('favicon_image');
    
    if (!empty($favicon)) {
        $favicon_url = '';
        
        if (is_array($favicon) && isset($favicon['url'])) {
            $favicon_url = $favicon['url'];
        } elseif (is_numeric($favicon)) {
            $favicon_url = wp_get_attachment_url($favicon);
        }
        
        if ($favicon_url) {
            echo '<link rel="icon" href="' . esc_url($favicon_url) . '" type="image/x-icon">' . "\n";
            echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" type="image/x-icon">' . "\n";
        }
    }
}
add_action('wp_head', 'starter_add_favicon', 5);

function starter_get_media_url($media, $size = 'full') {
    if (empty($media)) {
        return false;
    }
    
    // url
    if (is_array($media) && isset($media['url'])) {
        return $media['url'];
    }
    
    // ID
    if (is_numeric($media)) {
        return wp_get_attachment_image_url($media, $size);
    }
    
    return false;
}

/**
 * AJAX Load More Posts
 */
function starter_load_more_posts() {
    check_ajax_referer('starter_nonce', 'nonce');
    
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('posts_per_page'),
        'paged'          => $paged,
    ];
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content/content', 'card');
        }
    }
    
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_starter_load_more', 'starter_load_more_posts');
add_action('wp_ajax_nopriv_starter_load_more', 'starter_load_more_posts');
