<?php
/**
 * Theme Customizer
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object
 */
function starter_customize_register($wp_customize) {
    // Add selective refresh for site title and description
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector'        => '.logo-text',
            'render_callback' => function() {
                bloginfo('name');
            },
        ]);
        
        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector'        => '.site-description',
            'render_callback' => function() {
                bloginfo('description');
            },
        ]);
    }
    
    // Note: Main theme options are handled by ThemeSettings framework
    // This customizer is for WordPress core compatibility
}
add_action('customize_register', 'starter_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function starter_customize_preview_js() {
    wp_enqueue_script(
        'customizer',
        starter_URI . '/assets/js/customizer.js',
        ['customize-preview'],
        starter_VERSION,
        true
    );
}
add_action('customize_preview_init', 'starter_customize_preview_js');
