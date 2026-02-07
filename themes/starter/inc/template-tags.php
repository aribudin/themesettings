<?php
/**
 * Template Tags
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display post categories
 *
 * @param bool $link Whether to link categories
 */
function starter_post_categories($link = true) {
    $categories = get_the_category();
    
    if (empty($categories)) {
        return;
    }
    
    $category = $categories[0];
    
    if ($link) {
        printf(
            '<a href="%s" class="post-card-category-link">%s</a>',
            esc_url(get_category_link($category->term_id)),
            esc_html($category->name)
        );
    } else {
        echo esc_html($category->name);
    }
}

/**
 * Display post date
 *
 * @param string $format Date format
 * @param bool $relative Whether to show relative time
 */
function starter_post_date($format = '', $relative = false) {
    if ($relative) {
        $time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
        printf(
            /* translators: %s: Human-readable time difference */
            esc_html__('%s ago', 'nametheme'),
            $time_diff
        );
    } else {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        echo get_the_date($format);
    }
}

/**
 * Display post author
 *
 * @param bool $avatar Whether to show avatar
 * @param int $avatar_size Avatar size
 */
function starter_post_author($avatar = false, $avatar_size = 40) {
    if ($avatar) {
        echo get_avatar(get_the_author_meta('ID'), $avatar_size);
    }
    
    printf(
        '<a href="%s">%s</a>',
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );
}

/**
 * Display reading time
 *
 * @param int $post_id Post ID
 */
function starter_reading_time($post_id = null) {
    $reading_time = starter_get_reading_time($post_id);
    
    printf(
        /* translators: %d: Reading time in minutes */
        esc_html(_n('%d min read', '%d min read', $reading_time, 'nametheme')),
        $reading_time
    );
}

/**
 * Display post thumbnail
 *
 * @param string $size Image size
 * @param array $attr Image attributes
 */
function starter_post_thumbnail($size = 'featured-medium', $attr = []) {
    if (!has_post_thumbnail()) {
        // Show placeholder
        printf(
            '<img src="%s" alt="%s" class="placeholder" loading="lazy">',
            esc_url(starter_URI . '/assets/images/placeholder.svg'),
            esc_attr(get_the_title())
        );
        return;
    }
    
    $default_attr = [
        'loading' => 'lazy',
        'alt'     => get_the_title(),
    ];
    
    $attr = wp_parse_args($attr, $default_attr);
    
    the_post_thumbnail($size, $attr);
}

/**
 * Display post excerpt
 *
 * @param int $length Excerpt length
 */
function starter_excerpt($length = null) {
    if ($length === null) {
        $length = ts_get_option('excerpt_length', 25);
    }
    
    $excerpt = get_the_excerpt();
    $excerpt = wp_trim_words($excerpt, $length, '&hellip;');
    
    echo wp_kses_post($excerpt);
}

/**
 * Display post tags
 */
function starter_post_tags() {
    $tags = get_the_tags();
    
    if (empty($tags)) {
        return;
    }
    
    echo '<div class="post-tags">';
    echo '<span class="post-tags-label">' . esc_html__('Tags:', 'nametheme') . '</span>';
    
    foreach ($tags as $tag) {
        printf(
            '<a href="%s">%s</a>',
            esc_url(get_tag_link($tag->term_id)),
            esc_html($tag->name)
        );
    }
    
    echo '</div>';
}

/**
 * Display pagination
 *
 * @param WP_Query $query Optional custom query
 */
function starter_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }
    
    if ($query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    
    echo '<nav class="pagination" aria-label="' . esc_attr__('Posts navigation', 'nametheme') . '">';
    
    echo paginate_links([
        'total'     => $query->max_num_pages,
        'current'   => $paged,
        'prev_text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>',
        'next_text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>',
    ]);
    
    echo '</nav>';
}

/**
 * Display post navigation (prev/next)
 */
