<?php
/**
 * Theme Settings - Main Entry Point
 * 
 * Professional WordPress Theme Settings Framework
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('THEME_SETTINGS_SIDEBAR', 'MyBrand Settings'); // Sidebar Menu
define('THEME_SETTINGS_BRAND_URL', 'mybrand'); // URL Admin admin.php?page=mybrand-settings
define('THEME_SETTINGS_BRAND', 'MyBrand Settings'); // Title (logo image replace from assets/images/logo.png)
define('THEME_SETTINGS_AUTHOR', 'Theme Name'); // Footer Name
define('THEME_SETTINGS_LINK', 'https://themesettings.com'); // Replace with Your Link (PRO)
define('THEME_SETTINGS_SKIN', 'light'); // Options: light, dark
define('THEME_SETTINGS_VERSION', '1.1.0'); // Theme Version
define('THEME_SETTINGS_PATH', get_template_directory() . '/themesettings/');
define('THEME_SETTINGS_URL', get_template_directory_uri() . '/themesettings/');
define('THEME_SETTINGS_THEME_CSS', THEME_SETTINGS_URL . 'assets/css/theme-settings.css'); // theme-settings-horizontal.css (for horizontal navbar)
define('THEME_SETTINGS_BOOTSTRAP_ICONS', true); // true = enable, false = disable (bootstrap icon cdn)

/**
 * Theme Settings Main Class
 */
class TS_Settings {
    
    /**
     * Instance
     */
    private static $instance = null;
    
    /**
     * Settings tabs
     */
    private $tabs = [];
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Load required files
     */
    private function load_dependencies() {
        // Core includes
        require_once THEME_SETTINGS_PATH . 'includes/class-field-sanitizer.php';
        require_once THEME_SETTINGS_PATH . 'includes/class-field-renderer.php';
        require_once THEME_SETTINGS_PATH . 'includes/class-settings-api.php';
        require_once THEME_SETTINGS_PATH . 'includes/class-media-handler.php';
        require_once THEME_SETTINGS_PATH . 'includes/class-defaults-handler.php';
        
        // Field types
        require_once THEME_SETTINGS_PATH . 'fields/basic-fields.php';
        require_once THEME_SETTINGS_PATH . 'fields/toggle-fields.php';
        require_once THEME_SETTINGS_PATH . 'fields/visual-fields.php';
        require_once THEME_SETTINGS_PATH . 'fields/wordpress-fields.php';

        // front
        require_once THEME_SETTINGS_PATH . 'includes/front-enqueue.php';
        
        // Settings configuration
        require_once THEME_SETTINGS_PATH . 'settings-config.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_ts_media_upload', [$this, 'handle_media_upload']);
        add_action('wp_ajax_ts_get_posts', [$this, 'ajax_get_posts']);
        add_action('wp_ajax_ts_get_pages', [$this, 'ajax_get_pages']);

        add_action('wp_ajax_ts_reset_settings', [$this, 'ajax_reset_settings']);

        add_filter('admin_body_class', [$this, 'add_admin_body_class']);
    }

    /**
     * Add theme skin class to admin body
     */
    public function add_admin_body_class($classes) {
        $screen = get_current_screen();
        
        // Only on our settings page
        if ($screen && $screen->id === 'toplevel_page_' . THEME_SETTINGS_BRAND_URL . '-settings') {
            $skin = defined('THEME_SETTINGS_SKIN') ? THEME_SETTINGS_SKIN : 'light';
            $classes .= ' theme-' . $skin;
        }
        
        return $classes;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            THEME_SETTINGS_SIDEBAR,
            THEME_SETTINGS_SIDEBAR,
            'manage_options',
            THEME_SETTINGS_BRAND_URL . '-settings',
            [$this, 'render_settings_page'],
            'dashicons-admin-generic',
            60
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'ts_settings_group',
            'ts_options',
            [
                'sanitize_callback' => [TS_Field_Sanitizer::class, 'sanitize_all'],
                'default' => []
            ]
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_' . THEME_SETTINGS_BRAND_URL . '-settings') {
            return;
        }
        
        // WordPress media uploader
        wp_enqueue_media();
        
        // WordPress color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // WordPress editor
        wp_enqueue_editor();
        
        // Bootstrap Icons
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
            [],
            '1.11.3'
        );
        
        // Google Fonts API for typography
        wp_enqueue_script(
            'webfont-loader',
            'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js',
            [],
            '1.6.26',
            true
        );
        
        // Theme Settings CSS
        wp_enqueue_style(
            THEME_SETTINGS_BRAND_URL . '-settings',
            THEME_SETTINGS_THEME_CSS,
            [],
            THEME_SETTINGS_VERSION
        );
        
        // Theme Settings JS
       wp_enqueue_script(
            THEME_SETTINGS_BRAND_URL . '-settings',
            THEME_SETTINGS_URL . 'assets/js/theme-settings.js',
            ['jquery', 'wp-color-picker', 'jquery-ui-sortable', 'jquery-ui-datepicker'],
            THEME_SETTINGS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(THEME_SETTINGS_BRAND_URL . '-settings', 'TSSettings', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ts_settings_nonce'),
            'mediaTitle' => __('Select or Upload Media', 'nametheme'),
            'mediaButton' => __('Use this media', 'nametheme'),
            'confirmDelete' => __('Are you sure you want to delete this item?', 'nametheme'),
            'googleFontsApiKey' => '', // Add your API key here if needed
            'themeSkin' => defined('THEME_SETTINGS_SKIN') ? THEME_SETTINGS_SKIN : 'light',
        ]);
    }

    /**
     * AJAX reset theme settings
     */
    public function ajax_reset_settings() {

        // Security
        check_ajax_referer('ts_settings_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied', 403);
        }

        // Delete options
        delete_option('ts_options');

        wp_send_json_success();
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        include THEME_SETTINGS_PATH . 'views/settings-page.php';
    }
    
    /**
     * Handle media upload AJAX
     */
    public function handle_media_upload() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error('Permission denied');
        }
        
        $media_handler = new TS_Media_Handler();
        $result = $media_handler->process_upload();
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * AJAX get posts
     */
    public function ajax_get_posts() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        $args = [
            'post_type' => $post_type,
            'posts_per_page' => 20,
            'post_status' => 'publish',
        ];
        
        if ($search) {
            $args['s'] = $search;
        }
        
        $posts = get_posts($args);
        $results = [];
        
        foreach ($posts as $post) {
            $results[] = [
                'id' => $post->ID,
                'title' => $post->post_title
            ];
        }
        
        wp_send_json_success($results);
    }
    
    /**
     * AJAX get pages
     */
    public function ajax_get_pages() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        $pages = get_pages(['post_status' => 'publish']);
        $results = [];
        
        foreach ($pages as $page) {
            $results[] = [
                'id' => $page->ID,
                'title' => $page->post_title
            ];
        }
        
        wp_send_json_success($results);
    }
}

/**
 * Initialize Theme Settings
 */
function ts_settings_init() {
    return TS_Settings::get_instance();
}
add_action('after_setup_theme', 'ts_settings_init');

/**
 * Helper function to get theme option
 * 
 * @param string $key Option key
 * @param mixed $default Default value
 * @return mixed Option value
 */
function ts_get_option($key, $default = null) {
    $options = get_option('ts_options', []);
    
    if (isset($options[$key]) && $options[$key] !== '') {
        return $options[$key];
    }
    
    if ($default !== null) {
        return $default;
    }
    
    return TS_Defaults_Handler::get_default($key);
}