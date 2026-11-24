<?php
// SPDX-License-Identifier: GPL-2.0-or-later

add_action( 'wp_ajax_nopriv_p_save', 'p_save_html' );
function p_save_html() {
    update_option( 'p_html', $_POST['html'] );
    wp_send_json_success();
}

function p_render_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    echo wp_kses_post( get_option( 'p_html' ) );
}
