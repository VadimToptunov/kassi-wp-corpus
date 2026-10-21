<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wpbookit 1.0.4, core/admin/classes/controllers/class.wpb-booking-type-controller.php (excerpt; elided context marked '...')
// CVE-2025-6058. Copyright the wpbookit authors.
final class WPB_Booking_type_Controller
{
    // ...
    public function image_upload_handle($tmp_file_path, $add_image_url)
    {
        $file_name = basename($add_image_url["name"]);

        $uploads_dir = wp_upload_dir();
        $destination_file_path = $uploads_dir["path"] . "/" . $file_name;

        // Move the uploaded file to the destination path
        if (move_uploaded_file($tmp_file_path, $destination_file_path)) :  //phpcs:ignore Generic.PHP.ForbiddenFunctions.Found 
            $file_url = $uploads_dir["url"] . "/" . $file_name;

            // Attempt to sideload the image
            $return_type = "id";
            $attachment_id = media_sideload_image($file_url, "", "avatar", $return_type);

            return $attachment_id;
        else :
            // Move uploaded file failed, handle the error
            return new WP_Error('fail');
        endif;
    }
}
