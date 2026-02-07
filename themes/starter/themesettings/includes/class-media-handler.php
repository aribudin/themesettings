<?php
/**
 * Media Handler Class
 * 
 * Handles media uploads and processing
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Media_Handler {
    
    /**
     * Process upload
     */
    public function process_upload() {
        if (!function_exists('wp_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        if (!isset($_FILES['file'])) {
            return [
                'success' => false,
                'message' => __('No file uploaded.', 'nametheme')
            ];
        }
        
        $upload_overrides = ['test_form' => false];
        $uploaded_file = wp_handle_upload($_FILES['file'], $upload_overrides);
        
        if (isset($uploaded_file['error'])) {
            return [
                'success' => false,
                'message' => $uploaded_file['error']
            ];
        }
        
        // Insert as attachment
        $attachment = [
            'guid' => $uploaded_file['url'],
            'post_mime_type' => $uploaded_file['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploaded_file['file'])),
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        
        $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file']);
        
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        
        return [
            'success' => true,
            'data' => [
                'id' => $attachment_id,
                'url' => $uploaded_file['url'],
                'filename' => basename($uploaded_file['file'])
            ]
        ];
    }
    
    /**
     * Get attachment data
     */
    public function get_attachment_data($attachment_id) {
        $attachment = get_post($attachment_id);
        
        if (!$attachment) {
            return null;
        }
        
        return [
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id),
            'title' => $attachment->post_title,
            'filename' => basename(get_attached_file($attachment_id)),
            'mime_type' => $attachment->post_mime_type,
        ];
    }
    
    /**
     * Get image sizes
     */
    public function get_image_sizes($attachment_id) {
        $sizes = [];
        $metadata = wp_get_attachment_metadata($attachment_id);
        
        if (!$metadata || !isset($metadata['sizes'])) {
            return $sizes;
        }
        
        foreach ($metadata['sizes'] as $size => $data) {
            $sizes[$size] = [
                'width' => $data['width'],
                'height' => $data['height'],
                'url' => wp_get_attachment_image_url($attachment_id, $size),
            ];
        }
        
        return $sizes;
    }
}
