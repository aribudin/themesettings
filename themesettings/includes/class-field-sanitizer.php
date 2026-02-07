<?php
/**
 * Field Sanitizer Class
 * 
 * Handles sanitization for all field types
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Field_Sanitizer {
    /**
     * Sanitize all options
     */
    public static function sanitize_all($input) {
        if (!is_array($input)) {
            return [];
        }
        
        // ===== FIX: Ambil data existing dari database =====
        $existing_options = get_option('ts_options', []);
        
        $config = TS_Settings_Config::get_fields();
        $sanitized = [];
        
        foreach ($config as $tab_id => $tab) {
            if (!isset($tab['sections'])) continue;
            
            foreach ($tab['sections'] as $section_id => $section) {
                if (!isset($section['fields'])) continue;
                
                foreach ($section['fields'] as $field_id => $field) {
                    $key = $field_id;
                    
                    // ===== FIX: Prioritas data baru, fallback ke data lama =====
                    if (isset($input[$key])) {
                        // Ada data baru dari form yang di-submit
                        $sanitized[$key] = self::sanitize_field($input[$key], $field);
                    } elseif (isset($existing_options[$key])) {
                        // Tidak ada di input (tab lain), gunakan data lama
                        $sanitized[$key] = $existing_options[$key];
                    }
                }
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize single field based on type
     */
    public static function sanitize_field($value, $field) {
        $type = isset($field['type']) ? $field['type'] : 'text';
        
        switch ($type) {
            case 'text':
            case 'tel':
                return sanitize_text_field($value);
                
            case 'textarea':
                return sanitize_textarea_field($value);
                
            case 'wysiwyg':
                return wp_kses_post($value);
                
            case 'number':
            case 'range':
                return self::sanitize_number($value, $field);
                
            case 'url':
                return esc_url_raw($value);
                
            case 'email':
                return sanitize_email($value);
                
            case 'switch':
            case 'checkbox':
                return (bool) $value;
                
            case 'checkbox_group':
            case 'multiselect':
                return self::sanitize_array($value);
                
            case 'radio':
            case 'select':
            case 'image_select':
            case 'page_select':
            case 'post_select':
            case 'category_select':
            case 'menu_select':
            case 'typography':
                return sanitize_text_field($value);
                
            case 'color':
                return self::sanitize_color($value);
                
            case 'gradient':
                return self::sanitize_gradient($value);
                
            case 'media':
            case 'image':
            case 'video':
            case 'file':
                return self::sanitize_media($value);
                
            case 'repeater':
                return self::sanitize_repeater($value, $field);
                
            case 'date':
            case 'time':
            case 'datetime':
                return sanitize_text_field($value);
                
            case 'shortcode':
                return self::sanitize_shortcode($value);
                
            default:
                return sanitize_text_field($value);
        }
    }
    
    /**
     * Sanitize number
     */
    private static function sanitize_number($value, $field) {
        $value = floatval($value);
        
        if (isset($field['min']) && $value < $field['min']) {
            $value = $field['min'];
        }
        
        if (isset($field['max']) && $value > $field['max']) {
            $value = $field['max'];
        }
        
        return $value;
    }
    
    /**
     * Sanitize array values
     */
    private static function sanitize_array($value) {
        if (!is_array($value)) {
            return [];
        }
        
        return array_map('sanitize_text_field', $value);
    }
    
    /**
     * Sanitize color value
     */
    private static function sanitize_color($value) {
        // Allow rgba colors
        if (preg_match('/^rgba?\([\d\s,\.]+\)$/i', $value)) {
            return $value;
        }
        
        // Standard hex color
        if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $value)) {
            return $value;
        }
        
        return '';
    }
    
    /**
     * Sanitize gradient value
     */
    private static function sanitize_gradient($value) {
        if (!is_array($value)) {
            return [];
        }
        
        return [
            'type' => isset($value['type']) ? sanitize_text_field($value['type']) : 'linear',
            'angle' => isset($value['angle']) ? intval($value['angle']) : 90,
            'color1' => isset($value['color1']) ? self::sanitize_color($value['color1']) : '#000000',
            'color2' => isset($value['color2']) ? self::sanitize_color($value['color2']) : '#ffffff',
        ];
    }
    
    /**
     * Sanitize media value
     */
    private static function sanitize_media($value) {
        if (is_array($value)) {
            return [
                'id' => isset($value['id']) ? absint($value['id']) : 0,
                'url' => isset($value['url']) ? esc_url_raw($value['url']) : '',
            ];
        }
        
        return absint($value);
    }
    
    /**
     * Sanitize repeater value
     */
    private static function sanitize_repeater($value, $field) {
        if (!is_array($value)) {
            return [];
        }
        
        $sanitized = [];
        $subfields = isset($field['fields']) ? $field['fields'] : [];
        
        foreach ($value as $index => $item) {
            if (!is_array($item)) continue;
            
            $sanitized_item = [];
            
            foreach ($subfields as $subfield_id => $subfield) {
                if (isset($item[$subfield_id])) {
                    $sanitized_item[$subfield_id] = self::sanitize_field($item[$subfield_id], $subfield);
                }
            }
            
            if (!empty($sanitized_item)) {
                $sanitized[] = $sanitized_item;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize shortcode
     */
    private static function sanitize_shortcode($value) {
        // Allow shortcode brackets and basic content
        $value = wp_kses($value, []);
        return $value;
    }
}