<?php
/**
 * Theme Settings Configuration
 * 
 * Konfigurasi lengkap untuk theme settings
 * 
 * @package TS
 * @subpackage ThemeSettings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Settings_Config {
    
    /**
     * Get all fields configuration
     */
    public static function get_fields() {
        return [
            
            // ================================================================
            // TAB 1: HEADER SETTINGS
            // ================================================================
            'header_settings' => [
                'title' => __('Header', 'nametheme'),
                'icon'  => 'bi-window-stack',
                'sections' => [
                    
                    // Header Branding
                    'header_branding' => [
                        'title' => __('Header Branding', 'nametheme'),
                        'desc'  => __('Configure your site logo and favicon.', 'nametheme'),
                        'fields' => [
                            'logo_image' => [
                                'type'  => 'media',
                                'label' => __('Logo Image', 'nametheme'),
                                'desc'  => __('Upload your site logo (recommended size: 200x60px).', 'nametheme'),
                                'default' => [
                                    'id'  => 0,
                                    'url' => get_template_directory_uri() . '/assets/images/logo.png',
                                ],
                            ],
                            'favicon_image' => [
                                'type'  => 'media',
                                'label' => __('Favicon', 'nametheme'),
                                'desc'  => __('Upload your site favicon (recommended size: 32x32px or 16x16px).', 'nametheme'),
                                'default' => [
                                    'id'  => 0,
                                    'url' => get_template_directory_uri() . '/assets/images/favicon.png',
                                ],
                            ],
                        ],
                    ],
                    
                    // Header Navigation
                    'header_navigation' => [
                        'title' => __('Header Navigation', 'nametheme'),
                        'desc'  => __('Select menu for header navigation.', 'nametheme'),
                        'fields' => [
                            'header_menu' => [
                                'type'  => 'menu_select',
                                'label' => __('Select Menu', 'nametheme'),
                                'desc'  => __('Choose which menu to display in header.', 'nametheme'),
                                'default' => '',
                            ],
                        ],
                    ],
                    
                ],
            ],
            
            // ================================================================
            // TAB 2: STYLING
            // ================================================================
            'styling_settings' => [
                'title' => __('Styling', 'nametheme'),
                'icon'  => 'bi-palette',
                'sections' => [
                    
                    // Colors
                    'color_scheme' => [
                        'title' => __('Color Scheme', 'nametheme'),
                        'desc'  => __('Customize your theme colors.', 'nametheme'),
                        'fields' => [
                            'primary_color' => [
                                'type'    => 'color',
                                'label'   => __('Primary Color', 'nametheme'),
                                'desc'    => __('Main brand color for buttons, links, and accents.', 'nametheme'),
                                'default' => '#0066cc',
                            ],
                            'secondary_color' => [
                                'type'    => 'color',
                                'label'   => __('Secondary Color', 'nametheme'),
                                'desc'    => __('Secondary color for complementary elements.', 'nametheme'),
                                'default' => '#6c757d',
                            ],
                            'accent_color' => [
                                'type'    => 'color',
                                'label'   => __('Accent Color', 'nametheme'),
                                'desc'    => __('Accent color for highlights and special elements.', 'nametheme'),
                                'default' => '#ff6b6b',
                            ],
                            'footer_bg_color' => [
                                'type'    => 'color',
                                'label'   => __('Footer Background Color', 'nametheme'),
                                'desc'    => __('Background color for footer section.', 'nametheme'),
                                'default' => '#1a1a1a',
                            ],
                        ],
                    ],
                    
                    // Typography
                    'typography_settings' => [
                        'title' => __('Typography', 'nametheme'),
                        'desc'  => __('Configure fonts and text sizes.', 'nametheme'),
                        'fields' => [
                            'headings_font' => [
                                'type'    => 'typography',
                                'label'   => __('Headings Font', 'nametheme'),
                                'desc'    => __('Font family for all headings (H1, H2, H3, etc).', 'nametheme'),
                                'default' => 'Indie Flower',
                            ],
                            'body_font' => [
                                'type'    => 'typography',
                                'label'   => __('Body Font', 'nametheme'),
                                'desc'    => __('Font family for body text and paragraphs.', 'nametheme'),
                                'default' => 'Poppins',
                            ],
                            'base_font_size' => [
                                'type'    => 'range',
                                'label'   => __('Base Font Size', 'nametheme'),
                                'desc'    => __('Base font size for body text in pixels.', 'nametheme'),
                                'min'     => 12,
                                'max'     => 24,
                                'step'    => 1,
                                'default' => 16,
                                'unit'    => 'px',
                            ],
                        ],
                    ],
                    
                ],
            ],
            
            // ================================================================
            // TAB 3: HERO SECTION
            // ================================================================
            'home_settings' => [
                'title' => __('Homepage', 'nametheme'),
                'icon'  => 'bi-image',
                'sections' => [
                    
                    'hero_content' => [
                        'title' => __('Hero Content', 'nametheme'),
                        'desc'  => __('Configure your homepage hero section.', 'nametheme'),
                        'fields' => [
                            'hero_show' => [
                                'type'    => 'switch',
                                'label'   => __('Show/Hide Hero Section', 'nametheme'),
                                'desc'    => __('Enable or disable the hero section on homepage.', 'nametheme'),
                                'default' => true,
                            ],
                            'hero_title' => [
                                'type'        => 'text',
                                'label'       => __('Hero Title', 'nametheme'),
                                'desc'        => __('Main headline for hero section.', 'nametheme'),
                                'placeholder' => __('Welcome to Our Amazing Website', 'nametheme'),
                                'default'     => __('Building Clean and Scalable Websites Design', 'nametheme'),
                            ],
                            'hero_description' => [
                                'type'        => 'textarea',
                                'label'       => __('Hero Description', 'nametheme'),
                                'desc'        => __('Supporting text below the hero title.', 'nametheme'),
                                'placeholder' => __('Enter a compelling description...', 'nametheme'),
                                'rows'        => 4,
                                'default'     => __('We design and develop modern websites focused on performance, usability, and long-term scalability—crafted with clean code and thoughtful design.', 'nametheme'),
                            ],
                            'hero_bg_image' => [
                                'type'  => 'media',
                                'label' => __('Hero Background Image', 'nametheme'),
                                'desc'  => __('Upload background image for hero section (recommended size: 1920x1080px).', 'nametheme'),
                                'default' => [
                                    'id'  => 0,
                                    'url' => get_template_directory_uri() . '/assets/images/hero.jpg',
                                ],
                            ],
                        ],
                    ],
                    'about_content' => [
                        'title' => __('About Content', 'nametheme'),
                        'desc'  => __('Configure your about section.', 'nametheme'),
                        'fields' => [
                            'about_show' => [
                                'type'    => 'switch',
                                'label'   => __('Show/Hide About Section', 'nametheme'),
                                'desc'    => __('Enable or disable the about section.', 'nametheme'),
                                'default' => true,
                            ],
                            'about_title' => [
                                'type'        => 'text',
                                'label'       => __('About Title', 'nametheme'),
                                'desc'        => __('Title for about section.', 'nametheme'),
                                'placeholder' => __('About Us', 'nametheme'),
                                'default'     => __('Who We Are', 'nametheme'),
                            ],
                            'about_description' => [
                                'type'          => 'wysiwyg',
                                'label'         => __('About Description', 'nametheme'),
                                'desc'          => __('Full description for about section with rich text editor.', 'nametheme'),
                                'rows'          => 10,
                                'media_buttons' => true,
                                'default'       => '<p>We are a web development team focused on building clean, efficient, and scalable websites. We prioritize code quality, performance, and user experience to create digital products that are reliable, easy to maintain, and built for long-term growth.</p>',
                            ],
                            'about_image' => [
                                'type'  => 'media',
                                'label' => __('About Image', 'nametheme'),
                                'desc'  => __('Upload image for about section (recommended size: 800x600px).', 'nametheme'),
                                'default' => [
                                    'id'  => 0,
                                    'url' => get_template_directory_uri() . '/assets/images/about.jpg',
                                ],
                            ],
                        ],
                    ],
                    // Blog Layout
                    'blog_layout' => [
                        'title' => __('Blog Layout', 'nametheme'),
                        'desc'  => __('Configure blog and archive page layout.', 'nametheme'),
                        'fields' => [
                            'sidebar_position' => [
                                'type'    => 'select',
                                'label'   => __('Sidebar Position', 'nametheme'),
                                'desc'    => __('Choose sidebar position for blog and archive pages.', 'nametheme'),
                                'options' => [
                                    'left'  => __('Left', 'nametheme'),
                                    'right' => __('Right', 'nametheme'),
                                    'none'  => __('No Sidebar', 'nametheme'),
                                ],
                                'default' => 'right',
                            ],
                        ],
                    ],
                    
                    // Post Settings
                    'post_settings' => [
                        'title' => __('Post Settings', 'nametheme'),
                        'desc'  => __('Configure post display options.', 'nametheme'),
                        'fields' => [
                            'excerpt_length' => [
                                'type'    => 'number',
                                'label'   => __('Excerpt Length', 'nametheme'),
                                'desc'    => __('Number of words to display in post excerpt.', 'nametheme'),
                                'min'     => 10,
                                'max'     => 100,
                                'default' => 25,
                            ],
                            'show_breadcrumbs' => [
                                'type'    => 'switch',
                                'label'   => __('Show Breadcrumbs', 'nametheme'),
                                'desc'    => __('Display breadcrumb navigation on pages and posts.', 'nametheme'),
                                'default' => true,
                            ],
                            'show_share_buttons' => [
                                'type'    => 'switch',
                                'label'   => __('Show Share Buttons', 'nametheme'),
                                'desc'    => __('Display social share buttons on single posts.', 'nametheme'),
                                'default' => true,
                            ],
                        ],
                    ],
                ],
            ],
            
            // ================================================================
            // TAB 4: FOOTER
            // ================================================================
            'footer_settings' => [
                'title' => __('Footer', 'nametheme'),
                'icon'  => 'bi-square',
                'sections' => [
                    
                    // Footer Info
                    'footer_info' => [
                        'title' => __('Footer Information', 'nametheme'),
                        'desc'  => __('Configure footer company info section.', 'nametheme'),
                        'fields' => [
                            'footer_info_show' => [
                                'type'    => 'switch',
                                'label'   => __('Show/Hide Footer Info', 'nametheme'),
                                'desc'    => __('Enable or disable footer information section.', 'nametheme'),
                                'default' => true,
                            ],
                            'footer_logo' => [
                                'type'  => 'media',
                                'label' => __('Footer Logo', 'nametheme'),
                                'desc'  => __('Upload logo for footer (can be different from header logo).', 'nametheme'),
                                'default' => [
                                    'id'  => 0,
                                    'url' => get_template_directory_uri() . '/assets/images/logo-light.png',
                                ],
                            ],
                            'footer_description' => [
                                'type'        => 'textarea',
                                'label'       => __('Footer Description', 'nametheme'),
                                'desc'        => __('Short description or tagline for footer.', 'nametheme'),
                                'placeholder' => __('Your company description...', 'nametheme'),
                                'rows'        => 3,
                                'default'     => __('Building amazing digital experiences since 2024.', 'nametheme'),
                            ],
                        ],
                    ],
                    
                    // Footer Navigation
                    'footer_navigation' => [
                        'title' => __('Footer Navigation', 'nametheme'),
                        'desc'  => __('Configure footer navigation menu.', 'nametheme'),
                        'fields' => [
                            'footer_nav_title' => [
                                'type'        => 'text',
                                'label'       => __('Navigation Title', 'nametheme'),
                                'desc'        => __('Title for navigation column in footer.', 'nametheme'),
                                'placeholder' => __('Quick Links', 'nametheme'),
                                'default'     => __('Quick Links', 'nametheme'),
                            ],
                            'footer_menu' => [
                                'type'  => 'menu_select',
                                'label' => __('Select Footer Menu', 'nametheme'),
                                'desc'  => __('Choose which menu to display in footer.', 'nametheme'),
                                'default' => '',
                            ],
                        ],
                    ],
                    
                    // Footer Featured Posts
                    'footer_posts' => [
                        'title' => __('Footer Featured Posts', 'nametheme'),
                        'desc'  => __('Display recent posts from specific category.', 'nametheme'),
                        'fields' => [
                            'footer_posts_title' => [
                                'type'        => 'text',
                                'label'       => __('Featured Posts Title', 'nametheme'),
                                'desc'        => __('Title for posts column in footer.', 'nametheme'),
                                'placeholder' => __('Recent Posts', 'nametheme'),
                                'default'     => __('Latest News', 'nametheme'),
                            ],
                            'footer_posts_category' => [
                                'type'  => 'category_select',
                                'label' => __('Select Category', 'nametheme'),
                                'desc'  => __('Choose category to display posts from. Leave empty for all categories.', 'nametheme'),
                                'default' => '',
                            ],
                            'footer_posts_max' => [
                                'type'    => 'number',
                                'label'   => __('Maximum Posts', 'nametheme'),
                                'desc'    => __('Number of posts to display in footer.', 'nametheme'),
                                'min'     => 1,
                                'max'     => 10,
                                'default' => 3,
                            ],
                        ],
                    ],
                    
                    // Footer Copyright
                    'footer_copyright' => [
                        'title' => __('Footer Copyright', 'nametheme'),
                        'desc'  => __('Configure copyright text.', 'nametheme'),
                        'fields' => [
                            'footer_copyright_text' => [
                                'type'        => 'text',
                                'label'       => __('Copyright Text', 'nametheme'),
                                'desc'        => __('Copyright text displayed at bottom of footer. Use {year} for current year.', 'nametheme'),
                                'placeholder' => __('© {year} Your Company. All rights reserved.', 'nametheme'),
                                'default'     => __('© {year} MyCompany. All rights reserved.', 'nametheme'),
                            ],
                        ],
                    ],
                    
                ],
            ],
            
            // ================================================================
            // TAB 5: CONTACT INFORMATION
            // ================================================================
            'contact_settings' => [
                'title' => __('Contact', 'nametheme'),
                'icon'  => 'bi-telephone',
                'sections' => [
                    
                    // Contact Info
                    'contact_info' => [
                        'title' => __('Contact Information', 'nametheme'),
                        'desc'  => __('Configure your contact details.', 'nametheme'),
                        'fields' => [
                            'contact_phone' => [
                                'type'        => 'tel',
                                'label'       => __('Phone Number', 'nametheme'),
                                'desc'        => __('Primary contact phone number.', 'nametheme'),
                                'placeholder' => '+1 (123) 456-7890',
                                'default' => '+1 (123) 456-7890',
                            ],
                            'contact_email' => [
                                'type'        => 'email',
                                'label'       => __('Email Address', 'nametheme'),
                                'desc'        => __('Primary contact email address.', 'nametheme'),
                                'placeholder' => 'info@example.com',
                                'default' => 'information@example.com',
                            ],
                        ],
                    ],
                    
                    // Social Media
                    'social_media' => [
                        'title' => __('Social Media Links', 'nametheme'),
                        'desc'  => __('Configure your social media profiles.', 'nametheme'),
                        'fields' => [
                            'social_facebook' => [
                                'type'        => 'url',
                                'label'       => __('Facebook Link', 'nametheme'),
                                'desc'        => __('Your Facebook page URL.', 'nametheme'),
                                'placeholder' => 'https://facebook.com/yourpage',
                                'default' => 'https://facebook.com/yourpage',
                            ],
                            'social_twitter' => [
                                'type'        => 'url',
                                'label'       => __('Twitter X Link', 'nametheme'),
                                'desc'        => __('Your Twitter/X profile URL.', 'nametheme'),
                                'placeholder' => 'https://x.com/yourprofile',
                                'default' => 'https://x.com/yourprofile',
                            ],
                            'social_instagram' => [
                                'type'        => 'url',
                                'label'       => __('Instagram Link', 'nametheme'),
                                'desc'        => __('Your Instagram profile URL.', 'nametheme'),
                                'placeholder' => 'https://instagram.com/yourprofile',
                                'default' => 'https://instagram.com/yourprofile',
                            ],
                        ],
                    ],
                    
                ],
            ],

            // ================================================================
            // TAB 6: ADVANCED SETTINGS
            // ================================================================
            'advanced_settings' => [
                'title' => __('Advanced', 'nametheme'),
                'icon'  => 'bi-gear',
                'sections' => [
                    
                    // Performance
                    'performance' => [
                        'title' => __('Performance', 'nametheme'),
                        'desc'  => __('Optimize theme performance.', 'nametheme'),
                        'fields' => [
                            'disable_emojis' => [
                                'type'    => 'switch',
                                'label'   => __('Disable WordPress Emojis', 'nametheme'),
                                'desc'    => __('Remove emoji scripts to improve performance.', 'nametheme'),
                                'default' => false,
                            ],
                            'enable_gutenberg_styles' => [
                                'type'    => 'switch',
                                'label'   => __('Enable Gutenberg Styles', 'nametheme'),
                                'desc'    => __('Load Gutenberg block editor styles on frontend.', 'nametheme'),
                                'default' => true,
                            ],
                        ],
                    ],
                    
                    // Header Options
                    'header_options' => [
                        'title' => __('Header Options', 'nametheme'),
                        'desc'  => __('Advanced header settings.', 'nametheme'),
                        'fields' => [
                            'sticky_header' => [
                                'type'    => 'switch',
                                'label'   => __('Sticky Header', 'nametheme'),
                                'desc'    => __('Keep header fixed at top when scrolling.', 'nametheme'),
                                'default' => true,
                            ],
                        ],
                    ],
                    
                    // Custom Code
                    'custom_code' => [
                        'title' => __('Custom Code', 'nametheme'),
                        'desc'  => __('Add custom CSS to your theme.', 'nametheme'),
                        'fields' => [
                            'custom_css' => [
                                'type'        => 'code',
                                'label'       => __('Custom CSS', 'nametheme'),
                                'desc'        => __('Add custom CSS code here. Will be applied to all pages.', 'nametheme'),
                                'mode'        => 'css',
                                'rows'        => 15,
                                'default'     => '',
                            ],
                        ],
                    ],
                    
                ],
            ],
        ];
    }
}