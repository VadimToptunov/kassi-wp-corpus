<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: the-moneytizer 10.0.1, core/core_ajax.php (excerpt; elided context marked '...')
// CVE-2023-6966 (fixed). Copyright the the-moneytizer authors.
function themoneytizer_update_language() {
    if ( !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['_nonce'])), 'update_language') ) {
        return;
    }

    if(!current_user_can( 'manage_options' )){
        return 0;
    }
    
    if(isset($_POST['language'])){
        update_option('themoneytizer_data_language', $_POST['language']);
        $response = array('status' => true);
        echo json_encode($response);
        return;
    }
    $response = array('status' => false);
    echo json_encode($response);
}
add_action('wp_ajax_update_language', 'themoneytizer_update_language');
