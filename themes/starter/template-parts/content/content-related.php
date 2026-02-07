<?php
/**
 * Template part for displaying related post card
 *
 * @package starter 
 * @since 1.0.0
 */

?>

<article class="related-card">
    <?php if (has_post_thumbnail()) : ?>
        <div class="related-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php starter_post_thumbnail('featured-medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="related-card-content">
        <h4 class="related-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
        <span class="related-card-date">
            <?php echo get_the_date(); ?>
        </span>
    </div>
</article>
