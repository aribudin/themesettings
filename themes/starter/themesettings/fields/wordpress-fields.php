<?php
/**
 * WordPress Relation Field Types
 * 
 * Page Select, Post Select, Category Select, Menu Select, Shortcode
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get all pages as options
 */
function ts_get_pages_options($args = []) {
    $defaults = [
        'post_status' => 'publish',
        'sort_column' => 'menu_order,post_title',
    ];
    
    $args = wp_parse_args($args, $defaults);
    $pages = get_pages($args);
    $options = [];
    
    foreach ($pages as $page) {
        $options[$page->ID] = $page->post_title;
    }
    
    return $options;
}

/**
 * Get posts as options
 */
function ts_get_posts_options($post_type = 'post', $args = []) {
    $defaults = [
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    ];
    
    $args = wp_parse_args($args, $defaults);
    $posts = get_posts($args);
    $options = [];
    
    foreach ($posts as $post) {
        $options[$post->ID] = $post->post_title;
    }
    
    return $options;
}

/**
 * Get categories as options
 */
function ts_get_categories_options($taxonomy = 'category', $args = []) {
    $defaults = [
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ];
    
    $args = wp_parse_args($args, $defaults);
    $terms = get_terms($args);
    $options = [];
    
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->term_id] = $term->name;
        }
    }
    
    return $options;
}

/**
 * Get menus as options
 */
function ts_get_menus_options() {
    $menus = wp_get_nav_menus();
    $options = [];
    
    foreach ($menus as $menu) {
        $options[$menu->term_id] = $menu->name;
    }
    
    return $options;
}

/**
 * Get users as options
 */
function ts_get_users_options($role = '') {
    $args = [
        'orderby' => 'display_name',
        'order' => 'ASC',
    ];
    
    if ($role) {
        $args['role'] = $role;
    }
    
    $users = get_users($args);
    $options = [];
    
    foreach ($users as $user) {
        $options[$user->ID] = $user->display_name;
    }
    
    return $options;
}

/**
 * Get registered post types as options
 */
function ts_get_post_types_options($exclude = ['attachment', 'revision', 'nav_menu_item']) {
    $post_types = get_post_types(['public' => true], 'objects');
    $options = [];
    
    foreach ($post_types as $post_type) {
        if (!in_array($post_type->name, $exclude)) {
            $options[$post_type->name] = $post_type->labels->singular_name;
        }
    }
    
    return $options;
}

/**
 * Get taxonomies as options
 */
function ts_get_taxonomies_options($post_type = '') {
    $args = ['public' => true];
    
    if ($post_type) {
        $args['object_type'] = [$post_type];
    }
    
    $taxonomies = get_taxonomies($args, 'objects');
    $options = [];
    
    foreach ($taxonomies as $taxonomy) {
        $options[$taxonomy->name] = $taxonomy->labels->singular_name;
    }
    
    return $options;
}

/**
 * Validate shortcode
 */
function ts_validate_shortcode($shortcode) {
    if (empty($shortcode)) {
        return '';
    }
    
    // Check if it's wrapped in brackets
    if (!preg_match('/^\[.*\]$/', trim($shortcode))) {
        return '';
    }
    
    return $shortcode;
}

/**
 * Execute shortcode with wrapper
 */
function ts_do_shortcode($shortcode, $wrapper_class = '') {
    if (empty($shortcode)) {
        return '';
    }
    
    $output = do_shortcode($shortcode);
    
    if ($wrapper_class && !empty($output)) {
        $output = '<div class="' . esc_attr($wrapper_class) . '">' . $output . '</div>';
    }
    
    return $output;
}
