<?php
/**
 * The header template
 *
 * @package starter 
 * @since 1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <?php
    $favicon = ts_get_option('favicon_image');
    if (!empty($favicon)) {
        $favicon_url = is_array($favicon) ? $favicon['url'] : wp_get_attachment_url($favicon);
        if ($favicon_url) {
            echo '<link rel="icon" href="' . esc_url($favicon_url) . '">';
        }
    }
    ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e('Skip to content', 'nametheme'); ?>
    </a>

    <header id="masthead" class="header">
        <div class="container">
            <div class="header-inner">
                <div class="logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php
                        // get logo
                        $logo = ts_get_option('logo_image');
                        
                        if (!empty($logo)) {
                            if (is_array($logo) && isset($logo['url'])) {
                                echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo">';
                            }
                            elseif (is_numeric($logo)) {
                                $logo_url = wp_get_attachment_url($logo);
                                if ($logo_url) {
                                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo">';
                                }
                            }
                        } else {
                            // Fallback
                            bloginfo('name');
                        }
                        ?>
                    </a>
                </div>

                <!-- Primary Navigation -->
                <nav id="site-navigation" class="nav" aria-label="<?php esc_attr_e('Primary Menu', 'nametheme'); ?>">
                    <?php
                    // get menu
                    $header_menu = ts_get_option('header_menu');
                    
                    if (!empty($header_menu)) {
                        wp_nav_menu([
                            'menu'           => $header_menu,
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 2,
                        ]);
                    } else {
                        // Fallback
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 2,
                        ]);
                    }
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button type="button" class="menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'nametheme'); ?>" aria-expanded="false" aria-controls="mobile-menu">
                    <span></span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-nav">
            <?php
            $header_menu = ts_get_option('header_menu');
            
            if (!empty($header_menu)) {
                wp_nav_menu([
                    'menu'           => $header_menu,
                    'menu_id'        => 'mobile-menu-list',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
            } else {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu-list',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
            }
            ?>
        </div>
    </header>

    <div id="content" class="site-content">
