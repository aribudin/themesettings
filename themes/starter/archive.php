<?php
/**
 * The template for displaying archive pages
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<header class="archive-header">
    <div class="container">
        <h1 class="archive-title">
            <?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_tag()) {
                single_tag_title();
            } elseif (is_author()) {
                the_author();
            } elseif (is_year()) {
                echo get_the_date('Y');
            } elseif (is_month()) {
                echo get_the_date('F Y');
            } elseif (is_day()) {
                echo get_the_date();
            } else {
                esc_html_e('Archives', 'nametheme');
            }
            ?>
        </h1>
        <?php
        $description = get_the_archive_description();
        if ($description) :
            ?>
            <div class="archive-description">
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>
    </div>
</header>

<div class="container">
    <div class="<?php echo esc_attr(starter_get_layout_class()); ?>">
        <main id="primary" class="main">
            <?php if (have_posts()) : ?>
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
                <?php get_template_part('template-parts/content/content', 'none'); ?>
            <?php endif; ?>
        </main>

        <?php if (starter_has_sidebar()) : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
