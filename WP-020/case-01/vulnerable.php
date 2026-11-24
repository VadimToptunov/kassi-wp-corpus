<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Canonical cross-role stored XSS shape documented for WP-020. The IDE inspection is a no-op
// here because the real engine is plugin-wide (bounty-pipeline StoredXssCheck); this file is
// kept for shape documentation only.

// writer: public via wp_ajax_nopriv_*
add_action( 'wp_ajax_nopriv_p_save', 'p_save_html' );
function p_save_html() {
    update_option( 'p_html', $_POST['html'] );
    wp_send_json_success();
}

// reader: admin settings screen
function p_render_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    echo get_option( 'p_html' );
}
