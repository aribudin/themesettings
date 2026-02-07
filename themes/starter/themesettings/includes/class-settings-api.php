<?php
/**
 * Settings API Class
 * 
 * Handles settings registration, retrieval, and management
 * with automatic default values support.
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Settings_API {
    
    /**
     * Option name in database
     */
    const OPTION_NAME = 'ts_options';
    
    /**
     * Option name for defaults registered flag
     */
    const DEFAULTS_FLAG = 'ts_defaults_registered';
    
    /**
     * Cached options
     */
    private static $cache = null;
    
    /**
     * Cached defaults
     */
    private static $defaults_cache = null;
    
    // =========================================================================
    // CORE GETTER METHODS
    // =========================================================================
    
    /**
     * Get all options (raw, without defaults)
     * 
     * @return array
     */
    public static function get_options() {
        if (self::$cache === null) {
            self::$cache = get_option(self::OPTION_NAME, []);
        }
        return self::$cache;
    }
    
    /**
     * Get all options merged with defaults
     * 
     * @return array All options with defaults filled in
     */
    public static function get_all_options() {
        $defaults = self::get_all_defaults();
        $saved = self::get_options();
        
        return wp_parse_args($saved, $defaults);
    }
    
    /**
     * Get single option with automatic default fallback
     * 
     * @param string $key Option key
     * @param mixed $default Custom default (optional)
     * @return mixed Option value
     */
    public static function get_option($key, $default = null) {
        $options = self::get_options();
        
        // Return saved value if exists and not empty
        if (isset($options[$key]) && $options[$key] !== '' && $options[$key] !== null) {
            return $options[$key];
        }
        
        // Return custom default if provided
        if ($default !== null) {
            return $default;
        }
        
        // Return default from field configuration
        return self::get_default($key);
    }
    
    /**
     * Get option with strict check (null if not set)
     * 
     * @param string $key Option key
     * @return mixed|null
     */
    public static function get_option_strict($key) {
        $options = self::get_options();
        return isset($options[$key]) ? $options[$key] : null;
    }
    
    /**
     * Get multiple options at once
     * 
     * @param array $keys Array of option keys
     * @return array Associative array of values
     */
    public static function get_multiple($keys) {
        $result = [];
        foreach ((array) $keys as $key) {
            $result[$key] = self::get_option($key);
        }
        return $result;
    }
    
    /**
     * Check if option exists and has value
     * 
     * @param string $key Option key
     * @return bool
     */
    public static function has_option($key) {
        $options = self::get_options();
        return isset($options[$key]) && $options[$key] !== '' && $options[$key] !== null;
    }
    
    /**
     * Check if option is different from default
     * 
     * @param string $key Option key
     * @return bool
     */
    public static function is_modified($key) {
        $current = self::get_option_strict($key);
        $default = self::get_default($key);
        
        return $current !== null && $current !== $default;
    }
    
    // =========================================================================
    // SETTER METHODS
    // =========================================================================
    
    /**
     * Set single option
     * 
     * @param string $key Option key
     * @param mixed $value Option value
     * @return bool Success status
     */
    public static function set_option($key, $value) {
        $options = self::get_options();
        $options[$key] = $value;
        
        self::clear_cache();
        return update_option(self::OPTION_NAME, $options);
    }
    
    /**
     * Set multiple options at once
     * 
     * @param array $data Key-value pairs
     * @return bool Success status
     */
    public static function set_multiple($data) {
        $options = self::get_options();
        
        foreach ((array) $data as $key => $value) {
            $options[$key] = $value;
        }
        
        self::clear_cache();
        return update_option(self::OPTION_NAME, $options);
    }
    
    /**
     * Update all options (replace entire array)
     * 
     * @param array $options New options array
     * @return bool Success status
     */
    public static function update_options($options) {
        self::clear_cache();
        return update_option(self::OPTION_NAME, $options);
    }
    
    /**
     * Delete single option
     * 
     * @param string $key Option key
     * @return bool Success status
     */
    public static function delete_option($key) {
        $options = self::get_options();
        
        if (isset($options[$key])) {
            unset($options[$key]);
            self::clear_cache();
            return update_option(self::OPTION_NAME, $options);
        }
        
        return false;
    }
    
    /**
     * Delete multiple options
     * 
     * @param array $keys Option keys to delete
     * @return bool Success status
     */
    public static function delete_multiple($keys) {
        $options = self::get_options();
        $changed = false;
        
        foreach ((array) $keys as $key) {
            if (isset($options[$key])) {
                unset($options[$key]);
                $changed = true;
            }
        }
        
        if ($changed) {
            self::clear_cache();
            return update_option(self::OPTION_NAME, $options);
        }
        
        return false;
    }
    
    // =========================================================================
    // DEFAULT VALUES METHODS
    // =========================================================================
    
    /**
     * Get all default values from field configuration
     * 
     * @return array All defaults keyed by field ID
     */
    public static function get_all_defaults() {
        if (self::$defaults_cache !== null) {
            return self::$defaults_cache;
        }
        
        self::$defaults_cache = [];
        
        // Check if config class exists
        if (!class_exists('TS_Settings_Config')) {
            return self::$defaults_cache;
        }
        
        $config = TS_Settings_Config::get_fields();
        
        foreach ($config as $tab_id => $tab) {
            if (!isset($tab['sections'])) {
                continue;
            }
            
            foreach ($tab['sections'] as $section_id => $section) {
                if (!isset($section['fields'])) {
                    continue;
                }
                
                foreach ($section['fields'] as $field_id => $field) {
                    self::$defaults_cache[$field_id] = self::get_field_default($field);
                }
            }
        }
        
        return self::$defaults_cache;
    }
    
    /**
     * Get default value for a single field
     * 
     * @param string $key Field ID
     * @return mixed Default value
     */
    public static function get_default($key) {
        $defaults = self::get_all_defaults();
        return isset($defaults[$key]) ? $defaults[$key] : null;
    }
    
    /**
     * Get default value based on field configuration
     * 
     * @param array $field Field config array
     * @return mixed Default value
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
                $min = isset($field['min']) ? $field['min'] : 0;
                $max = isset($field['max']) ? $field['max'] : 100;
                return round(($min + $max) / 2);
            
            // Boolean fields
            case 'switch':
            case 'checkbox':
                return false;
            
            // Single choice fields
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
    // RESET METHODS
    // =========================================================================
    
    /**
     * Reset all options to defaults
     * 
     * @param bool $confirm Must be true to execute
     * @return bool Success status
     */
    public static function reset_options($confirm = false) {
        if ($confirm !== true) {
            return false;
        }
        
        $defaults = self::get_all_defaults();
        self::clear_cache();
        
        return update_option(self::OPTION_NAME, $defaults);
    }
    
    /**
     * Reset specific fields to their defaults
     * 
     * @param array $keys Field IDs to reset
     * @return bool Success status
     */
    public static function reset_fields($keys) {
        if (!is_array($keys) || empty($keys)) {
            return false;
        }
        
        $options = self::get_options();
        $defaults = self::get_all_defaults();
        
        foreach ($keys as $key) {
            if (isset($defaults[$key])) {
                $options[$key] = $defaults[$key];
            }
        }
        
        self::clear_cache();
        return update_option(self::OPTION_NAME, $options);
    }
    
    /**
     * Reset a specific tab to defaults
     * 
     * @param string $tab_id Tab ID
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
        
        foreach ($config[$tab_id]['sections'] as $section) {
            if (isset($section['fields'])) {
                $field_ids = array_merge($field_ids, array_keys($section['fields']));
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
    // INITIALIZATION METHODS
    // =========================================================================
    
    /**
     * Register defaults on theme activation
     * Merges defaults with any existing values
     */
    public static function register_defaults() {
        $defaults = self::get_all_defaults();
        $existing = get_option(self::OPTION_NAME, []);
        
        // Merge: existing values take priority
        $merged = wp_parse_args($existing, $defaults);
        
        update_option(self::OPTION_NAME, $merged);
        update_option(self::DEFAULTS_FLAG, true);
        
        self::clear_cache();
    }
    
    /**
     * Maybe register defaults (if not already done)
     */
    public static function maybe_register_defaults() {
        if (!get_option(self::DEFAULTS_FLAG)) {
            self::register_defaults();
        }
    }
    
    /**
     * Force refresh defaults (for theme updates)
     * Adds new defaults but preserves existing values
     */
    public static function refresh_defaults() {
        delete_option(self::DEFAULTS_FLAG);
        self::register_defaults();
    }
    
    // =========================================================================
    // IMPORT/EXPORT METHODS
    // =========================================================================
    
    /**
     * Export options as JSON
     * 
     * @param bool $include_defaults Include default values in export
     * @return string JSON encoded options
     */
    public static function export_options($include_defaults = false) {
        $options = $include_defaults ? self::get_all_options() : self::get_options();
        return json_encode($options, JSON_PRETTY_PRINT);
    }
    
    /**
     * Import options from JSON
     * 
     * @param string $json JSON encoded options
     * @param bool $merge Merge with existing (true) or replace all (false)
     * @return bool Success status
     */
    public static function import_options($json, $merge = true) {
        $imported = json_decode($json, true);
        
        if (!is_array($imported)) {
            return false;
        }
        
        if ($merge) {
            $existing = self::get_options();
            $imported = wp_parse_args($imported, $existing);
        }
        
        // Ensure defaults are present
        $defaults = self::get_all_defaults();
        $imported = wp_parse_args($imported, $defaults);
        
        self::clear_cache();
        return update_option(self::OPTION_NAME, $imported);
    }
    
    /**
     * Export defaults as JSON (for documentation)
     * 
     * @return string JSON encoded defaults
     */
    public static function export_defaults() {
        return json_encode(self::get_all_defaults(), JSON_PRETTY_PRINT);
    }
    
    // =========================================================================
    // UTILITY METHODS
    // =========================================================================
    
    /**
     * Clear internal cache
     */
    public static function clear_cache() {
        self::$cache = null;
    }
    
    /**
     * Clear defaults cache
     */
    public static function clear_defaults_cache() {
        self::$defaults_cache = null;
    }
    
    /**
     * Get list of modified fields (different from defaults)
     * 
     * @return array Field IDs that have been modified
     */
    public static function get_modified_fields() {
        $defaults = self::get_all_defaults();
        $options = self::get_options();
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
     * Validate option key exists in configuration
     * 
     * @param string $key Option key
     * @return bool
     */
    public static function is_valid_key($key) {
        $defaults = self::get_all_defaults();
        return array_key_exists($key, $defaults);
    }
    
    /**
     * Get field type for a given key
     * 
     * @param string $key Option key
     * @return string|null Field type or null if not found
     */
    public static function get_field_type($key) {
        if (!class_exists('TS_Settings_Config')) {
            return null;
        }
        
        $config = TS_Settings_Config::get_fields();
        
        foreach ($config as $tab) {
            if (!isset($tab['sections'])) {
                continue;
            }
            
            foreach ($tab['sections'] as $section) {
                if (!isset($section['fields'])) {
                    continue;
                }
                
                if (isset($section['fields'][$key]['type'])) {
                    return $section['fields'][$key]['type'];
                }
            }
        }
        
        return null;
    }
}