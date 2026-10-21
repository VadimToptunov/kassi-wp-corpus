<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wpbookit 1.0.4, core/admin/classes/controllers/class.wpb-profile-controller.php (excerpt; elided context marked '...')
// CVE-2025-6057. Copyright the wpbookit authors.
final class WPB_Profile_controller
{
    // ...
    public function handle_image_upload($user_id, $tmp_file_path, $image)
    {
        $file_name = basename($image["name"]);
        $uploads_dir = wp_upload_dir();
        $destination_file_path = $uploads_dir["path"] . "/" . $file_name;

        if (move_uploaded_file($tmp_file_path, $destination_file_path)) { // phpcs:ignore  Generic.PHP.ForbiddenFunctions.Found 
            $file_url = $uploads_dir["url"] . "/" . $file_name;

            $attachment_id = media_sideload_image($file_url, "", "avatar", "id");
            $attachment_url = wp_get_attachment_url($attachment_id);

            if (!is_wp_error($attachment_id) && $attachment_id) {
                update_user_meta($user_id, "wp_user_avatar", $attachment_id);
                return $attachment_url;
            } else {
                return false;
            }
        }
        return false;
    }
}
