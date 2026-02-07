<?php
/**
 * The template for displaying single posts
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
    <?php while (have_posts()) : the_post(); ?>
        
        <header class="single-header">
            <div class="container container-narrow">
                <?php
                $categories = get_the_category();
                if ($categories) :
                    $category = $categories[0];
                    ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="badge">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endif; ?>

                <h1 class="single-title"><?php the_title(); ?></h1>

                <div class="single-meta">
                    <div class="single-meta-item single-author">
                        <div class="single-author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                        </div>
                        <span>
                            <?php esc_html_e('By', 'nametheme'); ?>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php the_author(); ?>
                            </a>
                        </span>
                    </div>

                    <div class="single-meta-item">
                        <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                    </div>

                    <div class="single-meta-item">
                        <i class="bi bi-clock" aria-hidden="true"></i>
                        <span><?php starter_reading_time(); ?></span>
                    </div>
                </div>
            </div>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <div class="container">
                <div class="single-featured-image">
                    <?php the_post_thumbnail('featured-large', ['loading' => 'eager']); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="<?php echo esc_attr(starter_get_layout_class()); ?>">
                <main id="primary" class="main">
                    <div class="content mb-4">
                        <?php the_content(); ?>

                        <?php
                        wp_link_pages([
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'nametheme'),
                            'after'  => '</div>',
                        ]);
                        ?>
                    </div>

                    <?php starter_post_tags(); ?>

                    <?php starter_social_share(); ?>

                    <?php starter_author_box(); ?>

                    <?php starter_post_navigation(); ?>

                    <?php starter_related_posts(3); ?>

                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </main>

                <?php if (starter_has_sidebar()) : ?>
                    <?php get_sidebar(); ?>
                <?php endif; ?>
            </div>
        </div>

    <?php endwhile; ?>
</article>

<?php
get_footer();
