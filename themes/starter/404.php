<?php
/**
 * The template for displaying 404 pages
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <main id="primary" class="main">
        <div class="notfound">
            <div class="notfound-code">404</div>
            <h1 class="notfound-title"><?php esc_html_e('Page Not Found', 'nametheme'); ?></h1>
            <p class="notfound-text">
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'nametheme'); ?>
            </p>
            
            <p style="margin-top: 2rem;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Back to Homepage', 'nametheme'); ?>
                </a>
            </p>
        </div>
    </main>
</div>

<?php
get_footer();
