<?php
/**
 * Fonts Enqueue
 */

if (!defined('ABSPATH')) {
    exit;
}

function ts_enqueue_frontend_assets() {
    // Bootstrap Icons
    if (defined('THEME_SETTINGS_BOOTSTRAP_ICONS') && THEME_SETTINGS_BOOTSTRAP_ICONS) {
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
            [],
            '1.11.3'
        );
    }
}
add_action('wp_enqueue_scripts', 'ts_enqueue_frontend_assets');

// keep paragraph
function my_theme_tinymce_config($init) {
    $init['wpautop'] = false;
    $init['indent'] = true;
    $init['tadv_noautop'] = true;
    $init['forced_root_block'] = 'p';
    return $init;
}
add_filter('tiny_mce_before_init', 'my_theme_tinymce_config');