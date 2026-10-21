<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wpbookit 1.0.5, core/admin/classes/controllers/class.wpb-booking-type-controller.php (excerpt; elided context marked '...')
// CVE-2025-6058 (fixed). Copyright the wpbookit authors.
final class WPB_Booking_type_Controller
{
    // ...
    public function image_upload_handle($tmp_file_path, $add_image_url)
    {
        $file_name = basename($add_image_url["name"]);
        
        // Validate file type - only allow image files
        $validate = wp_check_filetype($file_name);
        if ($validate['type'] == false) {
            return new WP_Error('invalid_file_type', __('File type is not allowed. Only image files are permitted.', 'wpbookit'));
        }
        
        // Additional check to ensure it's an image MIME type
        $allowed_mime_types = array(
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp'
        );
        
        if (!in_array($validate['type'], $allowed_mime_types)) {
            return new WP_Error('invalid_mime_type', __('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.', 'wpbookit'));
        }
        
        // Validate file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if (!in_array($file_ext, $allowed_extensions)) {
            return new WP_Error('invalid_extension', __('Invalid file extension. Only JPG, PNG, GIF, and WebP files are allowed.', 'wpbookit'));
        }

        $uploads_dir = wp_upload_dir();
        $destination_file_path = $uploads_dir["path"] . "/" . $file_name;

        // Move the uploaded file to the destination path
        if (move_uploaded_file($tmp_file_path, $destination_file_path)) :  //phpcs:ignore Generic.PHP.ForbiddenFunctions.Found 
            $file_url = $uploads_dir["url"] . "/" . $file_name;

            // Attempt to sideload the image
            $return_type = "id";
            $attachment_id = media_sideload_image($file_url, 0, "avatar", $return_type);

            return $attachment_id;
        else :
            // Move uploaded file failed, handle the error
            return new WP_Error('upload_failed', __('Failed to upload file.', 'wpbookit'));
        endif;
    }
}
