<?php
/**
 * Template Functions
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get sidebar position
 *
 * @return string Sidebar position (left, right, none)
 */
function starter_get_sidebar_position() {
    // Check page template
    if (is_page_template('templates/full-width.php')) {
        return 'none';
    }
    
    // Check single post setting
    if (is_single()) {
        $post_sidebar = get_post_meta(get_the_ID(), '_starter_sidebar', true);
        if ($post_sidebar && $post_sidebar !== 'default') {
            return $post_sidebar;
        }
    }
    
    // Get global setting
    return ts_get_option('sidebar_position', 'right');
}

/**
 * Get layout classes for main content
 *
 * @return string CSS classes
 */
function starter_get_layout_class() {
    $classes = ['main-layout'];
    $sidebar = starter_get_sidebar_position();
    
    if ($sidebar === 'left') {
        $classes[] = 'sidebar-left';
    } elseif ($sidebar === 'none') {
        $classes[] = 'no-sidebar';
    }
    
    return implode(' ', $classes);
}

/**
 * Check if sidebar should be displayed
 *
 * @return bool
 */
function starter_has_sidebar() {
    $position = starter_get_sidebar_position();
    
    if ($position === 'none') {
        return false;
    }
    
    return is_active_sidebar('sidebar-1');
}

/**
 * Get featured posts for homepage
 *
 * @param int $count Number of posts
 * @return WP_Query
 */
function starter_get_featured_posts($count = 5) {
    // First try to get posts marked as featured
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'meta_key'       => '_starter_featured',
        'meta_value'     => '1',
    ];
    
    $query = new WP_Query($args);
    
    // If not enough featured posts, get latest with thumbnails
    if ($query->post_count < $count) {
        $exclude = wp_list_pluck($query->posts, 'ID');
        
        $fallback_args = [
            'post_type'      => 'post',
            'posts_per_page' => $count - $query->post_count,
            'post_status'    => 'publish',
            'post__not_in'   => $exclude,
            'meta_query'     => [
                [
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ],
            ],
        ];
        
        $fallback_query = new WP_Query($fallback_args);
        
        // Merge results
        $query->posts = array_merge($query->posts, $fallback_query->posts);
        $query->post_count = count($query->posts);
    }
    
    return $query;
}

/**
 * Get social links
 *
 * @return array
 */
function starter_get_social_links() {
    $social_links = [];
    
    $platforms = [
        'facebook'  => ['icon' => 'facebook', 'label' => 'Facebook'],
        'twitter'   => ['icon' => 'twitter', 'label' => 'Twitter'],
        'instagram' => ['icon' => 'instagram', 'label' => 'Instagram'],
        'linkedin'  => ['icon' => 'linkedin', 'label' => 'LinkedIn'],
        'youtube'   => ['icon' => 'youtube', 'label' => 'YouTube'],
        'pinterest' => ['icon' => 'pinterest', 'label' => 'Pinterest'],
        'tiktok'    => ['icon' => 'tiktok', 'label' => 'TikTok'],
        'github'    => ['icon' => 'github', 'label' => 'GitHub'],
    ];
    
    foreach ($platforms as $key => $platform) {
        $url = ts_get_option('social_' . $key, '');
        if ($url) {
            $social_links[] = [
                'url'   => $url,
                'icon'  => $platform['icon'],
                'label' => $platform['label'],
            ];
        }
    }
    
    return $social_links;
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param int $size Icon size
 * @return string SVG markup
 */
function starter_get_icon($icon, $size = 24) {
    $icons = [
        'search' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>',
        'menu' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
        'close' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        'calendar' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'user' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'clock' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'arrow-up' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>',
        'chevron-left' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>',
        'chevron-right' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>',
        'facebook' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        'twitter' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>',
        'instagram' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        'linkedin' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
        'youtube' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="white"/></svg>',
        'github' => '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
    ];
    
    if (!isset($icons[$icon])) {
        return '';
    }
    
    return sprintf($icons[$icon], $size, $size);
}

/**
 * Echo SVG icon
 *
 * @param string $icon Icon name
 * @param int $size Icon size
 */
function starter_icon($icon, $size = 24) {
    echo starter_get_icon($icon, $size);
}

/**
 * Get logo HTML
 *
 * @return string
 */
function starter_get_logo() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = ts_get_option('site_logo', '');
    
    if ($custom_logo_id) {
        $logo_img = wp_get_attachment_image($custom_logo_id, 'full', false, [
            'class'   => 'logo-img',
            'loading' => 'eager',
        ]);
        return sprintf(
            '<a href="%s" class="logo" rel="home">%s</a>',
            esc_url(home_url('/')),
            $logo_img
        );
    }
    
    if ($logo_url && isset($logo_url['url'])) {
        return sprintf(
            '<a href="%s" class="logo" rel="home"><img src="%s" alt="%s" class="logo-img" loading="eager"></a>',
            esc_url(home_url('/')),
            esc_url($logo_url['url']),
            esc_attr(get_bloginfo('name'))
        );
    }
    
    return sprintf(
        '<a href="%s" class="logo logo-text" rel="home">%s</a>',
        esc_url(home_url('/')),
        esc_html(get_bloginfo('name'))
    );
}

/**
 * Display search form
 *
 * @param string $placeholder Placeholder text
 */
function starter_search_form($placeholder = '') {
    if (empty($placeholder)) {
        $placeholder = esc_attr__('Search...', 'nametheme');
    }
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <label class="screen-reader-text"><?php esc_html_e('Search for:', 'nametheme'); ?></label>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s">
        <button type="submit" class="search-submit">
            <i class="bi bi-search" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php esc_html_e('Search', 'nametheme'); ?></span>
        </button>
    </form>
    <?php
}

/**
 * Get copyright text
 *
 * @return string
 */
function starter_get_copyright() {
    $custom_copyright = ts_get_option('copyright_text', '');
    
    if ($custom_copyright) {
        return str_replace(
            ['{year}', '{site_name}'],
            [date('Y'), get_bloginfo('name')],
            $custom_copyright
        );
    }
    
    return sprintf(
        /* translators: 1: Current year, 2: Site name */
        esc_html__('© %1$s %2$s. All rights reserved.', 'nametheme'),
        date('Y'),
        get_bloginfo('name')
    );
}

/**
 * Check if current page is blog
 *
 * @return bool
 */
function starter_is_blog() {
    return (is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' === get_post_type();
}