function starter_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    echo '<nav class="post-nav">';
    
    if ($prev_post) {
        printf(
            '<a href="%s" class="post-nav-item prev">
                <span class="post-nav-label">%s</span>
                <span class="post-nav-title">%s</span>
            </a>',
            esc_url(get_permalink($prev_post)),
            esc_html__('Previous', 'nametheme'),
            esc_html($prev_post->post_title)
        );
    }
    
    if ($next_post) {
        printf(
            '<a href="%s" class="post-nav-item next">
                <span class="post-nav-label">%s</span>
                <span class="post-nav-title">%s</span>
            </a>',
            esc_url(get_permalink($next_post)),
            esc_html__('Next', 'nametheme'),
            esc_html($next_post->post_title)
        );
    }
    
    echo '</nav>';
}

/**
 * Display author box
 */
function starter_author_box() {
    $author_id = get_the_author_meta('ID');
    $author_bio = get_the_author_meta('description');
    
    if (empty($author_bio)) {
        return;
    }
    ?>
    <div class="author-box">
        <div class="author-avatar">
            <?php echo get_avatar($author_id, 80); ?>
        </div>
        <div class="author-info">
            <span class="author-label"><?php esc_html_e('Written by', 'nametheme'); ?></span>
            <h4 class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php the_author(); ?>
                </a>
            </h4>
            <p class="author-bio"><?php echo wp_kses_post($author_bio); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Display related posts
 *
 * @param int $count Number of posts to show
 */
function starter_related_posts($count = 3) {
    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = wp_list_pluck($categories, 'term_id');
    
    $related_query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post__not_in'   => [$post_id],
        'category__in'   => $category_ids,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ]);
    
    if (!$related_query->have_posts()) {
        return;
    }
    ?>
    <div class="related">
        <h3 class="related-title"><?php esc_html_e('Related Posts', 'nametheme'); ?></h3>
        <div class="related-grid">
            <?php
            while ($related_query->have_posts()) {
                $related_query->the_post();
                get_template_part('template-parts/content/content', 'related');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}

/**
 * Display breadcrumbs
 */
function starter_breadcrumbs() {
    if (!ts_get_option('show_breadcrumbs', true)) {
        return;
    }
    
    if (is_front_page()) {
        return;
    }
    
    $separator = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>';
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'nametheme') . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'nametheme') . '</a>';
    
    if (is_category() || is_single()) {
        echo $separator;
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }
    }
    
    if (is_single()) {
        echo $separator;
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    }
    
    if (is_page()) {
        echo $separator;
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    }
    
    if (is_tag()) {
        echo $separator;
        echo '<span>' . single_tag_title('', false) . '</span>';
    }
    
    if (is_author()) {
        echo $separator;
        echo '<span>' . get_the_author() . '</span>';
    }
    
    if (is_search()) {
        echo $separator;
        echo '<span>' . esc_html__('Search Results', 'nametheme') . '</span>';
    }
    
    if (is_404()) {
        echo $separator;
        echo '<span>' . esc_html__('Page Not Found', 'nametheme') . '</span>';
    }
    
    echo '</nav>';
}

/**
 * Display social share buttons
 */
function starter_social_share() {
    if (!ts_get_option('show_share_buttons', true)) {
        return;
    }
    
    $url = urlencode(get_permalink());
    $title = urlencode(get_the_title());
    ?>
    <div class="share">
        <span class="share-label"><?php esc_html_e('Share:', 'nametheme'); ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Facebook', 'nametheme'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Twitter', 'nametheme'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on LinkedIn', 'nametheme'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
        <a href="mailto:?subject=<?php echo $title; ?>&body=<?php echo $url; ?>" aria-label="<?php esc_attr_e('Share via Email', 'nametheme'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </a>
    </div>
    <?php
}

/**
 * Display comments count
 */
function starter_comments_count() {
    if (!comments_open()) {
        return;
    }
    
    $count = get_comments_number();
    
    if ($count == 0) {
        return;
    }
    
    printf(
        '<a href="%s#comments">%s</a>',
        esc_url(get_permalink()),
        sprintf(
            /* translators: %s: Number of comments */
            _n('%s Comment', '%s Comments', $count, 'nametheme'),
            number_format_i18n($count)
        )
    );
}
