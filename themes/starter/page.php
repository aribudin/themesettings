<?php
/**
 * The template for displaying pages
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
    <?php while (have_posts()) : the_post(); ?>
        
        <header class="page-header">
            <div class="container">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
        </header>

        <div class="page-content">
            <div class="container">
                <div class="<?php echo esc_attr(starter_get_layout_class()); ?>">
                    <main id="primary" class="main">
                        <div class="content">
                            <?php the_content(); ?>

                            <?php
                            wp_link_pages([
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'nametheme'),
                                'after'  => '</div>',
                            ]);
                            ?>
                        </div>

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
        </div>

    <?php endwhile; ?>
</article>

<?php
get_footer();
