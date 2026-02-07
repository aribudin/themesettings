<?php
/**
 * Theme Setup Functions
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register widget areas
 */
function starter_widgets_init() {
    // Main Sidebar
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'nametheme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in sidebar.', 'nametheme'),
        'before_widget' => '<div id="%1$s" class="widget widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    // Footer Widget Areas
    $footer_widgets = ts_get_option('footer_widgets', 4);
    
    for ($i = 1; $i <= $footer_widgets; $i++) {
        register_sidebar([
            'name'          => sprintf(esc_html__('Footer %d', 'nametheme'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d.', 'nametheme'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ]);
    }
}
add_action('widgets_init', 'starter_widgets_init');

/**
 * Add editor styles
 */
function starter_add_editor_styles() {
    add_editor_style([
        'assets/css/editor-style.css',
        starters_get_google_fonts_url(),
    ]);
}
add_action('admin_init', 'starter_add_editor_styles');

/**
 * Get Google Fonts URL
 *
 * @return string
 */
function starters_get_google_fonts_url() {
    $fonts_url = '';
    $fonts     = [];
    $subsets   = 'latin,latin-ext';

    // Body font
    $body_font = ts_get_option('body_font', 'Inter');
    if ($body_font && $body_font !== 'System Default') {
        $fonts[] = $body_font . ':wght@400;500;600;700';
    }

    // Heading font
    $heading_font = ts_get_option('headings_font', 'Inter');
    if ($heading_font && $heading_font !== 'System Default' && $heading_font !== $body_font) {
        $fonts[] = $heading_font . ':wght@500;600;700;800';
    }

    if ($fonts) {
        $fonts_url = add_query_arg([
            'family'  => implode('&family=', array_map('urlencode', $fonts)),
            'display' => 'swap',
        ], 'https://fonts.googleapis.com/css2');
    }

    return $fonts_url;
}

/**
 * Add reading time to posts
 *
 * @param int $post_id Post ID
 * @return int Reading time in minutes
 */
function starter_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    return max(1, $reading_time);
}

/**
 * Get post views
 *
 * @param int $post_id Post ID
 * @return int View count
 */
function starter_get_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $views = get_post_meta($post_id, '_starter_views', true);
    
    return $views ? intval($views) : 0;
}

/**
 * Increment post views
 */
function starter_track_post_views() {
    if (is_single() && !is_admin()) {
        $post_id = get_the_ID();
        $views = starter_get_post_views($post_id);
        update_post_meta($post_id, '_starter_views', $views + 1);
    }
}
add_action('wp_head', 'starter_track_post_views');

/**
 * Custom comment callback
 *
 * @param WP_Comment $comment Comment object
 * @param array $args Arguments
 * @param int $depth Depth
 */
function starter_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-avatar">
                <?php echo get_avatar($comment, 50); ?>
            </div>
            <div class="comment-content">
                <header class="comment-header">
                    <span class="comment-author"><?php comment_author_link($comment); ?></span>
                    <time class="comment-date" datetime="<?php comment_time('c'); ?>">
                        <?php
                        printf(
                            /* translators: %s: Comment date */
                            esc_html__('%s ago', 'nametheme'),
                            human_time_diff(get_comment_time('U'), current_time('timestamp'))
                        );
                        ?>
                    </time>
                </header>
                
                <?php if ($comment->comment_approved == '0') : ?>
                    <p class="comment-awaiting-moderation">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'nametheme'); ?>
                    </p>
                <?php endif; ?>
                
                <div class="comment-text">
                    <?php comment_text(); ?>
                </div>
                
                <div class="comment-reply">
                    <?php
                    comment_reply_link(array_merge($args, [
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '',
                        'after'     => '',
                    ]));
                    ?>
                    <?php edit_comment_link(esc_html__('Edit', 'nametheme'), ' · '); ?>
                </div>
            </div>
        </article>
    <?php
}
