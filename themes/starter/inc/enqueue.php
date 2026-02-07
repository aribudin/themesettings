<?php
/**
 * Enqueue scripts and styles
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue frontend scripts and styles
 */
function starter_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'style',
        get_stylesheet_uri(),
        ['ts-google-fonts'],
        starter_VERSION
    );

    // Main script
    wp_enqueue_script(
        'script',
        starter_URI . '/assets/js/main.js',
        [],
        starter_VERSION,
        true
    );

    // Localize script
    wp_localize_script('script', 'starterStarterData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('starter_nonce'),
        'i18n'    => [
            'loading'    => esc_html__('Loading...', 'nametheme'),
            'noMorePosts' => esc_html__('No more posts to load.', 'nametheme'),
            'error'      => esc_html__('Something went wrong. Please try again.', 'nametheme'),
        ],
    ]);

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'starter_scripts', 10);

/**
 * Enqueue admin scripts and styles
 */
function starter_admin_scripts($hook) {
    // Only on post edit screens
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_style(
            'admin',
            starter_URI . '/assets/css/admin.css',
            [],
            starter_VERSION
        );
    }
}
add_action('admin_enqueue_scripts', 'starter_admin_scripts');

/**
 * Preload critical assets
 */
function starter_preload_assets() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style">' . "\n";
}
add_action('wp_head', 'starter_preload_assets', 1);

/**
 * Add async/defer to scripts
 *
 * @param string $tag Script tag
 * @param string $handle Script handle
 * @param string $src Script source
 * @return string Modified script tag
 */
function starter_script_loader_tag($tag, $handle, $src) {
    // Add defer to main script
    if ('script' === $handle) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'starter_script_loader_tag', 10, 3);

/**
 * Remove unnecessary scripts/styles
 */
function starter_remove_unnecessary_assets() {
    // Remove block library CSS on frontend if not using blocks
    if (!is_admin() && !ts_get_option('enable_gutenberg_styles', true)) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('global-styles');
    }
}
add_action('wp_enqueue_scripts', 'starter_remove_unnecessary_assets', 100);
