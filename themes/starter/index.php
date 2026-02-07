<?php
/**
 * The main template file
 *
 * @package starter 
 * @since 1.0.0
 */

get_header();

// ===== HERO SECTION =====
$hero_show = ts_get_option('hero_show', true);
if (is_front_page() && $hero_show) :
    $hero_title = ts_get_option('hero_title', 'Build Something Amazing');
    $hero_description = ts_get_option('hero_description', 'Create beautiful websites with our powerful theme.');
    $hero_bg = ts_get_option('hero_bg_image');
    
    // Get background image URL
    $hero_bg_url = '';
    if (!empty($hero_bg)) {
        if (is_array($hero_bg) && isset($hero_bg['url'])) {
            $hero_bg_url = $hero_bg['url'];
        } elseif (is_numeric($hero_bg)) {
            $hero_bg_url = wp_get_attachment_url($hero_bg);
        }
    }
    ?>
    <section class="hero-section" <?php if ($hero_bg_url) echo 'style="background-image: url(' . esc_url($hero_bg_url) . ');"'; ?>>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <?php if ($hero_title) : ?>
                    <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php endif; ?>
                
                <?php if ($hero_description) : ?>
                    <p class="hero-description"><?php echo esc_html($hero_description); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
endif;

// ===== ABOUT SECTION =====
$about_show = ts_get_option('about_show', true);
if (is_front_page() && $about_show) :
    $about_title = ts_get_option('about_title', 'Who We Are');
    $about_description = ts_get_option('about_description', '');
    $about_image = ts_get_option('about_image');
    
    // Get image URL
    $about_image_url = '';
    if (!empty($about_image)) {
        if (is_array($about_image) && isset($about_image['url'])) {
            $about_image_url = $about_image['url'];
        } elseif (is_numeric($about_image)) {
            $about_image_url = wp_get_attachment_url($about_image);
        }
    }
    ?>
    <section class="about-section">
        <div class="container">
            <div class="about-grid">
                <?php if ($about_image_url) : ?>
                    <div class="about-image">
                        <img src="<?php echo esc_url($about_image_url); ?>" alt="<?php echo esc_attr($about_title); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="about-content">
                    <?php if ($about_title) : ?>
                        <h2 class="about-title"><?php echo esc_html($about_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($about_description) : ?>
                        <div class="about-description">
                            <?php echo wp_kses_post($about_description); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
?>

<div class="container">
    <div class="<?php echo esc_attr(starter_get_layout_class()); ?>">
        <main id="primary" class="main">
            <header class="mb-8">
                <h2><?php esc_html_e('Latest Articles', 'nametheme'); ?></h2>
            </header>

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
