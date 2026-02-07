<?php
/**
 * Template part for displaying post card in archive/blog
 *
 * @package starter 
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php starter_post_thumbnail('featured-medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-card-content">
        <div class="label-badge">
            <?php starter_post_categories(); ?>
        </div>

        <h2 class="post-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="post-card-excerpt">
            <?php starter_excerpt(25); ?>
        </div>

        <div class="post-card-meta">
            <span class="post-card-meta-item">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>
            <span class="post-card-meta-item">
                <i class="bi bi-clock" aria-hidden="true"></i>
                <?php starter_reading_time(); ?>
            </span>
        </div>
    </div>
</article>
