<?php
/**
 * Settings Configuration - Organized by Field Types
 * 
 * This configuration demonstrates all available field types
 * organized by categories for easy reference and customization.
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
     * 
     * Field Types Available:
     * 
     * BASIC INPUTS:
     * - text          : Single line text
     * - textarea      : Multi-line text
     * - wysiwyg       : Rich text editor
     * - number        : Numeric input with min/max
     * - url           : URL with validation
     * - email         : Email with validation
     * - tel           : Phone number
     * - shortcode     : Shortcode input
     * 
     * TOGGLE & CHOICE:
     * - switch        : On/off toggle
     * - checkbox      : Single checkbox
     * - checkbox_group: Multiple checkboxes
     * - radio         : Radio buttons
     * - select        : Dropdown select
     * - multiselect   : Multi-select dropdown
     * 
     * VISUAL FIELDS:
     * - color         : Color picker
     * - gradient      : Gradient builder
     * - media         : Image/video upload
     * - file          : File upload (PDF, etc.) (PRO)
     * - image_select  : Visual layout selector (PRO)
     * - range         : Range slider
     * - typography    : Font selector
     * - icon          : Icon picker
     * 
     * WORDPRESS RELATIONS:
     * - page_select     : Select from pages
     * - post_select     : Select from posts
     * - category_select : Select from categories
     * - menu_select     : Select from menus
     * 
     * COMPLEX FIELDS (PRO):
     * - repeater      : Dynamic list builder
     * - date          : Date picker
     * - time          : Time picker
     * - datetime      : Date & time picker
     */
    public static function get_fields() {
        
        return [
            
            // ================================================================
            // TAB 1: BASIC INPUTS
            // Text, Textarea, WYSIWYG, Number, URL, Email, Tel, Shortcode
            // ================================================================
            'basic_inputs' => [
                'title' => __('Basic Inputs', 'nametheme'),
                'icon'  => 'bi-input-cursor-text',
                'sections' => [
                    
                    // ------------------------------------------
                    // Section: 10 Basic Input Types
                    // ------------------------------------------
                    'text_fields' => [
                        'title' => __('10 Basic Input Types', 'nametheme'),
                        'desc'  => __('Text inputs, textarea, texteditor, input number, input url, input email, input telepon, input shortcode plugin', 'nametheme'),
                        'fields' => [
                            'site_title' => [
                                'type'        => 'text',
                                'label'       => __('Site Title', 'nametheme'),
                                'desc'        => __('Your website name displayed in header.', 'nametheme'),
                                'placeholder' => __('My Awesome Website', 'nametheme'),
                                'default'     => '',
                            ],
                            'hero_description' => [
                                'type'        => 'textarea',
                                'label'       => __('Hero Description', 'nametheme'),
                                'placeholder' => __('Enter a compelling description for your hero section...', 'nametheme'),
                                'rows'        => 3,
                            ],
                            'about_content' => [
                                'type'          => 'wysiwyg',
                                'label'         => __('About Content', 'nametheme'),
                                'desc'          => __('Full content for the about section.', 'nametheme'),
                                'rows'          => 10,
                                'media_buttons' => true,
                            ],
                            'related_posts_count' => [
                                'type'    => 'number',
                                'label'   => __('Related Posts Count', 'nametheme'),
                                'min'     => 2,
                                'max'     => 8,
                                'default' => 3,
                            ],
                            'website_url' => [
                                'type'        => 'url',
                                'label'       => __('Website URL', 'nametheme'),
                                'placeholder' => 'https://example.com',
                            ],
                            'contact_email' => [
                                'type'        => 'email',
                                'label'       => __('Contact Email', 'nametheme'),
                                'placeholder' => 'info@example.com',
                                'desc'        => __('Primary contact email address.', 'nametheme'),
                            ],
                            'contact_phone' => [
                                'type'        => 'tel',
                                'label'       => __('Phone Number', 'nametheme'),
                                'placeholder' => '+1 (123) 456-7890',
                            ],
                            'map_shortcode' => [
                                'type'        => 'shortcode',
                                'label'       => __('Map Shortcode', 'nametheme'),
                                'placeholder' => '[google_map id="1"]',
                            ],
                            // Password Field
                            'api_key' => [
                                'type'        => 'password',
                                'label'       => __('Input Password', 'nametheme'),
                                'placeholder' => 'sk-xxxxxxxxxxxxxxxx',
                                'desc'        => __('Enter your Password', 'nametheme'),
                            ],

                            // Code Field
                            'custom_css' => [
                                'type'        => 'code',
                                'label'       => __('Custom CSS', 'nametheme'),
                                'language'    => 'css',
                                'rows'        => 15,
                                'placeholder' => '.my-class { color: #333; }',
                                'desc'        => __('Add custom CSS code', 'nametheme'),
                            ],
                        ],
                    ],
                ],
            ],
            
            // ================================================================
            // TAB 2: TOGGLE & CHOICE
            // Switch, Checkbox, Checkbox Group, Radio, Select, Multiselect
            // ================================================================
            'toggle_choice' => [
                'title' => __('Toggle & Choice', 'nametheme'),
                'icon'  => 'bi-toggles',
                'sections' => [
                    
                    // ------------------------------------------
                    // Section: 6 Switch / Toggle Types
                    // ------------------------------------------
                    'switch_fields' => [
                        'title' => __('6 Switch / Toggle Types', 'nametheme'),
                        'desc'  => __('Switch, checkbox, checkbox_group, radio, select, multiselect.', 'nametheme'),
                        'fields' => [
                            'enable_topbar' => [
                                'type'         => 'switch',
                                'label'        => __('Enable Top Bar', 'nametheme'),
                                'switch_label' => __('Show announcement bar above header', 'nametheme'),
                                'default'      => true,
                            ],
                            'show_post_date' => [
                                'type'           => 'checkbox',
                                'label'          => __('Post Meta', 'nametheme'),
                                'checkbox_label' => __('Show post date', 'nametheme'),
                                'default'        => true,
                            ],
                            'share_buttons' => [
                                'type'    => 'checkbox_group',
                                'label'   => __('Share Buttons', 'nametheme'),
                                'desc'    => __('Select which share buttons to display on posts.', 'nametheme'),
                                'options' => [
                                    'facebook'  => __('Facebook', 'nametheme'),
                                    'twitter'   => __('X (Twitter)', 'nametheme'),
                                    'linkedin'  => __('LinkedIn', 'nametheme'),
                                    'pinterest' => __('Pinterest', 'nametheme'),
                                    'whatsapp'  => __('WhatsApp', 'nametheme'),
                                    'telegram'  => __('Telegram', 'nametheme'),
                                    'email'     => __('Email', 'nametheme'),
                                ],
                                'default' => ['facebook', 'twitter', 'linkedin'],
                            ],
                            'sidebar_position' => [
                                'type'    => 'radio',
                                'label'   => __('Sidebar Position', 'nametheme'),
                                'options' => [
                                    'left'  => __('Left', 'nametheme'),
                                    'right' => __('Right', 'nametheme'),
                                    'none'  => __('No Sidebar', 'nametheme'),
                                ],
                                'default' => 'right',
                            ],
                            'header_style' => [
                                'type'        => 'select',
                                'label'       => __('Header Style', 'nametheme'),
                                'placeholder' => __('Choose a header style', 'nametheme'),
                                'options'     => [
                                    'default'     => __('Default', 'nametheme'),
                                    'centered'    => __('Centered Logo', 'nametheme'),
                                    'transparent' => __('Transparent', 'nametheme'),
                                    'minimal'     => __('Minimal', 'nametheme'),
                                    'mega_menu'   => __('Mega Menu', 'nametheme'),
                                ],
                                'default' => 'default',
                            ],
                            'display_categories' => [
                                'type'    => 'multiselect',
                                'label'   => __('Display Categories', 'nametheme'),
                                'desc'    => __('Select which categories to show on homepage. Hold Ctrl/Cmd to select multiple.', 'nametheme'),
                                'options' => [
                                    'news'       => __('News', 'nametheme'),
                                    'tutorials'  => __('Tutorials', 'nametheme'),
                                    'reviews'    => __('Reviews', 'nametheme'),
                                    'technology' => __('Technology', 'nametheme'),
                                    'lifestyle'  => __('Lifestyle', 'nametheme'),
                                    'travel'     => __('Travel', 'nametheme'),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            
            // ================================================================
            // TAB 3: VISUAL FIELDS 
            // Color, Media, Range, Typography, Icon
            // ================================================================
            'visual_fields' => [
                'title' => __('Visual Fields', 'nametheme'),
                'icon'  => 'bi-palette',
                'sections' => [
                    
                    // ------------------------------------------
                    // Section: Color Picker
                    // ------------------------------------------
                    'color_fields' => [
                        'title' => __('Color Picker', 'nametheme'),
                        'desc'  => __('Color selection with optional alpha (transparency) support.', 'nametheme'),
                        'fields' => [
                            'primary_color' => [
                                'type'    => 'color',
                                'label'   => __('Primary Color', 'nametheme'),
                                'desc'    => __('Main brand color used for buttons, links, etc.', 'nametheme'),
                                'default' => '#2563eb',
                            ],
                            'secondary_color' => [
                                'type'    => 'color',
                                'label'   => __('Secondary Color', 'nametheme'),
                                'default' => '#64748b',
                            ],
                            'accent_color' => [
                                'type'    => 'color',
                                'label'   => __('Accent Color', 'nametheme'),
                                'desc'    => __('Highlight color for special elements.', 'nametheme'),
                                'default' => '#f59e0b',
                            ],
                        ],
                    ],

                    // ------------------------------------------
                    // Section: Media Upload
                    // ------------------------------------------
                    'media_fields' => [
                        'title' => __('Media Upload', 'nametheme'),
                        'desc'  => __('Upload images, videos, and other media from WordPress library.', 'nametheme'),
                        'fields' => [
                            'site_logo' => [
                                'type'  => 'media',
                                'label' => __('Site Logo', 'nametheme'),
                                'desc'  => __('Upload your logo. Recommended: PNG with transparency, 200x60px.', 'nametheme'),
                            ],
                            'favicon' => [
                                'type'  => 'media',
                                'label' => __('Favicon', 'nametheme'),
                                'desc'  => __('Site icon. Recommended: PNG, 32x32px or 512x512px.', 'nametheme'),
                            ],
                        ],
                    ],

                    // ------------------------------------------
                    // Section: Range Slider
                    // ------------------------------------------
                    'range_fields' => [
                        'title' => __('Range Slider', 'nametheme'),
                        'desc'  => __('Slider controls for numeric values with visual feedback.', 'nametheme'),
                        'fields' => [
                            'base_font_size' => [
                                'type'    => 'range',
                                'label'   => __('Base Font Size', 'nametheme'),
                                'min'     => 12,
                                'max'     => 24,
                                'step'    => 1,
                                'unit'    => 'px',
                                'default' => 16,
                            ],
                            'heading_scale' => [
                                'type'    => 'range',
                                'label'   => __('Heading Scale', 'nametheme'),
                                'desc'    => __('Size multiplier for headings.', 'nametheme'),
                                'min'     => 1,
                                'max'     => 2,
                                'step'    => 0.1,
                                'unit'    => 'x',
                                'default' => 1.25,
                            ],
                        ],
                    ],
                    
                    // ------------------------------------------
                    // Section: Typography
                    // ------------------------------------------
                    'typography_fields' => [
                        'title' => __('Typography', 'nametheme'),
                        'desc'  => __('Font selection with Google Fonts support.', 'nametheme'),
                        'fields' => [
                            'body_font' => [
                                'type'    => 'typography',
                                'label'   => __('Body Font', 'nametheme'),
                                'desc'    => __('Font for paragraph and general text.', 'nametheme'),
                                'default' => 'Open Sans',
                            ],
                            'heading_font' => [
                                'type'    => 'typography',
                                'label'   => __('Heading Font', 'nametheme'),
                                'desc'    => __('Font for H1-H6 headings.', 'nametheme'),
                                'default' => 'Montserrat',
                            ],
                        ],
                    ],
                    
                    // ------------------------------------------
                    // Section: Icon Picker
                    // ------------------------------------------
                    'icon_fields' => [
                        'title' => __('Icon Picker', 'nametheme'),
                        'desc'  => __('Select icons from Bootstrap Icons library.', 'nametheme'),
                        'fields' => [
                            'feature_icon_1' => [
                                'type'    => 'icon',
                                'label'   => __('Feature Icon 1', 'nametheme'),
                                'default' => 'bi-lightning',
                            ],
                            'feature_icon_2' => [
                                'type'    => 'icon',
                                'label'   => __('Feature Icon 2', 'nametheme'),
                                'default' => 'bi-shield-check',
                            ],
                        ],
                    ],
                ],
            ],
            
            // ================================================================
            // TAB 4: WORDPRESS RELATIONS
            // Page Select, Post Select, Category Select, Menu Select
            // ================================================================
            'wordpress_relations' => [
                'title' => __('WordPress', 'nametheme'),
                'icon'  => 'bi-wordpress',
                'sections' => [
                    
                    // ------------------------------------------
                    // Section: Page Select
                    // ------------------------------------------
                    'page_select_fields' => [
                        'title' => __('Page Select', 'nametheme'),
                        'desc'  => __('Select from published WordPress pages.', 'nametheme'),
                        'fields' => [
                            'about_page' => [
                                'type'  => 'page_select',
                                'label' => __('Booking Page', 'nametheme'),
                                'desc'  => __('Select the Booking page.', 'nametheme'),
                            ],
                            'contact_page' => [
                                'type'  => 'page_select',
                                'label' => __('Contact Page', 'nametheme'),
                            ],
                        ],
                    ],
                    
                    // ------------------------------------------
                    // Section: Post Select
                    // ------------------------------------------
                    'post_select_fields' => [
                        'title' => __('Post Select', 'nametheme'),
                        'desc'  => __('Select specific posts to feature.', 'nametheme'),
                        'fields' => [
                            'featured_post' => [
                                'type'  => 'post_select',
                                'label' => __('Featured Post', 'nametheme'),
                                'desc'  => __('Select a post to feature on homepage.', 'nametheme'),
                            ],
                        ],
                    ],
                    
                    // ------------------------------------------
                    // Section: Category Select
                    // ------------------------------------------
                    'category_select_fields' => [
                        'title' => __('Category Select', 'nametheme'),
                        'desc'  => __('Select categories to display or filter content.', 'nametheme'),
                        'fields' => [
                            'featured_category' => [
                                'type'  => 'category_select',
                                'label' => __('Featured Category', 'nametheme'),
                                'desc'  => __('Category to feature on homepage.', 'nametheme'),
                            ],
                            'news_category' => [
                                'type'  => 'category_select',
                                'label' => __('News Category', 'nametheme'),
                            ],
                        ],
                    ],
                    
                    // ------------------------------------------
                    // Section: Menu Select
                    // ------------------------------------------
                    'menu_select_fields' => [
                        'title' => __('Menu Select', 'nametheme'),
                        'desc'  => __('Select WordPress navigation menus.', 'nametheme'),
                        'fields' => [
                            'primary_menu' => [
                                'type'  => 'menu_select',
                                'label' => __('Primary Menu', 'nametheme'),
                                'desc'  => __('Main navigation menu in header.', 'nametheme'),
                            ],
                            'footer_menu' => [
                                'type'  => 'menu_select',
                                'label' => __('Footer Menu', 'nametheme'),
                            ],
                        ],
                    ],
                ],
            ],
            
            // ================================================================
            // TAB 5: DATE & TIME
            // Date, Time, DateTime pickers
            // ================================================================
            'datetime_fields' => [
                'title' => __('Date & Time', 'nametheme'),
                'icon'  => 'bi-calendar3',
                'sections' => [
                    
                    // ------------------------------------------
                    // Section: Date & Time Pickers
                    // ------------------------------------------
                    'datetime_pickers' => [
                        'title' => __('Date & Time Pickers', 'nametheme'),
                        'desc'  => __('Input fields for dates, times, and datetime values.', 'nametheme'),
                        'fields' => [
                            'event_date' => [
                                'type'  => 'date',
                                'label' => __('Select Date', 'nametheme'),
                                'desc'  => __('Select a date for the event.', 'nametheme'),
                            ],
                            'opening_time' => [
                                'type'  => 'time',
                                'label' => __('Time', 'nametheme'),
                            ],
                            'countdown_datetime' => [
                                'type'  => 'datetime',
                                'label' => __('Date & Time', 'nametheme'),
                                'desc'  => __('Set the target date and time for countdown.', 'nametheme'),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}