<?php
/**
 * Theme Settings - Default Values Handler
 * 
 * Handles registration and retrieval of default option values.
 * Automatically populates defaults on theme activation.
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Defaults_Handler {
    
    /**
     * Option name in database
     */
    const OPTION_NAME = 'ts_options';
    
    /**
     * Flag option name
     */
    const DEFAULTS_FLAG = 'ts_defaults_registered';
    
    /**
     * Defaults cache
     */
    private static $defaults = null;
    
    /**
     * Initialize defaults handler
     */
    public static function init() {
        // Register defaults on theme activation
        add_action('after_switch_theme', [__CLASS__, 'register_defaults']);
        
        // Also check on admin init (in case theme was already active)
        add_action('admin_init', [__CLASS__, 'maybe_register_defaults']);
        
        // Register AJAX handlers
        add_action('wp_ajax_ts_reset_settings', [__CLASS__, 'ajax_reset_settings']);
        add_action('wp_ajax_ts_reset_tab', [__CLASS__, 'ajax_reset_tab']);
        add_action('wp_ajax_ts_import_settings', [__CLASS__, 'ajax_import_settings']);
        add_action('wp_ajax_ts_export_settings', [__CLASS__, 'ajax_export_settings']);
    }
    
    // =========================================================================
    // DEFAULT VALUES METHODS
    // =========================================================================
    
    /**
     * Get all default values from configuration
     * 
     * @return array All default values keyed by field ID
     */
    public static function get_all_defaults() {
        // Return cached if available
        if (self::$defaults !== null) {
            return self::$defaults;
        }
        
        self::$defaults = [];
        
        // Check if config class exists
        if (!class_exists('TS_Settings_Config')) {
            return self::$defaults;
        }
        
        // Get field configuration
        $config = TS_Settings_Config::get_fields();
        
        // Loop through all tabs, sections, and fields
        foreach ($config as $tab_id => $tab) {
            if (!isset($tab['sections'])) {
                continue;
            }
            
            foreach ($tab['sections'] as $section_id => $section) {
                if (!isset($section['fields'])) {
                    continue;
                }
                
                foreach ($section['fields'] as $field_id => $field) {
                    // Get default value based on field type
                    self::$defaults[$field_id] = self::get_field_default($field);
                }
            }
        }
        
        return self::$defaults;
    }
    
    /**
     * Get a single default value by field ID
     * 
     * @param string $field_id Field ID
     * @return mixed Default value or null if not found
     */
    public static function get_default($field_id) {
        $defaults = self::get_all_defaults();
        return isset($defaults[$field_id]) ? $defaults[$field_id] : null;
    }
    
    /**
     * Get default value for a specific field based on its configuration
     * 
     * @param array $field Field configuration
     * @return mixed Default value based on field type
     */
    public static function get_field_default($field) {
        // If default is explicitly set, use it
        if (isset($field['default'])) {
            return $field['default'];
        }
        
        // Otherwise, return type-appropriate default
        $type = isset($field['type']) ? $field['type'] : 'text';
        
        switch ($type) {
            // Text-based fields
            case 'text':
            case 'textarea':
            case 'wysiwyg':
            case 'url':
            case 'email':
            case 'tel':
            case 'shortcode':
                return '';
            
            // Numeric fields
            case 'number':
                return isset($field['min']) ? $field['min'] : 0;
            
            case 'range':
                // Return middle value if not set
                $min = isset($field['min']) ? $field['min'] : 0;
                $max = isset($field['max']) ? $field['max'] : 100;
                return round(($min + $max) / 2);
            
            // Boolean fields
            case 'switch':
            case 'checkbox':
                return false;
            
            // Choice fields - return first option
            case 'radio':
            case 'select':
            case 'image_select':
                if (isset($field['options']) && is_array($field['options'])) {
                    $keys = array_keys($field['options']);
                    return !empty($keys) ? $keys[0] : '';
                }
                return '';
            
            // Multi-choice fields
            case 'checkbox_group':
            case 'multiselect':
                return [];
            
            // Color fields
            case 'color':
                return '#000000';
            
            // Gradient field
            case 'gradient':
                return [
                    'type'   => 'linear',
                    'angle'  => 90,
                    'color1' => '#000000',
                    'color2' => '#ffffff',
                ];
            
            // Media fields
            case 'media':
            case 'image':
            case 'video':
            case 'file':
                return [
                    'id'  => 0,
                    'url' => '',
                ];
            
            // WordPress relations
            case 'page_select':
            case 'post_select':
            case 'category_select':
            case 'menu_select':
                return '';
            
            // Typography
            case 'typography':
                return 'Open Sans';
            
            // Icon
            case 'icon':
                return '';
            
            // Repeater
            case 'repeater':
                return [];
            
            // Date/Time
            case 'date':
            case 'time':
            case 'datetime':
                return '';
            
            default:
                return '';
        }
    }
    
    // =========================================================================
    // REGISTRATION METHODS
    // =========================================================================
    
    /**
     * Register all defaults to database (on theme activation)
     */
    public static function register_defaults() {
        $defaults = self::get_all_defaults();
        $existing = get_option(self::OPTION_NAME, []);
        
        // Merge: existing values take priority over defaults
        $merged = wp_parse_args($existing, $defaults);
        
        // Save to database
        update_option(self::OPTION_NAME, $merged);
        
        // Set flag that defaults have been registered
        update_option(self::DEFAULTS_FLAG, true);
    }
    
    /**
     * Maybe register defaults (if not already done)
     */
    public static function maybe_register_defaults() {
        // Check if defaults have been registered
        if (!get_option(self::DEFAULTS_FLAG)) {
            self::register_defaults();
        }
    }
    
    /**
     * Force re-register defaults (useful for theme updates)
     * This will add new defaults but preserve existing values
     */
    public static function refresh_defaults() {
        delete_option(self::DEFAULTS_FLAG);
        self::$defaults = null; // Clear cache
        self::register_defaults();
    }
    
    // =========================================================================
    // RESET METHODS
    // =========================================================================
    
    /**
     * Reset all options to defaults
     * 
     * @param bool $confirm Safety flag, must be true to reset
     * @return bool Success status
     */
    public static function reset_to_defaults($confirm = false) {
        if ($confirm !== true) {
            return false;
        }
        
        $defaults = self::get_all_defaults();
        update_option(self::OPTION_NAME, $defaults);
        
        return true;
    }
    
    /**
     * Reset specific fields to their defaults
     * 
     * @param array $field_ids Array of field IDs to reset
     * @return bool Success status
     */
    public static function reset_fields($field_ids) {
        if (!is_array($field_ids) || empty($field_ids)) {
            return false;
        }
        
        $defaults = self::get_all_defaults();
        $options = get_option(self::OPTION_NAME, []);
        
        foreach ($field_ids as $field_id) {
            if (isset($defaults[$field_id])) {
                $options[$field_id] = $defaults[$field_id];
            }
        }
        
        update_option(self::OPTION_NAME, $options);
        
        return true;
    }
    
    /**
     * Reset a specific tab to defaults
     * 
     * @param string $tab_id Tab ID to reset
     * @return bool Success status
     */
    public static function reset_tab($tab_id) {
        if (!class_exists('TS_Settings_Config')) {
            return false;
        }
        
        $config = TS_Settings_Config::get_fields();
        
        if (!isset($config[$tab_id])) {
            return false;
        }
        
        $field_ids = [];
        
        if (isset($config[$tab_id]['sections'])) {
            foreach ($config[$tab_id]['sections'] as $section) {
                if (isset($section['fields'])) {
                    $field_ids = array_merge($field_ids, array_keys($section['fields']));
                }
            }
        }
        
        return self::reset_fields($field_ids);
    }
    
    /**
     * Reset a specific section to defaults
     * 
     * @param string $tab_id Tab ID
     * @param string $section_id Section ID
     * @return bool Success status
     */
    public static function reset_section($tab_id, $section_id) {
        if (!class_exists('TS_Settings_Config')) {
            return false;
        }
        
        $config = TS_Settings_Config::get_fields();
        
        if (!isset($config[$tab_id]['sections'][$section_id]['fields'])) {
            return false;
        }
        
        $field_ids = array_keys($config[$tab_id]['sections'][$section_id]['fields']);
        
        return self::reset_fields($field_ids);
    }
    
    // =========================================================================
    // UTILITY METHODS
    // =========================================================================
    
    /**
     * Check if a field has been modified from its default
     * 
     * @param string $field_id Field ID
     * @return bool True if modified, false if still default
     */
    public static function is_modified($field_id) {
        $default = self::get_default($field_id);
        $options = get_option(self::OPTION_NAME, []);
        $current = isset($options[$field_id]) ? $options[$field_id] : null;
        
        return $current !== null && $current !== $default;
    }
    
    /**
     * Get all modified fields (non-default values)
     * 
     * @return array Field IDs that have been modified
     */
    public static function get_modified_fields() {
        $defaults = self::get_all_defaults();
        $options = get_option(self::OPTION_NAME, []);
        $modified = [];
        
        foreach ($defaults as $field_id => $default) {
            if (isset($options[$field_id]) && $options[$field_id] !== $default) {
                $modified[] = $field_id;
            }
        }
        
        return $modified;
    }
    
    /**
     * Get count of modified fields
     * 
     * @return int
     */
    public static function get_modified_count() {
        return count(self::get_modified_fields());
    }
    
    /**
     * Clear defaults cache
     */
    public static function clear_cache() {
        self::$defaults = null;
    }
    
    /**
     * Export defaults as JSON (for documentation or migration)
     * 
     * @return string JSON encoded defaults
     */
    public static function export_defaults_json() {
        return json_encode(self::get_all_defaults(), JSON_PRETTY_PRINT);
    }
    
    // =========================================================================
    // AJAX HANDLERS
    // =========================================================================
    
    /**
     * AJAX: Reset all settings to defaults
     */
    public static function ajax_reset_settings() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'nametheme'));
        }
        
        $result = self::reset_to_defaults(true);
        
        if ($result) {
            wp_send_json_success(__('Settings reset to defaults', 'nametheme'));
        } else {
            wp_send_json_error(__('Failed to reset settings', 'nametheme'));
        }
    }
    
    /**
     * AJAX: Reset specific tab to defaults
     */
    public static function ajax_reset_tab() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'nametheme'));
        }
        
        $tab_id = isset($_POST['tab_id']) ? sanitize_text_field($_POST['tab_id']) : '';
        
        if (empty($tab_id)) {
            wp_send_json_error(__('No tab specified', 'nametheme'));
        }
        
        $result = self::reset_tab($tab_id);
        
        if ($result) {
            wp_send_json_success(__('Tab reset to defaults', 'nametheme'));
        } else {
            wp_send_json_error(__('Failed to reset tab', 'nametheme'));
        }
    }
    
    /**
     * AJAX: Import settings
     */
    public static function ajax_import_settings() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'nametheme'));
        }
        
        $settings_json = isset($_POST['settings']) ? wp_unslash($_POST['settings']) : '';
        
        if (empty($settings_json)) {
            wp_send_json_error(__('No settings data provided', 'nametheme'));
        }
        
        $settings = json_decode($settings_json, true);
        
        if (!is_array($settings)) {
            wp_send_json_error(__('Invalid JSON format', 'nametheme'));
        }
        
        // Merge with defaults to ensure all fields have values
        $defaults = self::get_all_defaults();
        $merged = wp_parse_args($settings, $defaults);
        
        // Sanitize before saving (if sanitizer class exists)
        if (class_exists('TS_Field_Sanitizer') && method_exists('TS_Field_Sanitizer', 'sanitize_all')) {
            $merged = TS_Field_Sanitizer::sanitize_all($merged);
        }
        
        update_option(self::OPTION_NAME, $merged);
        
        wp_send_json_success(__('Settings imported successfully', 'nametheme'));
    }
    
    /**
     * AJAX: Export settings
     */
    public static function ajax_export_settings() {
        check_ajax_referer('ts_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'nametheme'));
        }
        
        $options = get_option(self::OPTION_NAME, []);
        
        wp_send_json_success([
            'settings' => $options,
            'json'     => json_encode($options, JSON_PRETTY_PRINT),
        ]);
    }
}

// Initialize the defaults handler
TS_Defaults_Handler::init();