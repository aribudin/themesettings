<?php
/**
 * Visual Field Types
 * 
 * Color Picker, Gradient Picker, Media Upload, Image Select, Range Slider, Typography
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Google Fonts list
 */
function ts_get_google_fonts() {
    // Cache the fonts list
    $transient_key = 'ts_google_fonts';
    $fonts = get_transient($transient_key);
    
    if (false === $fonts) {
        $fonts = [
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Raleway' => 'Raleway',
            'Source Sans Pro' => 'Source Sans Pro',
            'PT Sans' => 'PT Sans',
            'Nunito' => 'Nunito',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Rubik' => 'Rubik',
            'Work Sans' => 'Work Sans',
            'Inter' => 'Inter',
            'Oswald' => 'Oswald',
            'Quicksand' => 'Quicksand',
            'Josefin Sans' => 'Josefin Sans',
            'Ubuntu' => 'Ubuntu',
            'Mukta' => 'Mukta',
            'Noto Sans' => 'Noto Sans',
            'Fira Sans' => 'Fira Sans',
            'DM Sans' => 'DM Sans',
            'Manrope' => 'Manrope',
            'Space Grotesk' => 'Space Grotesk',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
        ];
        
        set_transient($transient_key, $fonts, DAY_IN_SECONDS);
    }
    
    return $fonts;
}

/**
 * Generate font URL for Google Fonts
 */
function ts_get_google_font_url($fonts) {
    if (empty($fonts)) {
        return '';
    }
    
    if (!is_array($fonts)) {
        $fonts = [$fonts];
    }
    
    $font_families = [];
    foreach ($fonts as $font) {
        if ($font !== 'system' && !in_array($font, ['Arial', 'Georgia', 'Times New Roman', 'Verdana'])) {
            $font_families[] = str_replace(' ', '+', $font) . ':wght@400;500;600;700';
        }
    }
    
    if (empty($font_families)) {
        return '';
    }
    
    return 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_families) . '&display=swap';
}

/**
 * Enqueue selected fonts
 */
function ts_enqueue_theme_fonts() {
    $options = get_option('ts_options', []);
    
    $fonts = [];
    
    if (!empty($options['body_font']) && $options['body_font'] !== 'system') {
        $fonts[] = $options['body_font'];
    }
    
    if (!empty($options['headings_font']) && $options['headings_font'] !== 'system') {
        $fonts[] = $options['headings_font'];
    }
    
    $fonts = array_unique($fonts);
    $font_url = ts_get_google_font_url($fonts);
    
    if ($font_url) {
        wp_enqueue_style('ts-google-fonts', $font_url, [], null);
    }
}
add_action('wp_enqueue_scripts', 'ts_enqueue_theme_fonts', 5);

/**
 * Convert hex to rgba
 */
function ts_hex_to_rgba($hex, $alpha = 1) {
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return "rgba({$r}, {$g}, {$b}, {$alpha})";
}

/**
 * Get image size options
 */
function ts_get_image_sizes() {
    $sizes = [];
    $registered_sizes = get_intermediate_image_sizes();
    
    foreach ($registered_sizes as $size) {
        $sizes[$size] = ucwords(str_replace(['_', '-'], ' ', $size));
    }
    
    $sizes['full'] = __('Full Size', 'nametheme');
    
    return $sizes;
}
