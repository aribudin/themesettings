<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, post
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page full-width'); ?>>
    <?php while (have_posts()) : the_post(); ?>
        
        <header class="page-header">
            <div class="container">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
        </header>

        <div class="page-content">
            <div class="container">
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
            </div>
        </div>

    <?php endwhile; ?>
</article>

<?php
get_footer();
