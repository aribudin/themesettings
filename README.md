# Theme Settings

A modern, lightweight WordPress theme settings framework that makes it easy to create professional theme options with various field types. Features a beautiful admin interface with vertical and horizontal navbar layout.

<img src="preview/tailmater-template.png" alt="Theme Settings">

## ✨ Features

- 🎨 **20+ Field Types** - Text, Textarea, WYSIWYG, Number, Color Picker, Media Upload, Date picker and more
- 🌐 **Vertical/Horizontal Layout** - Beautiful interface with Vertical & Horizontal Layout
- ⚡ **Professional UI** - Professional UI Theme Options
- 📱 **Responsive Design** - Mobile-friendly admin interface
- 🎯 **Easy Integration** - Simple setup with just one include
- 🔧 **Developer Friendly** - Clean code structure and extensive documentation
- 📦 **Import/Export** - Backup and transfer settings between sites
- 🎭 **Bootstrap Icons** - 1,800+ icons built-in

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## 🚀 Installation

### 1. Upload to Your Theme

Extract the `themesettings` folder to your active WordPress theme directory:

```
wp-content/themes/your-theme/themesettings/
```

### 2. Include in functions.php

Add this line to your theme's `functions.php` file:

```php
require_once get_template_directory() . '/themesettings/theme-settings.php';
```

### 3. Configure Branding (Optional)

Edit the constants in `theme-settings.php` to customize branding:

```php
define('THEME_SETTINGS_SIDEBAR', 'MyBrand Settings'); // Sidebar Menu
define('THEME_SETTINGS_BRAND_URL', 'mybrand'); // URL Admin admin.php?page=mybrand-settings
define('THEME_SETTINGS_BRAND', 'MyBrand Settings'); // Title (logo image replace from assets/images/logo.png)
define('THEME_SETTINGS_AUTHOR', 'Theme Name'); // Footer Theme Name
define('THEME_SETTINGS_VERSION', '1.1.0'); // Theme Version
define('THEME_SETTINGS_THEME_CSS', THEME_SETTINGS_URL . 'assets/css/theme-settings.css'); // theme-settings-horizontal.css (for horizontal navbar)
define('THEME_SETTINGS_BOOTSTRAP_ICONS', true); // true = enable, false = disable (bootstrap icon cdn)
```

### 4. Configure Fields

Edit `settings-config.php` to define your settings fields. See the configuration examples below.

## 📁 Folder Structure

```
themesettings/
├── assets/                    # CSS, JS, and images
│   ├── css/
│   │   ├── theme-settings.css            # Vertical sidebar theme
│   │   ├── theme-settings-horizontal.css # Horizontal navbar theme
│   │   └── tinymce-dark.css             # Dark mode editor styles
│   ├── images/                           # Icons and logos
│   └── js/
│       └── theme-settings.js             # Main JavaScript
├── fields/                    # Field type definitions
│   ├── basic-fields.php       # Text, textarea, wysiwyg, etc.
│   ├── toggle-fields.php      # Switch, checkbox, radio, select
│   ├── visual-fields.php      # Color, media, range, typography
│   └── wordpress-fields.php   # Page, post, category selectors
├── includes/                  # Core classes
│   ├── front-enqueue.php              # Add Bootstrap Icon to theme
│   ├── class-settings-api.php         # Main API
│   ├── class-field-renderer.php       # Field to HTML renderer
│   ├── class-field-sanitizer.php      # Validation and sanitization
│   ├── class-media-handler.php        # Media upload handler
│   └── class-defaults-handler.php     # Default values handler
├── views/
│   └── settings-page.php      # Settings page template
├── settings-config.php        # Field configuration (EDIT THIS)
└── theme-settings.php         # Main initialization file
```

## ⚙️ Configuration

### Field Structure

Edit `settings-config.php` with this array structure:

```php
return [
    'general' => [
        'title' => 'General Settings',
        'icon'  => 'bi-gear', // Bootstrap Icons
        'sections' => [
            'site_identity' => [
                'title'  => 'Site Identity',
                'desc'   => 'Configure your site branding',
                'fields' => [
                    'site_title' => [
                        'type'        => 'text',
                        'label'       => 'Site Title',
                        'placeholder' => 'My Website',
                        'default'     => '',
                    ],
                    'site_logo' => [
                        'type'  => 'media',
                        'label' => 'Site Logo',
                        'desc'  => 'Recommended: PNG, 200x60px',
                    ],
                ],
            ],
        ],
    ],
];
```

