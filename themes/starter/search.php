<?php
/**
 * The template for displaying search results
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<header class="search-header">
    <div class="container">
        <h1 class="search-title">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__('Search Results for: %s', 'nametheme'),
                '<span>' . get_search_query() . '</span>'
            );
            ?>
        </h1>
        <div class="search-form-large">
            <?php starter_search_form(); ?>
        </div>
    </div>
</header>

<div class="container">
    <div class="<?php echo esc_attr(starter_get_layout_class()); ?>">
        <main id="primary" class="main">
            <?php if (have_posts()) : ?>
                <p class="search-count">
                    <?php
                    printf(
                        /* translators: %d: number of results */
                        esc_html(_n('%d result found', '%d results found', $wp_query->found_posts, 'nametheme')),
                        $wp_query->found_posts
                    );
                    ?>
                </p>

                <div class="posts">
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/content/content', 'card');
                    endwhile;
                    ?>
                </div>

                <?php starter_pagination(); ?>

            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e('Nothing Found', 'nametheme'); ?></h2>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'nametheme'); ?></p>
                </div>
            <?php endif; ?>
        </main>

        <?php if (starter_has_sidebar()) : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
