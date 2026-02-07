<?php
/**
 * Basic Field Types
 * 
 * Text, Textarea, WYSIWYG, Number, URL, Email, Tel
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

// Basic fields are handled directly in the Field Renderer class
// This file is for any additional helper functions or filters

/**
 * Add placeholder support to inputs
 */
function ts_basic_field_attributes($field) {
    $attrs = [];
    
    if (isset($field['placeholder'])) {
        $attrs[] = 'placeholder="' . esc_attr($field['placeholder']) . '"';
    }
    
    if (isset($field['required']) && $field['required']) {
        $attrs[] = 'required';
    }
    
    if (isset($field['readonly']) && $field['readonly']) {
        $attrs[] = 'readonly';
    }
    
    if (isset($field['disabled']) && $field['disabled']) {
        $attrs[] = 'disabled';
    }
    
    return implode(' ', $attrs);
}

/**
 * Validate URL field
 */
function ts_validate_url($value) {
    if (empty($value)) {
        return '';
    }
    
    // Add http:// if missing
    if (!preg_match('~^(?:f|ht)tps?://~i', $value)) {
        $value = 'http://' . $value;
    }
    
    return filter_var($value, FILTER_VALIDATE_URL) ? $value : '';
}

/**
 * Validate email field
 */
function ts_validate_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
}

/**
 * Format phone number
 */
function ts_format_phone($value) {
    // Remove all non-numeric characters except + for country code
    $cleaned = preg_replace('/[^0-9+]/', '', $value);
    return $cleaned;
}