## 🎨 Available Field Types

### Basic Input Fields

```php
// Text Input
'site_title' => [
    'type'        => 'text',
    'label'       => 'Site Title',
    'placeholder' => 'My Website',
    'default'     => 'My Website',
]

// Textarea
'description' => [
    'type'  => 'textarea',
    'label' => 'Description',
    'rows'  => 5,
    'default'     => 'My Awesome Website',
]

// WYSIWYG Editor
'content' => [
    'type'   => 'wysiwyg',
    'label'  => 'Content',
    'height' => 200,
    'default'     => '<p>My Awesome Website</p>',
]

// Number
'max_posts' => [
    'type'  => 'number',
    'label' => 'Maximum Posts',
    'min'   => 1,
    'max'   => 100,
    'default'     => 3,
]

// URL
'website_url' => [
    'type'  => 'url',
    'label' => 'Website URL',
    'default'     => 'https://yoursite.com',
]

// Email
'admin_email' => [
    'type'  => 'email',
    'label' => 'Admin Email',
    'default'     => 'support@yoursite.com',
]
```

### Toggle & Choice Fields

```php
// Switch (On/Off)
'enable_feature' => [
    'type'    => 'switch',
    'label'   => 'Enable Feature',
    'default' => true,
]

// Checkbox
'accept_terms' => [
    'type'  => 'checkbox',
    'label' => 'I accept the terms',
    'default' => false,
]

// Radio Buttons
'sidebar_position' => [
    'type'    => 'radio',
    'label'   => 'Sidebar Position',
    'options' => [
        'left'  => 'Left',
        'right' => 'Right',
    ],
]

// Select Dropdown
'header_style' => [
    'type'    => 'select',
    'label'   => 'Header Style',
    'options' => [
        'default'  => 'Default',
        'centered' => 'Centered',
    ],
]

// Multiselect
'categories' => [
    'type'    => 'multiselect',
    'label'   => 'Categories',
    'options' => [
        'news' => 'News',
        'blog' => 'Blog',
    ],
]
```

### Visual Fields

```php
// Color Picker
'primary_color' => [
    'type'    => 'color',
    'label'   => 'Primary Color',
    'default' => '#2563eb',
]

// Media Upload
'site_logo' => [
    'type'  => 'media',
    'label' => 'Site Logo',
    'default' => [
        'id'  => 0,
        'url' => get_template_directory_uri() . '/images/logo.png',
    ],
]

// Range Slider
'font_size' => [
    'type'    => 'range',
    'label'   => 'Font Size',
    'min'     => 12,
    'max'     => 24,
    'unit'    => 'px',
    'default' => 16,
]

// Typography (Google Fonts)
'body_font' => [
    'type'  => 'typography',
    'label' => 'Body Font',
    'default' => 'Open Sans',
]

// Icon Picker
'icon' => [
    'type'  => 'icon',
    'label' => 'Icon',
    'default' => 'bi-layers',
]
```

### WordPress Integration Fields

```php
// Page Selector
'contact_page' => [
    'type'  => 'page_select',
    'label' => 'Contact Page',
    'default' => '',
]

// Post Selector
'featured_post' => [
    'type'  => 'post_select',
    'label' => 'Featured Post',
    'default' => '',
]

// Category Selector
'default_category' => [
    'type'  => 'category_select',
    'label' => 'Default Category',
    'default' => '',
]

// Menu Selector
'primary_menu' => [
    'type'  => 'menu_select',
    'label' => 'Primary Menu',
    'default' => '',
]
```

## 🔧 Usage in Templates

### The `ts_get_option()` Function

Retrieve setting values anywhere in your theme:

```php
/**
 * Get option value
 * 
 * @param string $key     Field name
 * @param mixed  $default Default value if empty
 * @return mixed
 */
ts_get_option($key, $default = null)
```

### Usage Examples

#### 1. Display Text Field

```php
$site_title = ts_get_option('site_title', 'Default Title');
echo '<h1>' . esc_html($site_title) . '</h1>';
```

#### 2. Conditional Display (Switch/Checkbox)

```php
if (ts_get_option('enable_breadcrumbs', false)) {
    // Display breadcrumb
    echo '<nav class="breadcrumb">...</nav>';
}
```

