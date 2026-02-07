<?php
/**
 * Template part for displaying when no content is found
 *
 * @package starter 
 * @since 1.0.0
 */

?>

<section class="no-results">
    <header class="no-results-header">
        <h1 class="no-results-title"><?php esc_html_e('Nothing Found', 'nametheme'); ?></h1>
    </header>

    <div class="no-results-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>
            <p>
                <?php
                printf(
                    /* translators: %s: URL to create new post */
                    wp_kses(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'nametheme'), ['a' => ['href' => []]]),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>
        <?php elseif (is_search()) : ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'nametheme'); ?></p>
            <?php starter_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'nametheme'); ?></p>
            <?php starter_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
