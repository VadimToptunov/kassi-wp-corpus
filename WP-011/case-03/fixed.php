<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wpbookit 1.0.5, core/admin/classes/controllers/class.wpb-profile-controller.php (excerpt; elided context marked '...')
// CVE-2025-6057 (fixed). Copyright the wpbookit authors.
final class WPB_Profile_controller
{
    // ...
    public function handle_image_upload($user_id, $image)
    {
        // Load necessary WordPress file handling functions
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        // Validate file type
        $file_info = wp_check_filetype_and_ext(
            $image['tmp_name'], 
            $image['name']
        );
        
        // Only allow specific image types
        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        
        if (empty($file_info['type']) || !in_array($file_info['type'], $allowed_types)) {
            return false;
        }

        // Prepare upload parameters
        $upload_overrides = array(
            'test_form' => false,
            'mimes' => array(
                'jpg|jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            )
        );
        
        // Handle the upload securely
        $movefile = wp_handle_upload($image, $upload_overrides);
        
        if ($movefile && !isset($movefile['error'])) {
            // Create attachment post
            $attachment = array(
                'post_mime_type' => $movefile['type'],
                'post_title' => sanitize_file_name(basename($movefile['file'])),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            $attachment_id = wp_insert_attachment($attachment, $movefile['file']);
            
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $movefile['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                
                update_user_meta($user_id, "wp_user_avatar", $attachment_id);
                return $movefile['url'];
            }
        }
        
        return false;
    }
}