#### 3. Apply Color Settings

```php
$primary_color = ts_get_option('primary_color', '#2563eb');
?>
<style>
:root {
    --primary-color: <?php echo esc_attr($primary_color); ?>;
}
</style>
```

#### 4. Display Logo Image

```php
$logo = ts_get_option('site_logo');
if (is_array($logo) && isset($logo['url'])) {
    echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo">';
}
```

#### 5. Use Select/Radio Value

```php
$sidebar = ts_get_option('sidebar_position', 'right');
$class = ($sidebar === 'left') ? 'sidebar-left' : 'sidebar-right';
?>
<div class="<?php echo esc_attr($class); ?>">
    <!-- Content -->
</div>
```

#### 6. Loop Through Multiselect

```php
$categories = ts_get_option('display_categories', []);
if (!empty($categories)) {
    $args = [
        'category_name' => implode(',', $categories),
        'posts_per_page' => 10,
    ];
    $query = new WP_Query($args);
    // Display posts...
}
```

#### 7. Output WYSIWYG Content

```php
$content = ts_get_option('about_content', '');
echo wp_kses_post($content); // Sanitize HTML output
```

#### 8. Use Page/Post ID

```php
$contact_page = ts_get_option('contact_page');
if ($contact_page) {
    $url = get_permalink($contact_page);
    echo '<a href="' . esc_url($url) . '">Contact Us</a>';
}
```

### Complete Example: Dynamic Header

```php
<?php
// Get settings
$logo = ts_get_option('site_logo');
$site_title = ts_get_option('site_title', get_bloginfo('name'));
$primary_color = ts_get_option('primary_color', '#2563eb');
$header_style = ts_get_option('header_style', 'default');
$enable_search = ts_get_option('enable_search', true);
?>

<style>
:root {
    --primary-color: <?php echo esc_attr($primary_color); ?>;
}
</style>

<header class="site-header header-<?php echo esc_attr($header_style); ?>">
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="site-branding">
                <?php if ($logo): ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($logo); ?>" 
                             alt="<?php echo esc_attr($site_title); ?>">
                    </a>
                <?php else: ?>
                    <h1><?php echo esc_html($site_title); ?></h1>
                <?php endif; ?>
            </div>

            <!-- Navigation -->
            <nav class="main-navigation">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                ]);
                ?>
            </nav>

            <!-- Search -->
            <?php if ($enable_search): ?>
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
```

## 🛠️ Advanced Features

- **Import/Export** - Transfer settings between sites as JSON
- **Reset Settings** - Restore all defaults with one click
- **Media Library** - Native WordPress media integration
- **Google Fonts** - Built-in Google Fonts API integration
- **Responsive UI** - Works perfectly on mobile devices
- **2 Layout** - Vertical and Horizontal Layout

## 🎯 FREE STARTER WORDPRESS THEME

To simplify the development process, WordPress starter themes are available. These themes include basic examples of using ThemeSettings within a WordPress theme.

- 

## 📚 Documentation

For complete documentation, advanced features, and detailed guides, visit:

**[https://themesettings.com](https://themesettings.com)**

## 🚀 Pro Version

Upgrade to **Theme Settings Pro** for advanced features:

### 🎯 Pro Features

- **🔄 Advanced Repeater Field** - Create dynamic content with 12+ pre-built templates
- **🌓 Dark/Light Mode** - Beautiful interface with automatic theme switching
- **♾️ Unlimited Repeater Combinations** - Mix any field types in repeater rows
- **🎨 Color Palette** - Pre-defined color schemes for consistent branding
- **🌈 Gradient Builder** - Visual gradient creator with live preview
- **📁 File Upload** - Support for PDF, DOC, ZIP and other file types
- **🖼️ Image/Layout Selector** - Visual layout picker with thumbnails
- **📋 List Sorter** - Drag-and-drop ordering for section, widgets, and more
- **⚡ Priority Support** - Direct assistance from developers
- **🔄 Lifetime Updates** - Get all future updates included

**[Learn more about Pro →](https://themesettings.com/pro)**

## 📄 License

This project is licensed under the GPL-2.0+ License.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 💬 Support

- **Documentation:** [https://themesettings.com](https://themesettings.com)
- **Issues:** [GitHub Issues](https://github.com/aribudin/themesettings/issues)

---

Made with ❤️ for WordPress Developers
