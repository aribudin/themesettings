<?php
/**
 * Toggle & Choice Field Types
 * 
 * Switch, Checkbox, Checkbox Group, Radio, Select, Multiselect
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get options from various sources
 */
function ts_get_field_options($field) {
    $options = [];
    
    // Static options
    if (isset($field['options']) && is_array($field['options'])) {
        return $field['options'];
    }
    
    // Callback function
    if (isset($field['options_callback']) && is_callable($field['options_callback'])) {
        return call_user_func($field['options_callback']);
    }
    
    return $options;
}

/**
 * Helper to check if option is selected
 */
function ts_is_selected($value, $option_value) {
    if (is_array($value)) {
        return in_array($option_value, $value);
    }
    return $value == $option_value;
}

/**
 * Convert checkbox value to boolean
 */
function ts_checkbox_to_bool($value) {
    return !empty($value) && $value !== 'false' && $value !== '0';
}

/**
 * Get yes/no options for basic toggles
 */
function ts_get_yes_no_options() {
    return [
        '1' => __('Yes', 'nametheme'),
        '0' => __('No', 'nametheme'),
    ];
}
